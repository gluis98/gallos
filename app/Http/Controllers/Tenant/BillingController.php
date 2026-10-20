<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\DollarRateService;
use App\Services\PayPalService;
use App\Services\SettingsService;
use App\Services\SubscriptionActivationService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService,
        protected SubscriptionActivationService $activationService,
        protected PayPalService $paypal
    ) {}

    public function checkout(Request $request)
    {
        $planSku = $request->query('plan', SubscriptionService::SKU_PRO_MONTHLY);

        if ($planSku === SubscriptionService::SKU_EXTRA_GALPON) {
            return redirect()->route('tenant.payments.create', ['plan' => $planSku]);
        }

        $config = $this->subscriptionService->getPlanSkuConfig($planSku);

        if (! $config) {
            return redirect()->route('plans')->withErrors(['plan' => 'Plan no válido.']);
        }

        $summary = $this->subscriptionService->subscriptionSummary();
        $settings = SettingsService::get();
        $rate = DollarRateService::getCachedRate();
        $paypalEnabled = $this->paypal->isConfigured() && ($settings['paypal']['enabled'] ?? true);

        $upgradePitch = session('upgrade_pitch');
        if (! is_array($upgradePitch) && $request->filled('from')) {
            $upgradePitch = $this->subscriptionService->upgradePitch((string) $request->query('from'));
        }

        return view('tenant.billing.checkout', compact(
            'planSku',
            'config',
            'summary',
            'settings',
            'rate',
            'paypalEnabled',
            'upgradePitch'
        ));
    }

    public function success(Request $request)
    {
        return view('tenant.billing.success', [
            'summary' => $this->subscriptionService->subscriptionSummary(),
        ]);
    }

    public function paypalSubscribe(Request $request)
    {
        $request->validate(['plan' => ['required', 'in:pro_monthly,pro_yearly']]);

        $wantsJson = $request->expectsJson() || $request->ajax();

        if (! $this->paypal->isConfigured()) {
            $msg = 'PayPal no está configurado. Agrega PAYPAL_CLIENT_ID y PAYPAL_CLIENT_SECRET en el .env.';

            return $wantsJson
                ? response()->json(['error' => $msg], 422)
                : redirect()->route('billing.checkout', ['plan' => $request->plan])->withErrors(['paypal' => $msg]);
        }

        try {
            $settings = SettingsService::get();
            $tenantId = tenant('id');
            $returnUrl = route('billing.paypal.return', ['plan' => $request->plan], absolute: true);
            $cancelUrl = route('billing.checkout', ['plan' => $request->plan], absolute: true);

            // Asegurar HTTPS en URLs de retorno hacia PayPal
            if (str_starts_with((string) config('app.url'), 'https://')) {
                $returnUrl = preg_replace('#^http://#', 'https://', $returnUrl);
                $cancelUrl = preg_replace('#^http://#', 'https://', $cancelUrl);
            }

            $planId = $request->plan === 'pro_yearly'
                ? ($settings['paypal']['yearly_plan_id'] ?? '')
                : ($settings['paypal']['monthly_plan_id'] ?? '');

            if (empty($planId)) {
                $sku = $request->plan === 'pro_yearly'
                    ? SubscriptionService::SKU_PRO_YEARLY
                    : SubscriptionService::SKU_PRO_MONTHLY;
                $config = $this->subscriptionService->getPlanSkuConfig($sku);
                $order = $this->paypal->createOrder(
                    $config['amount'],
                    $config['currency'],
                    $config['name'],
                    $tenantId,
                    $returnUrl,
                    $cancelUrl
                );
                $approvalUrl = $this->paypal->getApprovalUrl($order);

                if (! $approvalUrl) {
                    throw new \RuntimeException('PayPal no devolvió URL de aprobación.');
                }

                return $wantsJson
                    ? response()->json(['type' => 'order', 'order_id' => $order['id'], 'approval_url' => $approvalUrl])
                    : redirect()->away($approvalUrl);
            }

            $subscription = $this->paypal->createSubscription($planId, $tenantId, $returnUrl, $cancelUrl);
            $approvalUrl = $this->paypal->getApprovalUrl($subscription);

            if (! $approvalUrl) {
                throw new \RuntimeException('PayPal no devolvió URL de aprobación.');
            }

            return $wantsJson
                ? response()->json([
                    'type' => 'subscription',
                    'subscription_id' => $subscription['id'],
                    'approval_url' => $approvalUrl,
                ])
                : redirect()->away($approvalUrl);
        } catch (\Throwable $e) {
            \Log::error('PayPal subscribe failed', ['error' => $e->getMessage()]);

            return $wantsJson
                ? response()->json(['error' => $e->getMessage()], 422)
                : redirect()->route('billing.checkout', ['plan' => $request->plan])->withErrors(['paypal' => $e->getMessage()]);
        }
    }

    public function paypalReturn(Request $request)
    {
        $planSku = $request->query('plan', SubscriptionService::SKU_PRO_MONTHLY);
        $subscriptionId = $request->query('subscription_id');
        $token = $request->query('token');

        try {
            if ($subscriptionId) {
                $this->handleSubscriptionApproval($subscriptionId, $planSku);
            } elseif ($token) {
                $this->handleOrderCapture($token, $planSku);
            } else {
                return redirect()->route('billing.checkout', ['plan' => $planSku])
                    ->withErrors(['paypal' => 'No se recibió confirmación de PayPal.']);
            }
        } catch (\Throwable $e) {
            return redirect()->route('billing.checkout', ['plan' => $planSku])
                ->withErrors(['paypal' => $e->getMessage()]);
        }

        return redirect()->route('billing.success');
    }

    public function paypalCapture(Request $request)
    {
        $request->validate([
            'order_id' => ['required', 'string'],
            'plan' => ['required', 'in:pro_monthly,pro_yearly'],
        ]);

        $this->handleOrderCapture($request->order_id, $request->plan);

        return response()->json(['ok' => true]);
    }

    protected function handleSubscriptionApproval(string $subscriptionId, string $planSku): void
    {
        $details = $this->paypal->getSubscription($subscriptionId);
        $status = $details['status'] ?? '';

        if (! in_array($status, ['ACTIVE', 'APPROVED'], true)) {
            throw new \RuntimeException('La suscripción PayPal no está activa.');
        }

        $tenantId = $details['custom_id'] ?? tenant('id');
        $config = $this->subscriptionService->getPlanSkuConfig($planSku);

        $this->activationService->recordPaymentOrder(
            $tenantId,
            $planSku,
            $config['amount'],
            $config['currency'],
            'paypal',
            'verificado',
            [
                'gateway_subscription_id' => $subscriptionId,
                'method' => 'paypal',
            ]
        );

        $this->activationService->activatePro($tenantId, $planSku, now(), [
            'gateway' => 'paypal',
            'paypal_subscription_id' => $subscriptionId,
            'paypal_payer_id' => $details['subscriber']['payer_id'] ?? null,
        ]);
    }

    protected function handleOrderCapture(string $orderId, string $planSku): void
    {
        $capture = $this->paypal->captureOrder($orderId);
        $status = $capture['status'] ?? '';

        if ($status !== 'COMPLETED') {
            throw new \RuntimeException('El pago no se completó en PayPal.');
        }

        $unit = $capture['purchase_units'][0] ?? [];
        $tenantId = $unit['custom_id'] ?? tenant('id');
        $amount = (float) ($unit['payments']['captures'][0]['amount']['value'] ?? 0);
        $config = $this->subscriptionService->getPlanSkuConfig($planSku);

        $this->activationService->recordPaymentOrder(
            $tenantId,
            $planSku,
            $amount ?: $config['amount'],
            $config['currency'],
            'paypal',
            'verificado',
            [
                'gateway_order_id' => $orderId,
                'method' => 'paypal',
            ]
        );

        $this->activationService->activatePro($tenantId, $planSku, now(), [
            'gateway' => 'paypal',
            'gateway_order_id' => $orderId,
        ]);
    }
}

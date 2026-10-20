<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\PaymentOrder;
use App\Models\User;
use App\Services\AuditService;
use App\Services\SettingsService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentOrderController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    public function create(Request $request)
    {
        $settings = SettingsService::get();
        $planSku = $request->query('plan', SubscriptionService::SKU_PRO_MONTHLY);
        $planConfig = $this->subscriptionService->getPlanSkuConfig($planSku);
        $summary = $this->subscriptionService->subscriptionSummary();

        return view('tenant.payments.create', compact('settings', 'planSku', 'planConfig', 'summary'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plan_sku' => ['required', 'in:pro_monthly,pro_yearly,extra_galpon'],
            'method' => ['required', 'in:zelle,pagomovil,usdt_binance'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'proof' => ['nullable', 'file', 'image', 'max:5120'],
        ]);

        $planConfig = $this->subscriptionService->getPlanSkuConfig($data['plan_sku']);
        if (! $planConfig) {
            return back()->withErrors(['plan_sku' => 'Plan no válido.']);
        }

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('payment_proofs', 'public');
        }

        $order = PaymentOrder::query()->create([
            'tenant_id' => tenant('id'),
            'plan_sku' => $data['plan_sku'],
            'amount' => $planConfig['amount'],
            'currency' => $planConfig['currency'],
            'method' => $data['method'],
            'gateway' => 'manual',
            'reference' => $data['reference'] ?? null,
            'status' => 'pendiente',
            'proof_path' => $proofPath,
            'notes' => $data['notes'] ?? null,
        ]);

        $emails = User::query()->where('is_superadmin', true)->whereNotNull('email')->pluck('email')->filter()->values();
        if ($emails->isNotEmpty()) {
            $body = "Nueva orden de pago #{$order->id} — {$planConfig['name']} — {$order->amount} {$order->currency} ({$order->method}). Tenant: ".tenant('id');
            Mail::raw($body, function ($message) use ($emails) {
                $message->to($emails->all())->subject('Pago pendiente de verificación — Galpon');
            });
        }

        AuditService::log('payment.submitted', "Comprobante de pago enviado. Plan: {$data['plan_sku']}. Tenant ".tenant('id').". Monto: {$planConfig['amount']} {$planConfig['currency']}.", [
            'order_id' => $order->id,
            'plan_sku' => $data['plan_sku'],
            'method'   => $data['method'],
            'amount'   => $planConfig['amount'],
        ]);

        return redirect()->route('tenant.payments.create', ['plan' => $data['plan_sku']])
            ->with('ok', 'Comprobante enviado. Te avisaremos al verificar el pago (normalmente en menos de 24 h).');
    }
}

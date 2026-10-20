<?php

namespace App\Http\Controllers;

use App\Models\BillingWebhookEvent;
use App\Services\PayPalService;
use App\Services\SubscriptionActivationService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayPalWebhookController extends Controller
{
    public function __construct(
        protected PayPalService $paypal,
        protected SubscriptionActivationService $activationService,
        protected SubscriptionService $subscriptionService
    ) {}

    public function handle(Request $request)
    {
        $body = $request->getContent();
        $payload = json_decode($body, true) ?? [];
        $eventId = $payload['id'] ?? null;
        $eventType = $payload['event_type'] ?? 'unknown';

        if (! $eventId) {
            return response()->json(['error' => 'Invalid event'], 400);
        }

        if (BillingWebhookEvent::query()->where('event_id', $eventId)->exists()) {
            return response()->json(['ok' => true, 'duplicate' => true]);
        }

        $headers = collect($request->headers->all())
            ->map(fn ($v) => is_array($v) ? ($v[0] ?? '') : $v)
            ->toArray();

        if (! $this->paypal->verifyWebhook($headers, $body)) {
            Log::warning('PayPal webhook verification failed', ['event_id' => $eventId]);

            return response()->json(['error' => 'Verification failed'], 400);
        }

        $event = BillingWebhookEvent::query()->create([
            'gateway' => 'paypal',
            'event_id' => $eventId,
            'event_type' => $eventType,
            'payload' => $payload,
        ]);

        try {
            $this->processEvent($eventType, $payload);
            $event->update(['processed_at' => now()]);
        } catch (\Throwable $e) {
            Log::error('PayPal webhook processing error', [
                'event_id' => $eventId,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json(['ok' => true]);
    }

    protected function processEvent(string $eventType, array $payload): void
    {
        match ($eventType) {
            'BILLING.SUBSCRIPTION.ACTIVATED',
            'BILLING.SUBSCRIPTION.RE-ACTIVATED' => $this->handleSubscriptionActivated($payload),
            'BILLING.SUBSCRIPTION.CANCELLED',
            'BILLING.SUBSCRIPTION.SUSPENDED',
            'BILLING.SUBSCRIPTION.EXPIRED' => $this->handleSubscriptionCancelled($payload),
            'PAYMENT.SALE.COMPLETED' => $this->handlePaymentCompleted($payload),
            default => null,
        };
    }

    protected function handleSubscriptionActivated(array $payload): void
    {
        $resource = $payload['resource'] ?? [];
        $subscriptionId = $resource['id'] ?? null;
        $tenantId = $resource['custom_id'] ?? null;

        if (! $subscriptionId || ! $tenantId) {
            return;
        }

        $planSku = SubscriptionService::SKU_PRO_MONTHLY;
        $config = $this->subscriptionService->getPlanSkuConfig($planSku);

        $this->activationService->recordPaymentOrder(
            $tenantId,
            $planSku,
            $config['amount'],
            $config['currency'],
            'paypal',
            'verificado',
            ['gateway_subscription_id' => $subscriptionId, 'method' => 'paypal']
        );

        $this->activationService->activatePro($tenantId, $planSku, now(), [
            'gateway' => 'paypal',
            'paypal_subscription_id' => $subscriptionId,
        ]);
    }

    protected function handleSubscriptionCancelled(array $payload): void
    {
        $resource = $payload['resource'] ?? [];
        $tenantId = $resource['custom_id'] ?? null;

        if ($tenantId) {
            $this->activationService->cancelSubscription($tenantId, 'PayPal webhook: cancelled');
        }
    }

    protected function handlePaymentCompleted(array $payload): void
    {
        $resource = $payload['resource'] ?? [];
        $custom = $resource['custom'] ?? $resource['custom_id'] ?? null;

        if (! $custom) {
            return;
        }

        Log::info('PayPal payment sale completed', ['custom' => $custom]);
    }
}

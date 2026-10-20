<?php

namespace App\Services;

use App\Models\PaymentOrder;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\PaymentReceivedAdminNotification;
use App\Notifications\SubscriptionUpgradedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriptionActivationService
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    public function activatePro(
        string $tenantId,
        string $planSku,
        ?Carbon $periodStart = null,
        array $meta = []
    ): Subscription {
        $config = $this->subscriptionService->getPlanSkuConfig($planSku);

        if (! $config) {
            throw new \InvalidArgumentException("Plan SKU inválido: {$planSku}");
        }

        return DB::transaction(function () use ($tenantId, $planSku, $config, $periodStart, $meta) {
            $periodStart ??= now();
            $months = (int) ($config['months'] ?? 1);
            $sub = Subscription::withoutGlobalScopes()
                ->where('tenant_id', $tenantId)
                ->orderByDesc('id')
                ->first();

            $newEnd = ($sub && $sub->ends_at && $sub->ends_at->isFuture())
                ? $sub->ends_at->copy()->addMonths($months)
                : $periodStart->copy()->addMonths($months);

            $data = [
                'plan' => 'pro',
                'plan_sku' => $planSku,
                'billing_cycle' => $config['billing_cycle'],
                'status' => 'active',
                'ends_at' => $newEnd,
                'trial_ends_at' => null,
                'cancelled_at' => null,
                'paypal_subscription_id' => $meta['paypal_subscription_id'] ?? $sub?->paypal_subscription_id,
                'paypal_payer_id' => $meta['paypal_payer_id'] ?? $sub?->paypal_payer_id,
            ];

            if ($sub) {
                $sub->update($data);
            } else {
                $sub = Subscription::withoutGlobalScopes()->create(array_merge($data, [
                    'tenant_id' => $tenantId,
                ]));
            }

            $this->notifyTenantUsers($tenantId);
            $this->notifyAdmins($tenantId, $planSku, $config['amount'] ?? 0, $meta);

            AuditService::log('subscription.activated', "Plan Pro activado ({$planSku}) para tenant {$tenantId}. Vence: {$newEnd->format('Y-m-d')}.", [
                'tenant_id' => $tenantId,
                'plan_sku' => $planSku,
                'ends_at' => $newEnd->toIso8601String(),
                'gateway' => $meta['gateway'] ?? 'manual',
            ]);

            Log::info('Subscription activated', [
                'tenant_id' => $tenantId,
                'plan_sku' => $planSku,
                'ends_at' => $newEnd->toIso8601String(),
            ]);

            return $sub->fresh();
        });
    }

    public function startTrial(string $tenantId): Subscription
    {
        $trialDays = (int) config('paypal.trial_days', 14);

        return DB::transaction(function () use ($tenantId, $trialDays) {
            $sub = Subscription::withoutGlobalScopes()
                ->where('tenant_id', $tenantId)
                ->orderByDesc('id')
                ->first();

            if ($sub && ($sub->plan === 'pro' && ! $sub->trial_ends_at)) {
                throw new \RuntimeException('Ya tienes Pro activo.');
            }

            if ($sub && ($sub->plan_sku === SubscriptionService::SKU_TRIAL || $sub->trial_ends_at)) {
                throw new \RuntimeException('Ya usaste el periodo de prueba gratuito.');
            }

            $trialEnd = now()->addDays($trialDays);
            $data = [
                'plan' => 'pro',
                'plan_sku' => SubscriptionService::SKU_TRIAL,
                'billing_cycle' => 'trial',
                'status' => 'active',
                'trial_ends_at' => $trialEnd,
                'ends_at' => $trialEnd,
            ];

            if ($sub) {
                $sub->update($data);
            } else {
                $sub = Subscription::withoutGlobalScopes()->create(array_merge($data, [
                    'tenant_id' => $tenantId,
                ]));
            }

            AuditService::log('subscription.trial_started', "Trial Pro de {$trialDays} días iniciado para tenant {$tenantId}.", [
                'tenant_id' => $tenantId,
                'trial_ends_at' => $trialEnd->toIso8601String(),
            ]);

            return $sub->fresh();
        });
    }

    public function cancelSubscription(string $tenantId, ?string $reason = null): void
    {
        $sub = Subscription::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->latest('id')
            ->first();

        if (! $sub) {
            return;
        }

        $sub->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        AuditService::log('subscription.cancelled', "Suscripción cancelada para tenant {$tenantId}. {$reason}", [
            'tenant_id' => $tenantId,
        ]);
    }

    public function recordPaymentOrder(
        string $tenantId,
        string $planSku,
        float $amount,
        string $currency,
        string $gateway,
        string $status,
        array $extra = []
    ): PaymentOrder {
        $config = $this->subscriptionService->getPlanSkuConfig($planSku);

        return PaymentOrder::query()->create([
            'tenant_id' => $tenantId,
            'plan_sku' => $planSku,
            'amount' => $amount,
            'currency' => $currency,
            'method' => $extra['method'] ?? $gateway,
            'gateway' => $gateway,
            'gateway_order_id' => $extra['gateway_order_id'] ?? null,
            'gateway_subscription_id' => $extra['gateway_subscription_id'] ?? null,
            'reference' => $extra['reference'] ?? null,
            'status' => $status,
            'proof_path' => $extra['proof_path'] ?? null,
            'notes' => $extra['notes'] ?? null,
            'period_starts_at' => $extra['period_starts_at'] ?? now(),
            'period_ends_at' => $extra['period_ends_at'] ?? now()->addMonths($config['months'] ?? 1),
        ]);
    }

    protected function notifyTenantUsers(string $tenantId): void
    {
        User::query()
            ->where('tenant_id', $tenantId)
            ->where('is_superadmin', false)
            ->get()
            ->each(fn (User $u) => $u->notify(new SubscriptionUpgradedNotification));
    }

    protected function notifyAdmins(string $tenantId, string $planSku, float $amount, array $meta): void
    {
        $admins = User::query()->where('is_superadmin', true)->whereNotNull('email')->get();

        foreach ($admins as $admin) {
            $admin->notify(new PaymentReceivedAdminNotification($tenantId, $planSku, $amount, $meta));
        }

        $emails = $admins->pluck('email')->filter()->values();
        if ($emails->isNotEmpty()) {
            $body = "Pago confirmado — Tenant: {$tenantId}, Plan: {$planSku}, Monto: \${$amount} USD.";
            Mail::raw($body, function ($message) use ($emails) {
                $message->to($emails->all())->subject('Pago confirmado — Galpon');
            });
        }
    }
}

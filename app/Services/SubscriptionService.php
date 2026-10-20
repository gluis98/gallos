<?php

namespace App\Services;

use App\Models\Gallo;
use App\Models\Gallina;
use App\Models\Subscription;
use App\Services\UserGalponService;

class SubscriptionService
{
    public const SKU_PRO_MONTHLY = 'pro_monthly';
    public const SKU_PRO_YEARLY = 'pro_yearly';
    public const SKU_EXTRA_GALPON = 'extra_galpon';
    public const SKU_TRIAL = 'trial';

    public const FEATURE_UNLIMITED_BIRDS = 'unlimited_birds';
    public const FEATURE_PEDIGREE_FULL = 'pedigree_full';
    public const FEATURE_REPORTS_ADVANCED = 'reports_advanced';
    public const FEATURE_MARKETPLACE_PUBLISH = 'marketplace_publish';
    public const FEATURE_COMPRAS = 'compras_module';
    public const FEATURE_BCV_RATE = 'bcv_rate';

    protected static array $proFeatures = [
        self::FEATURE_UNLIMITED_BIRDS,
        self::FEATURE_PEDIGREE_FULL,
        self::FEATURE_REPORTS_ADVANCED,
        self::FEATURE_MARKETPLACE_PUBLISH,
        self::FEATURE_COMPRAS,
        self::FEATURE_BCV_RATE,
    ];

    public function getActiveSubscription(?string $tenantId = null): ?Subscription
    {
        if ($tenantId) {
            return Subscription::withoutGlobalScopes()
                ->where('tenant_id', $tenantId)
                ->where('status', 'active')
                ->where(function ($q) {
                    $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
                })
                ->latest('id')
                ->first();
        }

        return Subscription::query()
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->latest('id')
            ->first();
    }

    public function effectivePlan(?string $tenantId = null): string
    {
        $sub = $this->getActiveSubscription($tenantId);

        if (! $sub) {
            return 'free';
        }

        if ($this->isOnTrial($sub)) {
            return 'pro';
        }

        if ($sub->plan === 'pro') {
            return 'pro';
        }

        return 'free';
    }

    public function isPro(?string $tenantId = null): bool
    {
        return $this->effectivePlan($tenantId) === 'pro';
    }

    public function isOnTrial(?Subscription $sub = null): bool
    {
        $sub ??= $this->getActiveSubscription();

        return $sub
            && $sub->trial_ends_at
            && $sub->trial_ends_at->isFuture();
    }

    public function canUseFeature(string $feature, ?string $tenantId = null): bool
    {
        if ($this->isPro($tenantId)) {
            return true;
        }

        return ! in_array($feature, self::$proFeatures, true);
    }

    public function getLimits(?string $tenantId = null): array
    {
        $settings = SettingsService::get()['plans'] ?? [];

        return [
            'gallos' => (int) ($settings['free_gallos_limit'] ?? 20),
            'gallinas' => (int) ($settings['free_gallinas_limit'] ?? 20),
        ];
    }

    public function getUsage(?string $tenantId = null): array
    {
        $tenantId ??= tenant('id');

        if (! $tenantId && auth()->check()) {
            $tenantId = app(UserGalponService::class)->resolveActiveTenantId(auth()->user());
        }

        if (! $tenantId) {
            return ['gallos' => 0, 'gallinas' => 0];
        }

        return [
            'gallos' => Gallo::withoutGlobalScopes()->where('tenant_id', $tenantId)->count(),
            'gallinas' => Gallina::withoutGlobalScopes()->where('tenant_id', $tenantId)->count(),
        ];
    }

    public function isNearLimit(string $type, ?string $tenantId = null): bool
    {
        if ($this->isPro($tenantId)) {
            return false;
        }

        $limits = $this->getLimits($tenantId);
        $usage = $this->getUsage($tenantId);
        $limit = $limits[$type] ?? 20;
        $count = $usage[$type] ?? 0;

        return $limit > 0 && $count >= ($limit * 0.8);
    }

    public function isAtLimit(string $type, ?string $tenantId = null): bool
    {
        if ($this->isPro($tenantId)) {
            return false;
        }

        $limits = $this->getLimits($tenantId);
        $usage = $this->getUsage($tenantId);

        return ($usage[$type] ?? 0) >= ($limits[$type] ?? 20);
    }

    public function getPlanSkuConfig(string $sku): ?array
    {
        $settings = SettingsService::get();
        $plans = $settings['plans'] ?? [];
        $currency = $plans['pro_currency'] ?? 'USD';

        return match ($sku) {
            self::SKU_PRO_MONTHLY => [
                'sku' => self::SKU_PRO_MONTHLY,
                'name' => 'Plan Pro Mensual',
                'amount' => (float) ($plans['pro_monthly_price'] ?? 15),
                'currency' => $currency,
                'billing_cycle' => 'monthly',
                'months' => 1,
            ],
            self::SKU_PRO_YEARLY => [
                'sku' => self::SKU_PRO_YEARLY,
                'name' => 'Plan Pro Anual',
                'amount' => (float) ($plans['pro_yearly_price'] ?? 150),
                'currency' => $currency,
                'billing_cycle' => 'yearly',
                'months' => 12,
            ],
            self::SKU_EXTRA_GALPON => [
                'sku' => self::SKU_EXTRA_GALPON,
                'name' => 'Galpón Extra',
                'amount' => (float) ($plans['extra_galpon_price'] ?? 5),
                'currency' => $currency,
                'billing_cycle' => 'monthly',
                'months' => 1,
            ],
            default => null,
        };
    }

    /** @return array<int, array<string, mixed>> */
    public function availablePlans(): array
    {
        return array_values(array_filter([
            $this->getPlanSkuConfig(self::SKU_PRO_MONTHLY),
            $this->getPlanSkuConfig(self::SKU_PRO_YEARLY),
        ]));
    }

    public function subscriptionSummary(?string $tenantId = null): array
    {
        $sub = $this->getActiveSubscription($tenantId);
        $plan = $this->effectivePlan($tenantId);
        $limits = $this->getLimits($tenantId);
        $usage = $this->getUsage($tenantId);

        return [
            'plan' => $plan,
            'is_pro' => $plan === 'pro',
            'is_trial' => $sub ? $this->isOnTrial($sub) : false,
            'plan_sku' => $sub?->plan_sku,
            'billing_cycle' => $sub?->billing_cycle,
            'ends_at' => $sub?->ends_at?->toIso8601String(),
            'trial_ends_at' => $sub?->trial_ends_at?->toIso8601String(),
            'limits' => $limits,
            'usage' => $usage,
            'gallos_near_limit' => $this->isNearLimit('gallos', $tenantId),
            'gallinas_near_limit' => $this->isNearLimit('gallinas', $tenantId),
            'gallos_at_limit' => $this->isAtLimit('gallos', $tenantId),
            'gallinas_at_limit' => $this->isAtLimit('gallinas', $tenantId),
        ];
    }
}

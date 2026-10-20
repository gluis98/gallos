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
    public const FEATURE_REPORTS_ADVANCED = 'reports';
    public const FEATURE_MARKETPLACE_PUBLISH = 'marketplace';
    public const FEATURE_COMPRAS = 'compras';
    public const FEATURE_VACUNACIONES = 'vacunaciones';
    public const FEATURE_INVENTARIO = 'inventario';
    public const FEATURE_VENTAS = 'ventas';
    public const FEATURE_BCV_RATE = 'bcv_rate';

    /** Mapeo legacy → clave de módulo en settings */
    protected static array $featureToModule = [
        'compras_module' => 'compras',
        'marketplace_publish' => 'marketplace',
        'reports_advanced' => 'reports',
        'pedigree_full' => 'pedigree_full',
        'unlimited_birds' => 'unlimited_birds',
        'vacunaciones' => 'vacunaciones',
        'inventario' => 'inventario',
        'ventas' => 'ventas',
        'compras' => 'compras',
        'marketplace' => 'marketplace',
        'reports' => 'reports',
        'bcv_rate' => 'bcv_rate',
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

        return ! $this->moduleRequiresPro($feature);
    }

    public function moduleRequiresPro(string $featureOrModule): bool
    {
        $key = self::$featureToModule[$featureOrModule] ?? $featureOrModule;
        $modules = SettingsService::get()['modules'] ?? [];

        if (isset($modules[$key])) {
            return (bool) ($modules[$key]['pro'] ?? false);
        }

        // Fallback si aún no está en settings
        return in_array($key, [
            'compras', 'marketplace', 'reports', 'pedigree_full', 'unlimited_birds',
        ], true);
    }

    /** @return array<string, array{label:string,pro:bool,nav:bool}> */
    public function getModules(): array
    {
        return SettingsService::get()['modules'] ?? [];
    }

    public function isModuleProLocked(string $module, ?string $tenantId = null): bool
    {
        return $this->moduleRequiresPro($module) && ! $this->isPro($tenantId);
    }

    /** Respuesta 403 JSON si el módulo está bloqueado para el plan actual. */
    public function proGateJson(string $featureOrModule, ?string $customMessage = null): ?\Illuminate\Http\JsonResponse
    {
        if (! $this->isModuleProLocked($featureOrModule)) {
            return null;
        }

        $key = self::$featureToModule[$featureOrModule] ?? $featureOrModule;
        $label = (SettingsService::get()['modules'][$key]['label'] ?? null) ?: 'Esta función';
        $message = $customMessage ?? "{$label} requiere Plan Pro.";

        return response()->json([
            'message' => $message,
            'msj' => $message,
            'upgrade_url' => route('billing.checkout', ['plan' => 'pro_monthly']),
        ], 403);
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

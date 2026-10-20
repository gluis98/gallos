<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireProFeature
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();
        if (! $user || $user->is_superadmin) {
            return $next($request);
        }

        if ($this->subscriptionService->canUseFeature($feature)) {
            return $next($request);
        }

        $message = match ($feature) {
            SubscriptionService::FEATURE_PEDIGREE_FULL,
            'pedigree_full' => 'El pedigree completo requiere Plan Pro.',
            SubscriptionService::FEATURE_REPORTS_ADVANCED,
            'reports_advanced', 'reports' => 'Los reportes avanzados requieren Plan Pro.',
            SubscriptionService::FEATURE_COMPRAS,
            'compras_module' => 'El módulo de compras requiere Plan Pro.',
            SubscriptionService::FEATURE_MARKETPLACE_PUBLISH,
            'marketplace_publish' => 'Publicar en el Marketplace requiere Plan Pro.',
            SubscriptionService::FEATURE_VACUNACIONES => 'El módulo de vacunación requiere Plan Pro.',
            SubscriptionService::FEATURE_INVENTARIO => 'El módulo de inventario requiere Plan Pro.',
            SubscriptionService::FEATURE_VENTAS => 'El módulo de ventas requiere Plan Pro.',
            default => 'Esta función requiere Plan Pro.',
        };

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $message,
                'msj' => $message,
                'upgrade_url' => route('billing.checkout', ['plan' => 'pro_monthly']),
            ], 403);
        }

        return redirect()->route('billing.checkout', ['plan' => 'pro_monthly'])
            ->withErrors(['upgrade' => $message]);
    }
}

<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionLimit
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || $user->is_superadmin || ! tenancy()->initialized) {
            return $next($request);
        }

        if (! $request->isMethod('POST')) {
            return $next($request);
        }

        $tenantId = tenant('id');
        $limits = [
            'gallos.store' => 'gallos',
            'gallinas.store' => 'gallinas',
        ];

        foreach ($limits as $routeName => $type) {
            if (! $request->routeIs($routeName)) {
                continue;
            }

            if ($this->subscriptionService->isAtLimit($type, $tenantId)) {
                $max = $this->subscriptionService->getLimits($tenantId)[$type] ?? 20;
                $message = "Límite del plan gratuito alcanzado: máximo {$max} {$type}. Actualiza a Pro para registrar más.";

                return response()->json([
                    'message' => $message,
                    'msj' => $message,
                    'upgrade_url' => route('billing.checkout', ['plan' => 'pro_monthly']),
                    'at_limit' => true,
                ], 403);
            }
        }

        return $next($request);
    }
}

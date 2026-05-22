<?php

namespace App\Http\Middleware;

use App\Models\Gallo;
use App\Models\Gallina;
use App\Models\Subscription;
use App\Services\SettingsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionLimit
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || $user->is_superadmin || ! tenancy()->initialized) {
            return $next($request);
        }

        $tenantId = tenant('id');
        $sub = Subscription::query()
            ->where('status', 'active')
            ->latest('id')
            ->first();

        $plan = $sub?->plan ?? 'free';

        if ($plan !== 'free' || ! $request->isMethod('POST')) {
            return $next($request);
        }

        $settings = SettingsService::get();
        $limits = [
            'gallos.store' => [
                Gallo::class,
                (int) ($settings['plans']['free_gallos_limit'] ?? 20),
                'gallos',
            ],
            'gallinas.store' => [
                Gallina::class,
                (int) ($settings['plans']['free_gallinas_limit'] ?? 20),
                'gallinas',
            ],
        ];

        foreach ($limits as $routeName => [$modelClass, $max, $label]) {
            if (! $request->routeIs($routeName)) {
                continue;
            }

            $count = $modelClass::withoutGlobalScopes()
                ->where('tenant_id', $tenantId)
                ->count();

            if ($count >= $max) {
                $message = "Límite del plan gratuito alcanzado: máximo {$max} {$label}. Actualiza a Pro para registrar más.";

                return response()->json(['message' => $message, 'msj' => $message], 403);
            }
        }

        return $next($request);
    }
}

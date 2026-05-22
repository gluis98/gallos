<?php

namespace App\Http\Middleware;

use App\Services\UserGalponService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancyFromAuthenticatedUser
{
    public function __construct(
        protected UserGalponService $galponService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && ! $user->is_superadmin) {
            $tenant = $this->galponService->resolveActiveTenant($user);
            if ($tenant && $tenant->status === 'active' && ! tenancy()->initialized) {
                tenancy()->initialize($tenant);
            }
        }

        return $next($request);
    }
}

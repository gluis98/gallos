<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use App\Services\UserGalponService;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function __construct(
        protected CatalogService $catalogService,
        protected UserGalponService $galponService
    ) {}

    public function info(Request $request)
    {
        $user = $request->user();
        if (! $user || $user->is_superadmin || ! $this->galponService->resolveActiveTenantId($user)) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json(['data' => $this->catalogService->infoForUser($user)]);
    }

    public function regenerate(Request $request)
    {
        $user = $request->user();
        if (! $user || $user->is_superadmin || ! $this->galponService->resolveActiveTenantId($user)) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tenant = $this->galponService->resolveActiveTenant($user);
        if (! $tenant) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        $tenant = $this->catalogService->regenerateToken($tenant);

        return response()->json([
            'msj' => 'Enlace del catálogo renovado. El anterior dejará de funcionar.',
            'data' => $this->catalogService->infoForUser($user),
        ]);
    }

    public function toggle(Request $request)
    {
        $user = $request->user();
        if (! $user || $user->is_superadmin || ! $this->galponService->resolveActiveTenantId($user)) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tenant = $this->galponService->resolveActiveTenant($user);
        if (! $tenant) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        $this->catalogService->ensureToken($tenant);
        $tenant->catalog_enabled = ! $tenant->catalog_enabled;
        $tenant->save();

        return response()->json([
            'msj' => $tenant->catalog_enabled ? 'Catálogo público activado.' : 'Catálogo público desactivado.',
            'data' => $this->catalogService->infoForUser($user),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\UserGalponService;
use Illuminate\Http\Request;

class GalponSwitchController extends Controller
{
    public function __construct(
        protected UserGalponService $galponService
    ) {}

    public function switch(Request $request)
    {
        $user = $request->user();
        if (! $user || $user->is_superadmin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $data = $request->validate([
            'tenant_id' => ['required', 'string'],
        ]);

        $this->galponService->switchGalpon($user, $data['tenant_id']);

        $tenant = $this->galponService->resolveActiveTenant($user);

        if ($request->expectsJson()) {
            return response()->json([
                'msj' => 'Galpón cambiado correctamente.',
                'tenant_id' => $tenant?->id,
                'tenant_name' => $tenant?->name,
            ]);
        }

        return redirect()->back()->with('ok', 'Galpón activo: '.($tenant?->name ?? ''));
    }
}

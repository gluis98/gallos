<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class PublicacionController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    public function index()
    {
        $items = Publicacion::query()
            ->with('ave')
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['data' => $items]);
    }

    public function store(Request $request)
    {
        if (! $this->subscriptionService->canUseFeature(SubscriptionService::FEATURE_MARKETPLACE_PUBLISH)) {
            return response()->json([
                'message' => 'Publicar en el Marketplace requiere Plan Pro.',
                'msj' => 'Publicar en el Marketplace requiere Plan Pro.',
                'upgrade_url' => route('billing.checkout', ['plan' => 'pro_monthly']),
            ], 403);
        }

        $data = $request->validate([
            'ave_type' => ['required', 'in:gallo,gallina'],
            'ave_id' => ['required', 'integer'],
            'precio' => ['required', 'numeric', 'min:0'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
        ]);

        $aveType = $data['ave_type'] === 'gallo'
            ? \App\Models\Gallo::class
            : \App\Models\Gallina::class;

        $exists = Publicacion::query()
            ->where('ave_type', $aveType)
            ->where('ave_id', $data['ave_id'])
            ->where('activo', true)
            ->exists();

        if ($exists) {
            return response()->json(['msj' => 'Esta ave ya tiene una publicación activa.'], 422);
        }

        $pub = Publicacion::query()->create([
            'ave_type' => $aveType,
            'ave_id' => $data['ave_id'],
            'precio' => $data['precio'],
            'descripcion' => $data['descripcion'] ?? null,
            'activo' => true,
            'destacado' => false,
        ]);

        return response()->json([
            'msj' => 'Publicación creada en el Marketplace.',
            'data' => $pub->load('ave'),
        ], 201);
    }

    public function destroy(int $id)
    {
        $pub = Publicacion::query()->findOrFail($id);
        $pub->update(['activo' => false]);

        return response()->json(['msj' => 'Publicación desactivada.']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Gallo;
use App\Models\Gallina;
use App\Models\Vacunacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VacunacionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user     = $request->user();
        $tenantId = $user->tenant_id;

        $query = Vacunacion::forTenant($tenantId)
            ->orderByDesc('fecha_aplicacion');

        if ($request->filled('ave_type')) {
            $query->where('ave_type', $request->ave_type);
        }
        if ($request->filled('ave_id')) {
            $query->where('ave_id', $request->ave_id);
        }
        if ($request->filled('vacuna')) {
            $query->where('vacuna', 'like', '%' . $request->vacuna . '%');
        }
        if ($request->boolean('proximas')) {
            $query->proximas(30);
        }

        $vacunaciones = $query->paginate(20);

        $vacunaciones->getCollection()->transform(function ($v) {
            $v->ave_nombre = $this->resolveAveName($v->ave_id, $v->ave_type);
            return $v;
        });

        return response()->json($vacunaciones);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'ave_id'             => 'required|integer',
            'ave_type'           => 'required|in:gallo,gallina',
            'vacuna'             => 'required|string|max:120',
            'descripcion'        => 'nullable|string|max:255',
            'dosis'              => 'nullable|string|max:60',
            'via_administracion' => 'nullable|string|max:80',
            'fecha_aplicacion'   => 'required|date',
            'proxima_fecha'      => 'nullable|date|after:fecha_aplicacion',
            'lote'               => 'nullable|string|max:80',
            'veterinario'        => 'nullable|string|max:120',
            'notas'              => 'nullable|string|max:1000',
        ]);

        $user = $request->user();
        $this->authorizeAve($data['ave_id'], $data['ave_type'], $user->tenant_id);

        $data['tenant_id'] = $user->tenant_id;
        $data['user_id']   = $user->id;

        $vacunacion = Vacunacion::create($data);

        return response()->json($vacunacion, 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $vac = Vacunacion::forTenant($request->user()->tenant_id)->findOrFail($id);
        $vac->ave_nombre = $this->resolveAveName($vac->ave_id, $vac->ave_type);
        return response()->json($vac);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $vac = Vacunacion::forTenant($request->user()->tenant_id)->findOrFail($id);

        $data = $request->validate([
            'vacuna'             => 'sometimes|required|string|max:120',
            'descripcion'        => 'nullable|string|max:255',
            'dosis'              => 'nullable|string|max:60',
            'via_administracion' => 'nullable|string|max:80',
            'fecha_aplicacion'   => 'sometimes|required|date',
            'proxima_fecha'      => 'nullable|date',
            'lote'               => 'nullable|string|max:80',
            'veterinario'        => 'nullable|string|max:120',
            'notas'              => 'nullable|string|max:1000',
        ]);

        $vac->update($data);

        return response()->json($vac);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $vac = Vacunacion::forTenant($request->user()->tenant_id)->findOrFail($id);
        $vac->delete();
        return response()->json(['ok' => true]);
    }

    public function historial(Request $request, int $aveId, string $aveType): JsonResponse
    {
        abort_unless(in_array($aveType, ['gallo', 'gallina']), 422);

        $historial = Vacunacion::forTenant($request->user()->tenant_id)
            ->where('ave_id', $aveId)
            ->where('ave_type', $aveType)
            ->orderByDesc('fecha_aplicacion')
            ->get();

        return response()->json($historial);
    }

    public function estadisticas(Request $request): JsonResponse
    {
        $tenantId = $request->user()->tenant_id;

        $totalGallos   = Vacunacion::forTenant($tenantId)->where('ave_type', 'gallo')->count();
        $totalGallinas = Vacunacion::forTenant($tenantId)->where('ave_type', 'gallina')->count();
        $proximas      = Vacunacion::forTenant($tenantId)->proximas(30)->count();

        $porVacuna = Vacunacion::forTenant($tenantId)
            ->selectRaw('vacuna, COUNT(*) as total')
            ->groupBy('vacuna')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return response()->json([
            'total_gallos'   => $totalGallos,
            'total_gallinas' => $totalGallinas,
            'proximas_30d'   => $proximas,
            'por_vacuna'     => $porVacuna,
        ]);
    }

    private function resolveAveName(int $aveId, string $aveType): string
    {
        $ave = $aveType === 'gallo'
            ? Gallo::withoutGlobalScopes()->find($aveId)
            : Gallina::withoutGlobalScopes()->find($aveId);

        if (! $ave) {
            return "#{$aveId}";
        }

        return $ave->nombre ?? $ave->codigo ?? "#{$aveId}";
    }

    private function authorizeAve(int $aveId, string $aveType, string $tenantId): void
    {
        $exists = $aveType === 'gallo'
            ? Gallo::withoutGlobalScopes()->where('id', $aveId)->where('tenant_id', $tenantId)->exists()
            : Gallina::withoutGlobalScopes()->where('id', $aveId)->where('tenant_id', $tenantId)->exists();

        abort_unless($exists, 403, 'Ave no pertenece a este tenant.');
    }
}

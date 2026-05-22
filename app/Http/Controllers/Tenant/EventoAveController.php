<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\EventoAve;
use App\Models\Gallo;
use App\Models\Gallina;
use Illuminate\Http\Request;

class EventoAveController extends Controller
{
    public function index(Request $request)
    {
        $tipo = $request->get('ave_type', Gallo::class);
        $id = (int) $request->get('ave_id');
        $q = EventoAve::query()->orderByDesc('fecha');
        if ($id) {
            $q->where('ave_type', $tipo)->where('ave_id', $id);
        }

        return response()->json(['data' => $q->limit(500)->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ave_type' => ['required', 'string', 'in:'.Gallo::class.','.Gallina::class],
            'ave_id' => ['required', 'integer'],
            'tipo_evento' => ['required', 'string', 'max:64'],
            'fecha' => ['required', 'date'],
            'notas' => ['nullable', 'string'],
            'ids' => ['nullable', 'array'],
            'ids.*' => ['integer'],
        ]);

        if (! empty($data['ids'])) {
            foreach ($data['ids'] as $aid) {
                EventoAve::query()->create([
                    'ave_type' => $data['ave_type'],
                    'ave_id' => $aid,
                    'tipo_evento' => $data['tipo_evento'],
                    'fecha' => $data['fecha'],
                    'notas' => $data['notas'] ?? null,
                ]);
            }
        } else {
            EventoAve::query()->create([
                'ave_type' => $data['ave_type'],
                'ave_id' => $data['ave_id'],
                'tipo_evento' => $data['tipo_evento'],
                'fecha' => $data['fecha'],
                'notas' => $data['notas'] ?? null,
            ]);
        }

        return response()->json(['msj' => 'Evento registrado']);
    }

    public function destroy(int $id)
    {
        EventoAve::query()->findOrFail($id)->delete();

        return response()->json(['msj' => 'Eliminado']);
    }
}

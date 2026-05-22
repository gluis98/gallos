<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVentaRequest;
use App\Http\Requests\UpdateVentaRequest;
use App\Models\Publicacion;
use App\Models\Venta;
use App\Services\VentaService;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function __construct(
        protected VentaService $ventaService
    ) {}

    public function index()
    {
        $v = Venta::query()->with('gallo', 'gallina', 'cliente', 'inventario')->latest('id')->get();

        return response()->json([
            'data' => $v,
        ], 200);
    }

    public function store(StoreVentaRequest $request)
    {
        $data = $request->validated();
        $publicacion = null;
        if (! empty($data['publicacion_id'])) {
            $publicacion = Publicacion::query()->find($data['publicacion_id']);
        }
        unset($data['publicacion_id']);
        $this->ventaService->registrarVenta($data, $publicacion);

        return response()->json([
            'msj' => 'Registro registrado exitosamente',
        ], 200);
    }

    public function show(string $id)
    {
        $v = Venta::query()->with('cliente', 'gallo')->find($id);

        return response()->json([
            'data' => $v,
        ], 200);
    }

    public function update(UpdateVentaRequest $request, $id)
    {
        $v = Venta::query()->findOrFail($id);
        $v->fill($request->validated())->save();

        return response()->json([
            'msj' => 'Registro actualizado exitosamente',
        ], 200);
    }

    public function destroy($id)
    {
        $this->ventaService->eliminarVenta($id);

        return response()->json([
            'msj' => 'Registro eliminado exitosamente',
        ], 200);
    }

    public function search(Request $request)
    {
        $dato = $request->input('dato', '');
        $g = Venta::query()
            ->with(['gallo', 'cliente'])
            ->where(function ($q) use ($dato) {
                $q->whereHas('cliente', function ($c) use ($dato) {
                    $c->where('name', 'like', '%'.$dato.'%')
                        ->orWhere('phone', 'like', '%'.$dato.'%');
                })
                    ->orWhere('created_at', 'like', '%'.$dato.'%')
                    ->orWhere('precio', 'like', '%'.$dato.'%');
            })
            ->get();

        return response()->json([
            'data' => $g,
        ], 200);
    }
}

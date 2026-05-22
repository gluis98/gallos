<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompraRequest;
use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\Inventario;
use App\Models\Proveedor;
use App\Services\CompraAvicolaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function __construct(
        protected CompraAvicolaService $compraAvicolaService
    ) {}

    public function index()
    {
        $compras = Compra::query()
            ->with(['proveedor', 'items'])
            ->latest('id')
            ->get();

        return response()->json(['data' => $compras]);
    }

    public function store(StoreCompraRequest $request)
    {
        $data = $request->validated();

        $result = DB::transaction(function () use ($data, $request) {
            $proveedorAttrs = [
                'name' => $data['proveedor_nombre'],
                'phone' => $data['proveedor_telefono'] ?? null,
            ];
            if (tenancy()->initialized) {
                $proveedorAttrs['tenant_id'] = tenant('id');
            }

            $proveedor = Proveedor::query()->firstOrCreate(
                $proveedorAttrs,
                [
                    'email' => $data['proveedor_email'] ?? null,
                ]
            );

            $compra = Compra::query()->create([
                'proveedor_id' => $proveedor->id,
                'fecha_compra' => $data['fecha_compra'],
                'total' => 0,
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            $total = 0;
            $avesRegistradas = 0;
            foreach ($data['items'] as $idx => $item) {
                $path = null;
                if ($request->hasFile("fotos.$idx")) {
                    $path = $request->file("fotos.$idx")->store('compras/items', 'public');
                }

                $cantidad    = (float) ($item['cantidad'] ?? 1);
                $inventarioId = ($item['tipo_ave'] === 'inventario') ? ($item['inventario_id'] ?? null) : null;

                $compraItem = CompraItem::query()->create([
                    'compra_id'        => $compra->id,
                    'tipo_ave'         => $item['tipo_ave'],
                    'inventario_id'    => $inventarioId,
                    'placa'            => $item['placa'] ?? null,
                    'nombre'           => $item['nombre'] ?? null,
                    'marca_nacimiento' => $item['marca_nacimiento'] ?? null,
                    'color'            => $item['color'] ?? null,
                    'costo'            => $item['costo'],
                    'cantidad'         => $cantidad,
                    'foto_path'        => $path,
                    'observaciones'    => $item['observaciones'] ?? null,
                ]);

                if ($this->compraAvicolaService->registrarDesdeCompra($compraItem, $compra->id, (int) $idx)) {
                    $avesRegistradas++;
                }

                // Si es inventario, incrementar stock
                if ($inventarioId) {
                    $inv = Inventario::find($inventarioId);
                    $inv?->ajustarStock($cantidad, 'incrementar');
                }

                $total += (float) $item['costo'] * ($item['tipo_ave'] === 'inventario' ? $cantidad : 1);
            }

            $compra->update(['total' => $total]);

            return [
                'compra' => $compra->load(['proveedor', 'items']),
                'aves_registradas' => $avesRegistradas,
            ];
        });

        $msj = 'Compra registrada correctamente';
        if ($result['aves_registradas'] > 0) {
            $msj .= sprintf(
                '. %d ave(s) añadida(s) al módulo de gallos/gallinas.',
                $result['aves_registradas']
            );
        }

        return response()->json([
            'msj' => $msj,
            'data' => $result['compra'],
        ]);
    }

    public function destroy(string $id)
    {
        $compra = Compra::query()->findOrFail($id);
        $compra->delete();

        return response()->json(['msj' => 'Compra eliminada correctamente']);
    }

    public function search(Request $request)
    {
        $term = $request->input('dato', '');

        $compras = Compra::query()
            ->with(['proveedor', 'items'])
            ->where(function ($q) use ($term) {
                $q->where('fecha_compra', 'like', '%'.$term.'%')
                    ->orWhereHas('proveedor', function ($p) use ($term) {
                        $p->where('name', 'like', '%'.$term.'%')
                            ->orWhere('phone', 'like', '%'.$term.'%');
                    });
            })
            ->latest('id')
            ->get();

        return response()->json(['data' => $compras]);
    }
}


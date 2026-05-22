<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Comision;
use App\Models\Gallina;
use App\Models\Gallo;
use App\Models\Inventario;
use App\Models\Publicacion;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

class VentaService
{
    public function registrarVenta(array $data, ?Publicacion $publicacion = null): Venta
    {
        return DB::transaction(function () use ($data, $publicacion) {
            $client = Client::query()->firstOrCreate(
                ['name' => $data['nombre_cliente'], 'phone' => $data['telefono'] ?? null],
                ['email' => null]
            );

            $tipoItem     = $data['tipo_item'] ?? 'gallo';
            $inventarioId = $tipoItem === 'inventario' ? ($data['inventario_id'] ?? null) : null;
            $galloId      = $tipoItem === 'gallo'      ? ($data['gallo_id'] ?? null)      : null;
            $gallinaId    = $tipoItem === 'gallina'    ? ($data['gallina_id'] ?? null)    : null;
            $cantidad     = (float) ($data['cantidad'] ?? 1);

            $venta = Venta::create([
                'gallo_id'      => $galloId,
                'gallina_id'    => $gallinaId,
                'inventario_id' => $inventarioId,
                'cliente_id'    => $client->id,
                'fecha'         => $data['fecha'] ?? now()->toDateString(),
                'precio'        => $data['monto'],
                'tipo_venta'    => $data['tipo_venta'] ?? 'directa',
                'tipo_item'     => $tipoItem,
                'cantidad'      => $cantidad,
                'observaciones' => $data['observaciones'] ?? null,
                'estatus'       => $data['estatus'] ?? 'Finalizada',
            ]);

            // Actualizar estado gallo / descontar inventario
            if ($galloId) {
                $gallo = Gallo::query()->findOrFail($galloId);
                $gallo->estatus = 'Vendido';
                $gallo->save();
            }

            if ($gallinaId) {
                $gallina = Gallina::query()->findOrFail($gallinaId);
                $gallina->estatus = 'Vendido';
                $gallina->save();
            }

            if ($inventarioId) {
                $inv = Inventario::find($inventarioId);
                if ($inv) {
                    $nuevoStock = max(0, (float) $inv->stock_actual - $cantidad);
                    $inv->update(['stock_actual' => $nuevoStock]);
                }
            }

            if ($publicacion) {
                $pct = (float) config('marketplace.commission_percent', 5);
                Comision::create([
                    'tenant_id' => $publicacion->tenant_id,
                    'venta_id'  => $venta->id,
                    'monto'     => round((float) $venta->precio * ($pct / 100), 2),
                    'estado'    => 'pendiente',
                ]);
            }

            return $venta->load('cliente', 'gallo', 'gallina', 'inventario');
        });
    }

    public function eliminarVenta(int|string $id): void
    {
        DB::transaction(function () use ($id) {
            $venta = Venta::query()->findOrFail($id);

            // Revertir estado gallo
            if ($venta->gallo_id) {
                $gallo = Gallo::query()->find($venta->gallo_id);
                if ($gallo) {
                    $gallo->estatus = 'Activo';
                    $gallo->save();
                }
            }

            if ($venta->gallina_id) {
                $gallina = Gallina::query()->find($venta->gallina_id);
                if ($gallina) {
                    $gallina->estatus = 'Activa';
                    $gallina->save();
                }
            }

            // Revertir stock inventario
            if ($venta->inventario_id) {
                $inv = Inventario::find($venta->inventario_id);
                $inv?->ajustarStock((float) $venta->cantidad, 'incrementar');
            }

            Comision::query()->where('venta_id', $venta->id)->delete();
            $venta->delete();
        });
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Services\DollarRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventarioController extends Controller
{
    public function index()
    {
        $items = Inventario::latest()->get()->map(fn ($i) => $this->format($i));
        return response()->json(['data' => $items]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'              => 'required|string|max:120',
            'descripcion'         => 'nullable|string',
            'categoria'           => 'nullable|string|max:80',
            'unidad_medida'       => 'nullable|string|max:40',
            'stock_inicial'       => 'nullable|numeric|min:0',
            'costo_unitario'      => 'required|numeric|min:0',
            'utilidad_porcentaje' => 'nullable|numeric|min:0|max:9999',
            'precio_venta'        => 'nullable|numeric|min:0',
            'notas'               => 'nullable|string',
            'foto'                => 'nullable|image|max:4096',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('inventario', 'public');
        }

        $precioVenta = (float) ($data['precio_venta'] ?? 0);
        if ($precioVenta <= 0 && (float) $data['costo_unitario'] > 0) {
            $utilidad    = (float) ($data['utilidad_porcentaje'] ?? 0);
            $precioVenta = (float) $data['costo_unitario'] * (1 + $utilidad / 100);
        }

        $item = Inventario::create([
            'nombre'              => $data['nombre'],
            'descripcion'         => $data['descripcion'] ?? null,
            'categoria'           => $data['categoria'] ?? null,
            'unidad_medida'       => $data['unidad_medida'] ?? 'unidad',
            'stock_actual'        => (float) ($data['stock_inicial'] ?? 0),
            'costo_unitario'      => (float) $data['costo_unitario'],
            'utilidad_porcentaje' => (float) ($data['utilidad_porcentaje'] ?? 0),
            'precio_venta'        => $precioVenta,
            'foto_path'           => $fotoPath,
            'notas'               => $data['notas'] ?? null,
        ]);

        return response()->json(['msj' => 'Ítem registrado', 'data' => $this->format($item)]);
    }

    public function update(Request $request, string $id)
    {
        $item = Inventario::findOrFail($id);
        $data = $request->validate([
            'nombre'              => 'required|string|max:120',
            'descripcion'         => 'nullable|string',
            'categoria'           => 'nullable|string|max:80',
            'unidad_medida'       => 'nullable|string|max:40',
            'costo_unitario'      => 'required|numeric|min:0',
            'utilidad_porcentaje' => 'nullable|numeric|min:0|max:9999',
            'precio_venta'        => 'nullable|numeric|min:0',
            'notas'               => 'nullable|string',
            'foto'                => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('foto')) {
            if ($item->foto_path) Storage::disk('public')->delete($item->foto_path);
            $item->foto_path = $request->file('foto')->store('inventario', 'public');
        }

        $precioVenta = (float) ($data['precio_venta'] ?? 0);
        if ($precioVenta <= 0) {
            $utilidad    = (float) ($data['utilidad_porcentaje'] ?? 0);
            $precioVenta = (float) $data['costo_unitario'] * (1 + $utilidad / 100);
        }

        $item->fill([
            'nombre'              => $data['nombre'],
            'descripcion'         => $data['descripcion'] ?? null,
            'categoria'           => $data['categoria'] ?? null,
            'unidad_medida'       => $data['unidad_medida'] ?? 'unidad',
            'costo_unitario'      => (float) $data['costo_unitario'],
            'utilidad_porcentaje' => (float) ($data['utilidad_porcentaje'] ?? 0),
            'precio_venta'        => $precioVenta,
            'notas'               => $data['notas'] ?? null,
        ])->save();

        return response()->json(['msj' => 'Ítem actualizado', 'data' => $this->format($item)]);
    }

    public function destroy(string $id)
    {
        $item = Inventario::findOrFail($id);
        if ($item->foto_path) Storage::disk('public')->delete($item->foto_path);
        $item->delete();
        return response()->json(['msj' => 'Ítem eliminado']);
    }

    public function search(Request $request)
    {
        $term  = $request->input('q', '');
        $items = Inventario::where('nombre', 'like', "%{$term}%")
            ->orWhere('categoria', 'like', "%{$term}%")
            ->latest()->get()->map(fn ($i) => $this->format($i));
        return response()->json(['data' => $items]);
    }

    private function format(Inventario $i): array
    {
        $rate = DollarRateService::getCachedRate();
        $pv   = (float) $i->precio_venta_calculado;
        $cu   = (float) $i->costo_unitario;
        return [
            'id'                  => $i->id,
            'nombre'              => $i->nombre,
            'descripcion'         => $i->descripcion,
            'categoria'           => $i->categoria,
            'unidad_medida'       => $i->unidad_medida,
            'stock_actual'        => (float) $i->stock_actual,
            'costo_unitario'      => $cu,
            'utilidad_porcentaje' => (float) $i->utilidad_porcentaje,
            'precio_venta'        => $pv,
            'costo_bs'            => $rate > 0 ? round($cu * $rate, 2) : null,
            'precio_venta_bs'     => $rate > 0 ? round($pv * $rate, 2) : null,
            'tasa'                => $rate,
            'foto_url'            => $i->foto_path ? asset('storage/' . $i->foto_path) : null,
            'notas'               => $i->notas,
            'created_at'          => $i->created_at?->toDateString(),
        ];
    }
}

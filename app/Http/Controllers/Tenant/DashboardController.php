<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\Gallo;
use App\Models\Gallina;
use App\Models\Inventario;
use App\Models\Subscription;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->user()?->is_superadmin) {
            return redirect()->route('superadmin.tenants.index');
        }

        if (! tenancy()->initialized) {
            return view('admin.dashboard.index', [
                'stats' => [
                    'gallos' => 0,
                    'gallinas' => 0,
                    'aves_total' => 0,
                    'ventas_mes' => 0,
                    'compras_mes' => 0,
                    'ingresos_mes' => 0,
                    'egresos_mes' => 0,
                    'balance_mes' => 0,
                    'plan' => 'free',
                    'limite_aves' => null,
                ],
                'charts' => [
                    'estatus_labels' => [],
                    'estatus_values' => [],
                    'ventas_labels' => [],
                    'ventas_values' => [],
                    'compras_values' => [],
                ],
                'recent' => [
                    'ventas' => [],
                    'compras' => [],
                ],
            ]);
        }

        $sub = Subscription::query()
            ->where('status', 'active')
            ->latest('id')
            ->first();

        $gallos     = Gallo::query()->count();
        $gallinas   = Gallina::query()->count();
        $inventarios = Schema::hasTable('inventarios') ? Inventario::query()->count() : 0;
        $ventasMes = Venta::query()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $hasComprasTable = Schema::hasTable('compras');
        $comprasMes = $hasComprasTable
            ? Compra::query()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count()
            : 0;
        $ingresosMes = (float) Venta::query()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('precio');
        $egresosMes = $hasComprasTable
            ? (float) Compra::query()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total')
            : 0.0;

        $galloBy = Gallo::query()->selectRaw('estatus, count(*) as c')->groupBy('estatus')->pluck('c', 'estatus');
        $gallinaBy = Gallina::query()->selectRaw('estatus, count(*) as c')->groupBy('estatus')->pluck('c', 'estatus');
        $labels = collect($galloBy->keys())->merge($gallinaBy->keys())->unique()->sort()->values();
        $estatusLabels = [];
        $estatusValues = [];
        foreach ($labels as $label) {
            $estatusLabels[] = (string) $label;
            $estatusValues[] = (int) ($galloBy[$label] ?? 0) + (int) ($gallinaBy[$label] ?? 0);
        }

        $ventasLabels = [];
        $ventasValues = [];
        $comprasValues = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $ventasLabels[] = $d->format('m/Y');
            $ventasValues[] = (float) Venta::query()
                ->whereYear('created_at', $d->year)
                ->whereMonth('created_at', $d->month)
                ->sum('precio');
            $comprasValues[] = $hasComprasTable
                ? (float) Compra::query()
                    ->whereYear('created_at', $d->year)
                    ->whereMonth('created_at', $d->month)
                    ->sum('total')
                : 0.0;
        }

        $recentVentas = Venta::query()
            ->with(['gallo', 'cliente'])
            ->latest('id')
            ->take(6)
            ->get()
            ->map(fn (Venta $v) => [
                'id' => $v->id,
                'fecha' => optional($v->created_at)->format('d/m/Y'),
                'gallo' => $v->gallo?->placa ?? ('#'.$v->gallo_id),
                'cliente' => $v->cliente?->name ?? $v->nombre_cliente,
                'monto' => (float) ($v->precio ?? 0),
            ]);

        $recentCompras = $hasComprasTable
            ? Compra::query()
                ->with(['proveedor'])
                ->latest('id')
                ->take(6)
                ->get()
                ->map(fn (Compra $c) => [
                    'id' => $c->id,
                    'fecha' => optional($c->fecha_compra)->format('d/m/Y'),
                    'proveedor' => $c->proveedor?->name ?? 'N/D',
                    'total' => (float) ($c->total ?? 0),
                ])
            : collect();

        $ventasTotal  = Venta::query()->count();
        $comprasTotal = $hasComprasTable ? Compra::query()->count() : 0;

        return view('admin.dashboard.index', [
            'stats' => [
                'gallos'       => $gallos,
                'gallinas'     => $gallinas,
                'inventarios'  => $inventarios,
                'aves_total'   => $gallos + $gallinas,
                'ventas_mes'   => $ventasMes,
                'compras_mes'  => $comprasMes,
                'ventas_total' => $ventasTotal,
                'compras_total'=> $comprasTotal,
                'ingresos_mes' => $ingresosMes,
                'egresos_mes'  => $egresosMes,
                'balance_mes'  => $ingresosMes - $egresosMes,
                'plan'         => $sub?->plan ?? 'free',
                'limite_aves'  => ($sub?->plan ?? 'free') === 'free' ? 20 : null,
            ],
            'charts' => [
                'estatus_labels' => $estatusLabels,
                'estatus_values' => $estatusValues,
                'ventas_labels' => $ventasLabels,
                'ventas_values' => $ventasValues,
                'compras_values' => $comprasValues,
            ],
            'recent' => [
                'ventas' => $recentVentas,
                'compras' => $recentCompras,
            ],
        ]);
    }
}

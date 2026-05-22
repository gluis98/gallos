<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PaymentOrder;
use App\Models\Subscription;
use App\Models\User;
use App\Services\AuditService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // ── Totales generales ──────────────────────────────
        $totalUsers      = User::query()->where('is_superadmin', false)->count();
        $totalGallos     = DB::table('gallos')->count();
        $totalGallinas   = DB::table('gallinas')->count();
        $totalVentas     = DB::table('ventas')->count();

        $proSubs         = Subscription::withoutGlobalScopes()->where('plan', 'pro')->where('status', 'active')->count();
        $pendingPayments = PaymentOrder::query()->where('status', 'pendiente')->count();

        $revenueTotal = PaymentOrder::query()
            ->where('status', 'verificado')
            ->sum('amount');

        $revenueMonth = PaymentOrder::query()
            ->where('status', 'verificado')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // ── Registros nuevos hoy / esta semana / este mes ──
        $newToday  = User::query()->where('is_superadmin', false)->whereDate('created_at', today())->count();
        $newWeek   = User::query()->where('is_superadmin', false)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $newMonth  = User::query()->where('is_superadmin', false)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

        // ── Registros diarios — últimos 30 días (para gráfico) ──
        $from = now()->subDays(29)->startOfDay();
        $usersByDay = User::query()
            ->where('is_superadmin', false)
            ->where('created_at', '>=', $from)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $chartDays = [];
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = now()->subDays($i)->format('Y-m-d');
            $chartDays[] = now()->subDays($i)->format('d M');
            $chartData[] = $usersByDay[$d] ?? 0;
        }

        // ── Ingresos últimos 6 meses ──
        $revenueByMonth = [];
        $revenueLabels  = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $revenueLabels[]  = $m->translatedFormat('M Y');
            $revenueByMonth[] = (float) PaymentOrder::query()
                ->where('status', 'verificado')
                ->whereMonth('created_at', $m->month)
                ->whereYear('created_at', $m->year)
                ->sum('amount');
        }

        // ── Suscripciones próximas a vencer (30 días) ──
        $expiringSoon = Subscription::withoutGlobalScopes()
            ->where('plan', 'pro')
            ->where('status', 'active')
            ->whereBetween('ends_at', [now(), now()->addDays(30)])
            ->with('tenant')
            ->orderBy('ends_at')
            ->get()
            ->map(function ($sub) {
                $user = User::query()->where('tenant_id', $sub->tenant_id)->where('is_superadmin', false)->first();
                $sub->user = $user;
                $sub->days_left = (int) now()->diffInDays($sub->ends_at, false);
                return $sub;
            });

        // ── Suscripciones vencidas sin renovar ──
        $expiredCount = Subscription::withoutGlobalScopes()
            ->where('plan', 'pro')
            ->where(function ($q) {
                $q->where('status', 'cancelled')->orWhere(function ($q2) {
                    $q2->where('status', 'active')->where('ends_at', '<', now());
                });
            })->count();

        // ── Pagos pendientes recientes ──
        $recentPending = PaymentOrder::query()
            ->where('status', 'pendiente')
            ->with('tenant')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get()
            ->map(function ($o) {
                $o->user = User::query()->where('tenant_id', $o->tenant_id)->where('is_superadmin', false)->first();
                return $o;
            });

        // ── Últimas entradas de auditoría ──
        $auditRecent = collect(AuditService::all())->take(10);

        return view('super-admin.dashboard.index', compact(
            'totalUsers', 'totalGallos', 'totalGallinas', 'totalVentas',
            'proSubs', 'pendingPayments', 'revenueTotal', 'revenueMonth',
            'newToday', 'newWeek', 'newMonth',
            'chartDays', 'chartData',
            'revenueLabels', 'revenueByMonth',
            'expiringSoon', 'expiredCount',
            'recentPending', 'auditRecent'
        ));
    }
}

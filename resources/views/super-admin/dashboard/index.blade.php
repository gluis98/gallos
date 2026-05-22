@extends('layouts.app')

@section('styles')
<style>
    .sa-metric { background:#fff; border:1px solid #e8eef8; border-radius:1rem; padding:1.25rem 1.4rem; box-shadow:0 4px 16px rgba(16,39,77,.06); display:flex; align-items:center; gap:1rem; }
    .sa-metric-icon { width:50px; height:50px; border-radius:.85rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .sa-metric-icon .material-symbols-outlined { font-size:1.45rem; }
    .sa-metric-val { font-size:1.65rem; font-weight:900; line-height:1; color:#1a2648; }
    .sa-metric-lbl { font-size:.75rem; color:#60708d; margin-top:.15rem; }
    .sa-metric-badge { font-size:.68rem; font-weight:700; padding:.15rem .5rem; border-radius:20px; margin-left:.4rem; }
    .chart-card { background:#fff; border:1px solid #e8eef8; border-radius:1rem; padding:1.4rem; box-shadow:0 4px 16px rgba(16,39,77,.06); }
    .chart-card h6 { font-weight:800; color:#1a2648; margin-bottom:1rem; font-size:.9rem; display:flex; align-items:center; gap:.4rem; }
    .chart-card h6 .material-symbols-outlined { font-size:1rem; color:#3b82f6; }
    .alert-card { background:#fff; border:1px solid #e8eef8; border-radius:1rem; padding:1.25rem 1.4rem; box-shadow:0 4px 16px rgba(16,39,77,.06); }
    .alert-card h6 { font-weight:800; color:#1a2648; font-size:.9rem; display:flex; align-items:center; gap:.4rem; margin-bottom:1rem; }
    .expiry-row { display:flex; justify-content:space-between; align-items:center; padding:.6rem 0; border-bottom:1px solid #f0f4fc; font-size:.83rem; }
    .expiry-row:last-child { border-bottom:none; }
    .expiry-days { font-weight:800; padding:.15rem .55rem; border-radius:.4rem; font-size:.72rem; }
    .days-ok      { background:#dcfce7; color:#166534; }
    .days-warn    { background:#fef9c3; color:#854d0e; }
    .days-danger  { background:#fee2e2; color:#991b1b; }
    .pending-row { display:flex; justify-content:space-between; align-items:center; padding:.6rem 0; border-bottom:1px solid #f0f4fc; font-size:.83rem; }
    .pending-row:last-child { border-bottom:none; }
    .audit-row { display:flex; gap:.75rem; padding:.55rem 0; border-bottom:1px solid #f0f4fc; font-size:.8rem; }
    .audit-row:last-child { border-bottom:none; }
    .audit-action { font-size:.68rem; font-weight:700; padding:.12rem .5rem; border-radius:.35rem; white-space:nowrap; }
    .act-payment  { background:#dcfce7; color:#166534; }
    .act-sub      { background:#dbeafe; color:#1d4ed8; }
    .act-user     { background:#fdf4ff; color:#7c3aed; }
    .act-settings { background:#fff7ed; color:#9a3412; }
    .act-other    { background:#f3f4f6; color:#4b5563; }
</style>
@endsection

@section('content')
{{-- ── Métricas principales ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="sa-metric">
            <div class="sa-metric-icon" style="background:#eff6ff;">
                <span class="material-symbols-outlined" style="color:#3b82f6;">group</span>
            </div>
            <div>
                <div class="sa-metric-val">{{ $totalUsers }}</div>
                <div class="sa-metric-lbl">Usuarios totales
                    <span class="sa-metric-badge" style="background:#dcfce7;color:#166534;">+{{ $newToday }} hoy</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="sa-metric">
            <div class="sa-metric-icon" style="background:#f0fdf4;">
                <span class="material-symbols-outlined" style="color:#22c55e;">workspace_premium</span>
            </div>
            <div>
                <div class="sa-metric-val">{{ $proSubs }}</div>
                <div class="sa-metric-lbl">Suscripciones Pro activas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="sa-metric">
            <div class="sa-metric-icon" style="background:#fefce8;">
                <span class="material-symbols-outlined" style="color:#eab308;">payments</span>
            </div>
            <div>
                <div class="sa-metric-val">${{ number_format($revenueMonth, 0) }}</div>
                <div class="sa-metric-lbl">Ingresos este mes (USD)</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="sa-metric">
            <div class="sa-metric-icon" style="{{ $pendingPayments > 0 ? 'background:#fef2f2;' : 'background:#f3f4f6;' }}">
                <span class="material-symbols-outlined" style="{{ $pendingPayments > 0 ? 'color:#ef4444;' : 'color:#9ca3af;' }}">pending_actions</span>
            </div>
            <div>
                <div class="sa-metric-val" style="{{ $pendingPayments > 0 ? 'color:#dc2626;' : '' }}">{{ $pendingPayments }}</div>
                <div class="sa-metric-lbl">Pagos pendientes
                    @if($pendingPayments > 0)
                        <a href="{{ route('superadmin.payments.index') }}" style="font-size:.68rem;color:#3b82f6;margin-left:.3rem;">Revisar →</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="sa-metric">
            <div class="sa-metric-icon" style="background:#eff6ff;"><span class="material-symbols-outlined" style="color:#1d4ed8;">pets</span></div>
            <div><div class="sa-metric-val">{{ $totalGallos }}</div><div class="sa-metric-lbl">Gallos registrados</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="sa-metric">
            <div class="sa-metric-icon" style="background:#fdf4ff;"><span class="material-symbols-outlined" style="color:#7c3aed;">egg</span></div>
            <div><div class="sa-metric-val">{{ $totalGallinas }}</div><div class="sa-metric-lbl">Gallinas registradas</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="sa-metric">
            <div class="sa-metric-icon" style="background:#f0fdf4;"><span class="material-symbols-outlined" style="color:#16a34a;">sell</span></div>
            <div><div class="sa-metric-val">{{ $totalVentas }}</div><div class="sa-metric-lbl">Ventas totales</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="sa-metric">
            <div class="sa-metric-icon" style="background:#fefce8;"><span class="material-symbols-outlined" style="color:#ca8a04;">attach_money</span></div>
            <div><div class="sa-metric-val">${{ number_format($revenueTotal, 0) }}</div><div class="sa-metric-lbl">Ingresos totales (USD)</div></div>
        </div>
    </div>
</div>

{{-- ── Gráficos ── --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="chart-card">
            <h6><span class="material-symbols-outlined">person_add</span> Nuevos usuarios — últimos 30 días
                <span class="ms-auto" style="font-size:.75rem;font-weight:600;color:#60708d;">
                    {{ $newWeek }} esta semana · {{ $newMonth }} este mes
                </span>
            </h6>
            <canvas id="chart-users" height="95"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="chart-card">
            <h6><span class="material-symbols-outlined">bar_chart</span> Ingresos últimos 6 meses</h6>
            <canvas id="chart-revenue" height="185"></canvas>
        </div>
    </div>
</div>

{{-- ── Alertas + pendientes + auditoría ── --}}
<div class="row g-3">
    {{-- Suscripciones por vencer --}}
    <div class="col-lg-4">
        <div class="alert-card">
            <h6>
                <span class="material-symbols-outlined" style="color:#f59e0b;">notifications_active</span>
                Suscripciones por vencer
                @if($expiringSoon->count() > 0)
                    <span class="sa-badge" style="background:#f59e0b;">{{ $expiringSoon->count() }}</span>
                @endif
            </h6>
            @forelse($expiringSoon as $sub)
            <div class="expiry-row">
                <div>
                    <div class="fw-semibold" style="color:#1a2648;">{{ optional($sub->user)->name ?? 'Sin usuario' }}</div>
                    <div style="font-size:.72rem;color:#60708d;">{{ optional($sub->user)->email ?? 'Tenant: '.Str::limit($sub->tenant_id,12,'…') }}</div>
                </div>
                <span class="expiry-days {{ $sub->days_left <= 7 ? 'days-danger' : ($sub->days_left <= 15 ? 'days-warn' : 'days-ok') }}">
                    {{ $sub->days_left }}d
                </span>
            </div>
            @empty
            <p class="text-muted small mb-0 text-center py-3">✅ Sin suscripciones próximas a vencer.</p>
            @endforelse
            @if($expiringSoon->count() > 0)
            <div class="mt-2 text-end">
                <a href="{{ route('superadmin.subscriptions.index') }}?filter=expiring" style="font-size:.78rem;color:#3b82f6;">Ver todas →</a>
            </div>
            @endif
        </div>
    </div>

    {{-- Pagos pendientes --}}
    <div class="col-lg-4">
        <div class="alert-card">
            <h6>
                <span class="material-symbols-outlined" style="color:#3b82f6;">receipt_long</span>
                Pagos pendientes de revisión
            </h6>
            @forelse($recentPending as $p)
            <div class="pending-row">
                <div>
                    <div class="fw-semibold" style="color:#1a2648;">{{ optional($p->user)->name ?? 'Sin usuario' }}</div>
                    <div style="font-size:.72rem;color:#60708d;">{{ ucfirst($p->method) }} · {{ optional($p->created_at)->diffForHumans() }}</div>
                </div>
                <div class="text-end">
                    <div class="fw-bold" style="color:#16a34a;font-size:.9rem;">${{ number_format($p->amount, 2) }}</div>
                    <a href="{{ route('superadmin.payments.index') }}" style="font-size:.68rem;color:#3b82f6;">Revisar</a>
                </div>
            </div>
            @empty
            <p class="text-muted small mb-0 text-center py-3">✅ Sin pagos pendientes.</p>
            @endforelse
        </div>
    </div>

    {{-- Auditoría reciente --}}
    <div class="col-lg-4">
        <div class="alert-card">
            <h6>
                <span class="material-symbols-outlined" style="color:#6d28d9;">history</span>
                Actividad reciente
            </h6>
            @forelse($auditRecent as $entry)
            @php
                $cls = match(true) {
                    str_starts_with($entry['action'],'payment')     => 'act-payment',
                    str_starts_with($entry['action'],'subscription')=> 'act-sub',
                    str_starts_with($entry['action'],'user')        => 'act-user',
                    str_starts_with($entry['action'],'settings')    => 'act-settings',
                    default                                         => 'act-other',
                };
            @endphp
            <div class="audit-row">
                <span class="audit-action {{ $cls }}">{{ $entry['action'] }}</span>
                <div class="flex-grow-1" style="min-width:0;">
                    <div style="color:#374151;font-size:.78rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $entry['description'] }}</div>
                    <div style="color:#9baac7;font-size:.68rem;">{{ \Carbon\Carbon::parse($entry['created_at'])->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <p class="text-muted small mb-0 text-center py-3">Sin actividad registrada aún.</p>
            @endforelse
            <div class="mt-2 text-end">
                <a href="{{ route('superadmin.audit.index') }}" style="font-size:.78rem;color:#3b82f6;">Ver todo →</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartDefaults = { font: { family: 'Poppins, sans-serif' } };
Chart.defaults.font.family = 'Poppins, sans-serif';

// ── Usuarios por día ──
new Chart(document.getElementById('chart-users'), {
    type: 'bar',
    data: {
        labels: @json($chartDays),
        datasets: [{
            label: 'Nuevos usuarios',
            data: @json($chartData),
            backgroundColor: 'rgba(59,130,246,.18)',
            borderColor: '#3b82f6',
            borderWidth: 2,
            borderRadius: 6,
            hoverBackgroundColor: 'rgba(59,130,246,.35)',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 }, maxTicksLimit: 10 } },
            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f0f4fc' } }
        }
    }
});

// ── Ingresos por mes ──
new Chart(document.getElementById('chart-revenue'), {
    type: 'line',
    data: {
        labels: @json($revenueLabels),
        datasets: [{
            label: 'Ingresos USD',
            data: @json($revenueByMonth),
            borderColor: '#22c55e',
            backgroundColor: 'rgba(34,197,94,.1)',
            borderWidth: 2.5,
            pointBackgroundColor: '#22c55e',
            pointRadius: 4,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
            y: { beginAtZero: true, ticks: { font: { size: 10 }, callback: v => '$'+v }, grid: { color: '#f0f4fc' } }
        }
    }
});
</script>
@endsection

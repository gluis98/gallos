@extends('layouts.app')

@section('styles')
<style>
/* ── Dashboard ─────────────────────────────────────── */
.dash-stat-card {
    background: #fff;
    border: 1px solid #e8eef8;
    border-radius: 1rem;
    padding: 1.1rem 1.25rem;
    box-shadow: 0 4px 14px rgba(16,39,77,.05);
    transition: transform .2s, box-shadow .2s;
    height: 100%;
}
.dash-stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(16,39,77,.10); }
.dash-stat-icon {
    width: 44px; height: 44px; border-radius: .75rem;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.dash-stat-icon .material-symbols-outlined { font-size: 1.4rem; }
.dash-stat-value { font-size: 1.7rem; font-weight: 900; color: #1a2648; line-height: 1; }
.dash-stat-label { font-size: .72rem; color: #60708d; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; margin-top: .2rem; }

/* ── Onboarding ─────────────────────────────────────── */
.onb-wrap {
    background: linear-gradient(135deg, #f0f6ff 0%, #fff 60%, #f5f0ff 100%);
    border: 1.5px solid #dbeafe;
    border-radius: 1.2rem;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}
.onb-wrap::before {
    content: '';
    position: absolute; top: -30px; right: -30px;
    width: 160px; height: 160px;
    background: radial-gradient(circle, rgba(99,102,241,.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
}
.onb-wrap > * { position: relative; z-index: 1; }

/* Pasos desktop */
.onb-step {
    display: flex; align-items: center; gap: 1rem;
    padding: .75rem 1rem;
    border-radius: .85rem;
    transition: all .18s;
    cursor: pointer; text-decoration: none;
    border: 1.5px solid transparent;
}
.onb-step:hover { background: rgba(59,130,246,.06); border-color: #bfdbfe; transform: translateX(3px); }
.onb-step.done   { opacity: .6; }
.onb-step.done:hover { transform: none; }
.onb-icon {
    width: 42px; height: 42px; border-radius: .75rem;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    font-size: 1.3rem;
}
.onb-check {
    width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    margin-left: auto; font-size: .9rem;
}
.onb-check.pending { background: #f1f5fb; border: 2px solid #d7dfed; color: transparent; }
.onb-check.done    { background: #dcfce7; border: 2px solid #86efac; color: #15803d; }

/* Chips móvil */
.onb-chips-scroll {
    display: flex; gap: .55rem;
    overflow-x: auto; padding-bottom: .35rem;
    -webkit-overflow-scrolling: touch; scrollbar-width: none;
}
.onb-chips-scroll::-webkit-scrollbar { display: none; }
.onb-chip {
    display: inline-flex; align-items: center; gap: .4rem;
    white-space: nowrap; flex-shrink: 0;
    padding: .45rem .8rem; border-radius: 20px;
    text-decoration: none; font-size: .75rem; font-weight: 700;
    border: 1.5px solid #dbeafe; background: #fff;
    color: #1a2648; transition: all .15s;
}
.onb-chip:active { transform: scale(.96); }
.onb-chip.done {
    background: #f0fdf4; border-color: #86efac; color: #15803d; opacity: .75;
}
.onb-chip.done .chip-check { color: #15803d; }
.chip-check { font-size: .7rem; font-weight: 900; }
.chip-pending { width: 8px; height: 8px; border-radius: 50%; background: #dbeafe; border: 1.5px solid #93c5fd; flex-shrink: 0; }

/* Barra de progreso lineal (móvil) */
.onb-progress-bar-wrap {
    background: #e8eef8; border-radius: 20px; height: 5px; overflow: hidden; margin: .65rem 0 .5rem;
}
.onb-progress-bar {
    height: 100%; border-radius: 20px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    transition: width .6s cubic-bezier(.4,0,.2,1);
}

/* Anillo desktop */
.progress-ring { width: 56px; height: 56px; flex-shrink: 0; }
.progress-ring circle { fill: none; stroke-width: 5; }
.progress-ring .bg  { stroke: #e8eef8; }
.progress-ring .fg  { stroke: url(#ring-gradient); stroke-linecap: round; transform: rotate(-90deg); transform-origin: 28px 28px; transition: stroke-dashoffset .6s ease; }

/* ── Responsive ─────────────────────────────────────── */
@media (max-width: 767px) {
    .onb-wrap { padding: .85rem 1rem; margin-bottom: 1rem; }
    .onb-desktop-header { display: none !important; }
    .onb-steps-grid { display: none !important; }
    .onb-mobile { display: block !important; }
}
@media (min-width: 768px) {
    .onb-mobile { display: none !important; }
}

.section-divider { height: 1px; background: linear-gradient(90deg, transparent, #e8eef8, transparent); margin: 1.5rem 0; }

/* ── Quick access ─────────────────────────────────────── */
.quick-btn {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: .4rem; padding: .9rem .5rem;
    background: #fff; border: 1.5px solid #e8eef8; border-radius: 1rem;
    text-decoration: none; transition: all .18s; color: #1a2648;
    box-shadow: 0 2px 8px rgba(16,39,77,.04);
}
.quick-btn:hover { border-color: #6ea4ff; background: #f0f6ff; transform: translateY(-2px); box-shadow: 0 6px 18px rgba(59,130,246,.13); color: #1a2648; }
.quick-btn .material-symbols-outlined { font-size: 1.5rem; color: #3b82f6; }
.quick-btn span.label { font-size: .75rem; font-weight: 700; text-align: center; line-height: 1.2; }

/* ── Tabla reciente ─────────────────────────────────────── */
.recent-table th { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #94a3b8; border-bottom: 1.5px solid #e8eef8; padding: .5rem .75rem; }
.recent-table td { font-size: .82rem; color: #374151; padding: .55rem .75rem; border-bottom: 1px solid #f3f6fc; }
.recent-table tr:last-child td { border-bottom: none; }
.amount-positive { color: #15803d; font-weight: 800; }
.amount-negative { color: #dc2626; font-weight: 800; }
</style>
@endsection

@section('content')

@php
$steps = [
    [
        'key'   => 'gallos',
        'done'  => ($stats['gallos'] ?? 0) > 0,
        'icon'  => '🐓',
        'color' => '#fef3c7',
        'title' => 'Registra tus gallos',
        'desc'  => 'Agrega el plantel de gallos de tu criadero con placa, color, fecha y fotos.',
        'url'   => route('gallos'),
        'btn'   => 'Ir a Gallos',
    ],
    [
        'key'   => 'gallinas',
        'done'  => ($stats['gallinas'] ?? 0) > 0,
        'icon'  => '🐔',
        'color' => '#f5f3ff',
        'title' => 'Registra tus gallinas',
        'desc'  => 'Lleva el control de tu pie de cría con historial de eventos y pedigree.',
        'url'   => route('gallinas'),
        'btn'   => 'Ir a Gallinas',
    ],
    [
        'key'   => 'inventario',
        'done'  => ($stats['inventarios'] ?? 0) > 0,
        'icon'  => '📦',
        'color' => '#dbeafe',
        'title' => 'Carga tu inventario',
        'desc'  => 'Registra medicamentos, alimentos e insumos con costo, utilidad y precio de venta en USD y Bs.',
        'url'   => route('inventario'),
        'btn'   => 'Ir a Inventario',
    ],
    [
        'key'   => 'compras',
        'done'  => ($stats['compras_total'] ?? 0) > 0,
        'icon'  => '🛒',
        'color' => '#fce7f3',
        'title' => 'Registra tus compras',
        'desc'  => 'Lleva un historial de lo que compras a tus proveedores: aves e insumos.',
        'url'   => route('compras'),
        'btn'   => 'Ir a Compras',
    ],
    [
        'key'   => 'ventas',
        'done'  => ($stats['ventas_total'] ?? 0) > 0,
        'icon'  => '💰',
        'color' => '#dcfce7',
        'title' => 'Registra tu primera venta',
        'desc'  => 'Vende gallos, gallinas o ítems de inventario y monitorea tus ingresos.',
        'url'   => route('ventas'),
        'btn'   => 'Ir a Ventas',
    ],
];
$completed = collect($steps)->where('done', true)->count();
$total     = count($steps);
$pct       = $total > 0 ? round($completed / $total * 100) : 0;
$allDone   = $completed === $total;
@endphp

{{-- ═══ GUÍA DE INICIO ════════════════════════════════════════════ --}}
@if(!$allDone)
<div class="onb-wrap">

    {{-- ── Versión MÓVIL (compacta) ─────────────────── --}}
    <div class="onb-mobile">
        {{-- Fila: icono rocket + título + % + toggle --}}
        <div style="display:flex;align-items:center;gap:.65rem;">
            <div style="background:linear-gradient(135deg,#3b82f6,#8b5cf6);width:32px;height:32px;border-radius:.6rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span class="material-symbols-outlined" style="font-size:1rem;color:#fff;">rocket_launch</span>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:.8rem;font-weight:800;color:#1a2648;line-height:1.1;">¡Configura tu criadero!</div>
                <div style="font-size:.65rem;color:#60708d;">{{ $completed }} de {{ $total }} pasos completados</div>
            </div>
            <div style="display:flex;align-items:center;gap:.5rem;flex-shrink:0;">
                <span style="font-size:.75rem;font-weight:900;color:#3b82f6;">{{ $pct }}%</span>
                <button type="button" id="btn-toggle-guia" style="background:#f3f6fd;border:1.5px solid #d7dfed;border-radius:.55rem;padding:.2rem .5rem;font-size:.7rem;font-weight:700;color:#374151;cursor:pointer;white-space:nowrap;position:relative;z-index:2;line-height:1.4;">
                    <span id="toggle-arrow">▼</span>
                </button>
            </div>
        </div>

        {{-- Barra de progreso lineal --}}
        <div class="onb-progress-bar-wrap">
            <div class="onb-progress-bar" style="width:{{ $pct }}%;"></div>
        </div>

        {{-- Chips horizontales (pasos) --}}
        <div id="onb-steps-list">
            <div class="onb-chips-scroll">
                @foreach($steps as $step)
                <a href="{{ $step['url'] }}" class="onb-chip {{ $step['done'] ? 'done' : '' }}">
                    <span>{{ $step['icon'] }}</span>
                    <span>{{ explode(' ', $step['title'])[1] ?? $step['title'] }}</span>
                    @if($step['done'])
                        <span class="chip-check">✓</span>
                    @else
                        <span class="chip-pending"></span>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Versión DESKTOP (completa) ──────────────── --}}
    <div class="onb-desktop-header" style="display:flex;align-items:flex-start;gap:1.25rem;flex-wrap:wrap;">
        <div style="display:flex;flex-direction:column;align-items:center;gap:.4rem;flex-shrink:0;">
            <svg class="progress-ring" viewBox="0 0 56 56">
                <defs>
                    <linearGradient id="ring-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#3b82f6"/>
                        <stop offset="100%" stop-color="#8b5cf6"/>
                    </linearGradient>
                </defs>
                <circle class="bg" cx="28" cy="28" r="23"/>
                <circle class="fg" cx="28" cy="28" r="23"
                    stroke-dasharray="{{ round(2 * pi() * 23, 2) }}"
                    stroke-dashoffset="{{ round(2 * pi() * 23 * (1 - $pct / 100), 2) }}"
                />
                <text x="28" y="33" text-anchor="middle" font-size="11" font-weight="900" fill="#1a2648">{{ $pct }}%</text>
            </svg>
            <span style="font-size:.65rem;font-weight:700;color:#60708d;text-align:center;line-height:1.2;">{{ $completed }}/{{ $total }}<br>pasos</span>
        </div>
        <div style="flex:1;min-width:200px;">
            <h4 style="font-size:1rem;font-weight:900;color:#1a2648;margin:0 0 .2rem;">
                🚀 ¡Comienza a configurar tu criadero!
            </h4>
            <p style="font-size:.82rem;color:#60708d;margin:0;">
                Completa los siguientes pasos para sacar el máximo provecho de <strong>Gallos Pro</strong>.
                Cada módulo está diseñado para ahorrarte tiempo y darte control total.
            </p>
        </div>
        <button type="button" id="btn-toggle-guia-desktop" style="background:#f3f6fd;border:1.5px solid #d7dfed;border-radius:.65rem;padding:.35rem .75rem;font-size:.75rem;font-weight:600;color:#374151;cursor:pointer;flex-shrink:0;white-space:nowrap;position:relative;z-index:2;">
            Ocultar guía ▲
        </button>
    </div>

    <div id="onb-steps-list-desktop" style="margin-top:1.1rem;">
        <div class="row g-2">
            @foreach($steps as $step)
            <div class="col-12 col-md-6">
                <a href="{{ $step['url'] }}" class="onb-step {{ $step['done'] ? 'done' : '' }}">
                    <div class="onb-icon" style="background:{{ $step['color'] }};">{{ $step['icon'] }}</div>
                    <div style="flex:1;overflow:hidden;">
                        <div style="font-size:.86rem;font-weight:800;color:#1a2648;display:flex;align-items:center;gap:.4rem;">
                            {{ $step['title'] }}
                            @if(!$step['done'])
                            <span style="background:#eff6ff;color:#3b82f6;font-size:.6rem;font-weight:700;border-radius:20px;padding:.1rem .45rem;">Pendiente</span>
                            @endif
                        </div>
                        <div style="font-size:.74rem;color:#60708d;margin-top:.15rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $step['desc'] }}</div>
                    </div>
                    <div class="onb-check {{ $step['done'] ? 'done' : 'pending' }}">
                        @if($step['done']) ✓ @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@else
{{-- Felicitación cuando todo está completado --}}
<div style="background:linear-gradient(135deg,#15803d,#16a34a);border-radius:1.2rem;padding:1.1rem 1.5rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
    <span style="font-size:2rem;">🎉</span>
    <div>
        <div style="font-weight:900;color:#fff;font-size:1rem;">¡Configuración completada!</div>
        <div style="font-size:.8rem;color:rgba(255,255,255,.8);">Tu criadero está 100% configurado. Ahora tienes control total de gallos, gallinas, inventario, compras y ventas.</div>
    </div>
</div>
@endif

{{-- ═══ ESTADÍSTICAS PRINCIPALES ════════════════════════════════════ --}}
<section class="section-card p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="section-title">Panel de negocio</h2>
            <p class="section-subtitle mb-0">Resumen operativo y financiero del criadero</p>
        </div>
        <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
            @php $currentPlan = $stats['plan'] ?? 'free'; @endphp
            <span style="background:{{ $currentPlan === 'pro' ? 'linear-gradient(135deg,#f59e0b,#d97706)' : '#f3f6fd' }};color:{{ $currentPlan === 'pro' ? '#fff' : '#374151' }};border:1.5px solid {{ $currentPlan === 'pro' ? '#f59e0b' : '#d7dfed' }};border-radius:20px;padding:.3rem .85rem;font-size:.75rem;font-weight:800;">
                {{ $currentPlan === 'pro' ? '⭐ Plan Pro' : '🔒 Plan Gratis' }}
            </span>
            @if($currentPlan !== 'pro')
            <a href="{{ route('plans') }}" style="background:linear-gradient(135deg,#1a2648,#3b82f6);color:#fff;border:none;border-radius:20px;padding:.3rem .85rem;font-size:.75rem;font-weight:700;text-decoration:none;">Mejorar a Pro →</a>
            @endif
        </div>
    </div>

    {{-- Stats cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-sm-4 col-md-2">
            <div class="dash-stat-card">
                <div class="dash-stat-icon" style="background:#fef3c7;">
                    <span class="material-symbols-outlined" style="color:#d97706;">agriculture</span>
                </div>
                <div class="dash-stat-value mt-2">{{ $stats['gallos'] ?? 0 }}</div>
                <div class="dash-stat-label">Gallos</div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            <div class="dash-stat-card">
                <div class="dash-stat-icon" style="background:#f5f3ff;">
                    <span class="material-symbols-outlined" style="color:#7c3aed;">egg</span>
                </div>
                <div class="dash-stat-value mt-2">{{ $stats['gallinas'] ?? 0 }}</div>
                <div class="dash-stat-label">Gallinas</div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            <div class="dash-stat-card">
                <div class="dash-stat-icon" style="background:#dbeafe;">
                    <span class="material-symbols-outlined" style="color:#1d4ed8;">inventory_2</span>
                </div>
                <div class="dash-stat-value mt-2">{{ $stats['inventarios'] ?? 0 }}</div>
                <div class="dash-stat-label">Inventario</div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            <div class="dash-stat-card">
                <div class="dash-stat-icon" style="background:#dcfce7;">
                    <span class="material-symbols-outlined" style="color:#15803d;">point_of_sale</span>
                </div>
                <div class="dash-stat-value mt-2">{{ $stats['ventas_mes'] ?? 0 }}</div>
                <div class="dash-stat-label">Ventas (mes)</div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            <div class="dash-stat-card">
                <div class="dash-stat-icon" style="background:#fce7f3;">
                    <span class="material-symbols-outlined" style="color:#9d174d;">shopping_cart</span>
                </div>
                <div class="dash-stat-value mt-2">{{ $stats['compras_mes'] ?? 0 }}</div>
                <div class="dash-stat-label">Compras (mes)</div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            @php $balance = (float)($stats['balance_mes'] ?? 0); @endphp
            <div class="dash-stat-card" style="border-color:{{ $balance >= 0 ? '#86efac' : '#fca5a5' }};">
                <div class="dash-stat-icon" style="background:{{ $balance >= 0 ? '#dcfce7' : '#fee2e2' }};">
                    <span class="material-symbols-outlined" style="color:{{ $balance >= 0 ? '#15803d' : '#dc2626' }};">{{ $balance >= 0 ? 'trending_up' : 'trending_down' }}</span>
                </div>
                <div class="dash-stat-value mt-2 {{ $balance >= 0 ? 'amount-positive' : 'amount-negative' }}" style="font-size:1.1rem;">
                    ${{ number_format(abs($balance), 0) }}
                </div>
                <div class="dash-stat-label">Balance mes</div>
            </div>
        </div>
    </div>

    {{-- Ingresos / Egresos --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div style="background:linear-gradient(135deg,#15803d,#16a34a);border-radius:1rem;padding:1.1rem 1.25rem;color:#fff;display:flex;align-items:center;gap:1rem;">
                <div style="background:rgba(255,255,255,.15);width:44px;height:44px;border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span class="material-symbols-outlined" style="font-size:1.4rem;">arrow_downward</span>
                </div>
                <div>
                    <div style="font-size:1.4rem;font-weight:900;">${{ number_format((float)($stats['ingresos_mes'] ?? 0), 2) }}</div>
                    <div style="font-size:.72rem;opacity:.8;text-transform:uppercase;letter-spacing:.04em;">Ingresos este mes</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div style="background:linear-gradient(135deg,#b91c1c,#dc2626);border-radius:1rem;padding:1.1rem 1.25rem;color:#fff;display:flex;align-items:center;gap:1rem;">
                <div style="background:rgba(255,255,255,.15);width:44px;height:44px;border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span class="material-symbols-outlined" style="font-size:1.4rem;">arrow_upward</span>
                </div>
                <div>
                    <div style="font-size:1.4rem;font-weight:900;">${{ number_format((float)($stats['egresos_mes'] ?? 0), 2) }}</div>
                    <div style="font-size:.72rem;opacity:.8;text-transform:uppercase;letter-spacing:.04em;">Egresos este mes</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div style="background:linear-gradient(135deg,#1a2648,#2d4278);border-radius:1rem;padding:1.1rem 1.25rem;color:#fff;display:flex;align-items:center;gap:1rem;">
                <div style="background:rgba(255,255,255,.12);width:44px;height:44px;border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span class="material-symbols-outlined" style="font-size:1.4rem;">account_balance</span>
                </div>
                <div>
                    <div style="font-size:1.4rem;font-weight:900; color:{{ $balance >= 0 ? '#4ade80' : '#f87171' }};">${{ number_format($balance, 2) }}</div>
                    <div style="font-size:.72rem;opacity:.8;text-transform:uppercase;letter-spacing:.04em;">Balance neto mes</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Accesos rápidos --}}
    <div class="mb-4">
        <div style="font-size:.78rem;font-weight:700;color:#60708d;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Accesos rápidos</div>
        <div class="row g-2">
            <div class="col-4 col-md-2"><a href="{{ route('gallos') }}" class="quick-btn"><span class="material-symbols-outlined">agriculture</span><span class="label">Gallos</span></a></div>
            <div class="col-4 col-md-2"><a href="{{ route('gallinas') }}" class="quick-btn"><span class="material-symbols-outlined">egg</span><span class="label">Gallinas</span></a></div>
            <div class="col-4 col-md-2"><a href="{{ route('inventario') }}" class="quick-btn"><span class="material-symbols-outlined">inventory_2</span><span class="label">Inventario</span></a></div>
            <div class="col-4 col-md-2"><a href="{{ route('compras') }}" class="quick-btn"><span class="material-symbols-outlined">shopping_cart</span><span class="label">Compras</span></a></div>
            <div class="col-4 col-md-2"><a href="{{ route('ventas') }}" class="quick-btn"><span class="material-symbols-outlined">point_of_sale</span><span class="label">Ventas</span></a></div>
            <div class="col-4 col-md-2"><a href="{{ route('plans') }}" class="quick-btn"><span class="material-symbols-outlined">workspace_premium</span><span class="label">Planes</span></a></div>
        </div>
    </div>

    <div class="section-divider"></div>

    {{-- Gráficas --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-5">
            <div style="background:#fff;border:1px solid #e8eef8;border-radius:1rem;padding:1.1rem;box-shadow:0 2px 8px rgba(16,39,77,.04);">
                <div style="font-size:.78rem;font-weight:700;color:#60708d;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Aves por estatus</div>
                @if(count($charts['estatus_labels'] ?? []) > 0)
                    <canvas id="chart-estatus" height="200"></canvas>
                @else
                    <div style="text-align:center;padding:2rem;color:#94a3b8;font-size:.85rem;">
                        <span class="material-symbols-outlined" style="display:block;font-size:2rem;margin-bottom:.5rem;">bar_chart</span>
                        Sin datos aún
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-7">
            <div style="background:#fff;border:1px solid #e8eef8;border-radius:1rem;padding:1.1rem;box-shadow:0 2px 8px rgba(16,39,77,.04);">
                <div style="font-size:.78rem;font-weight:700;color:#60708d;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">Ingresos vs egresos (6 meses)</div>
                @if(array_sum($charts['ventas_values'] ?? []) > 0 || array_sum($charts['compras_values'] ?? []) > 0)
                    <canvas id="chart-balance" height="200"></canvas>
                @else
                    <div style="text-align:center;padding:2rem;color:#94a3b8;font-size:.85rem;">
                        <span class="material-symbols-outlined" style="display:block;font-size:2rem;margin-bottom:.5rem;">show_chart</span>
                        Sin movimientos registrados aún
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tablas recientes --}}
    <div class="row g-3">
        <div class="col-lg-6">
            <div style="background:#fff;border:1px solid #e8eef8;border-radius:1rem;overflow:hidden;box-shadow:0 2px 8px rgba(16,39,77,.04);">
                <div style="padding:.85rem 1.1rem;border-bottom:1px solid #f1f5fb;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:.82rem;font-weight:800;color:#1a2648;">Últimas ventas</span>
                    <a href="{{ route('ventas') }}" style="font-size:.72rem;color:#3b82f6;font-weight:600;text-decoration:none;">Ver todas →</a>
                </div>
                @if(count($recent['ventas'] ?? []) > 0)
                <div class="table-responsive">
                    <table class="recent-table w-100">
                        <thead><tr><th>Fecha</th><th>Artículo</th><th>Cliente</th><th class="text-end">Monto</th></tr></thead>
                        <tbody>
                        @foreach(($recent['ventas'] ?? []) as $v)
                        <tr>
                            <td>{{ $v['fecha'] }}</td>
                            <td>{{ $v['gallo'] }}</td>
                            <td style="max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $v['cliente'] ?? '—' }}</td>
                            <td class="text-end amount-positive">${{ number_format((float)$v['monto'], 2) }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div style="text-align:center;padding:2rem;color:#94a3b8;font-size:.85rem;">
                    <span class="material-symbols-outlined" style="display:block;font-size:2rem;margin-bottom:.4rem;">receipt_long</span>
                    Sin ventas registradas aún
                </div>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div style="background:#fff;border:1px solid #e8eef8;border-radius:1rem;overflow:hidden;box-shadow:0 2px 8px rgba(16,39,77,.04);">
                <div style="padding:.85rem 1.1rem;border-bottom:1px solid #f1f5fb;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:.82rem;font-weight:800;color:#1a2648;">Últimas compras</span>
                    <a href="{{ route('compras') }}" style="font-size:.72rem;color:#3b82f6;font-weight:600;text-decoration:none;">Ver todas →</a>
                </div>
                @if(count($recent['compras'] ?? []) > 0)
                <div class="table-responsive">
                    <table class="recent-table w-100">
                        <thead><tr><th>Fecha</th><th>Proveedor</th><th class="text-end">Total</th></tr></thead>
                        <tbody>
                        @foreach(($recent['compras'] ?? []) as $c)
                        <tr>
                            <td>{{ $c['fecha'] }}</td>
                            <td>{{ $c['proveedor'] }}</td>
                            <td class="text-end amount-negative">${{ number_format((float)$c['total'], 2) }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div style="text-align:center;padding:2rem;color:#94a3b8;font-size:.85rem;">
                    <span class="material-symbols-outlined" style="display:block;font-size:2rem;margin-bottom:.4rem;">shopping_bag</span>
                    Sin compras registradas aún
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ── Toggle guía ──
document.addEventListener('DOMContentLoaded', () => {
    // Móvil
    const btnMob  = document.getElementById('btn-toggle-guia');
    const listMob = document.getElementById('onb-steps-list');
    const arrow   = document.getElementById('toggle-arrow');

    // Desktop
    const btnDesk  = document.getElementById('btn-toggle-guia-desktop');
    const listDesk = document.getElementById('onb-steps-list-desktop');

    function applyMobState(hidden) {
        if (!listMob) return;
        listMob.style.display = hidden ? 'none' : '';
        if (arrow) arrow.textContent = hidden ? '▶' : '▼';
        localStorage.setItem('guia_oculta', hidden ? '1' : '0');
    }
    function applyDeskState(hidden) {
        if (!listDesk) return;
        listDesk.style.display = hidden ? 'none' : '';
        if (btnDesk) btnDesk.textContent = hidden ? 'Mostrar guía ▼' : 'Ocultar guía ▲';
        localStorage.setItem('guia_oculta_desk', hidden ? '1' : '0');
    }

    // Restaurar estados
    applyMobState(localStorage.getItem('guia_oculta') === '1');
    applyDeskState(localStorage.getItem('guia_oculta_desk') === '1');

    btnMob?.addEventListener('click', () => {
        applyMobState(listMob?.style.display !== 'none');
    });
    btnDesk?.addEventListener('click', () => {
        applyDeskState(listDesk?.style.display !== 'none');
    });
});

// ── Charts ──
(() => {
    const labels  = @json($charts['estatus_labels'] ?? []);
    const values  = @json($charts['estatus_values'] ?? []);
    const mLabels = @json($charts['ventas_labels']  ?? []);
    const ingresos = @json($charts['ventas_values']  ?? []);
    const egresos  = @json($charts['compras_values'] ?? []);

    const estatusEl = document.getElementById('chart-estatus');
    if (estatusEl && labels.length) {
        new Chart(estatusEl, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: ['#3b82f6','#16a34a','#dc2626','#f59e0b','#6b7280'],
                    borderWidth: 0,
                }]
            },
            options: {
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 11 }, usePointStyle: true, padding: 14 } }
                }
            }
        });
    }

    const balanceEl = document.getElementById('chart-balance');
    if (balanceEl && mLabels.length) {
        new Chart(balanceEl, {
            type: 'bar',
            data: {
                labels: mLabels,
                datasets: [
                    {
                        label: 'Ingresos',
                        data: ingresos,
                        backgroundColor: 'rgba(22,163,74,.75)',
                        borderRadius: 6,
                        order: 1,
                    },
                    {
                        label: 'Egresos',
                        data: egresos,
                        backgroundColor: 'rgba(220,53,69,.65)',
                        borderRadius: 6,
                        order: 1,
                    },
                    {
                        label: 'Tendencia ingresos',
                        data: ingresos,
                        type: 'line',
                        borderColor: '#16a34a',
                        backgroundColor: 'transparent',
                        tension: .35,
                        pointRadius: 3,
                        order: 0,
                    }
                ],
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, usePointStyle: true, padding: 14 } } },
                scales: { y: { beginAtZero: true, grid: { color: '#f1f5fb' }, ticks: { font: { size: 11 } } }, x: { grid: { display: false }, ticks: { font: { size: 11 } } } }
            }
        });
    }
})();
</script>
@endsection

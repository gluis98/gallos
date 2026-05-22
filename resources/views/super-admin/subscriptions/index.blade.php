@extends('layouts.app')

@section('styles')
<style>
    .filter-tabs { display:flex; gap:.4rem; flex-wrap:wrap; margin-bottom:1rem; }
    .ftab { border:1px solid #e8eef8; background:#fff; border-radius:.6rem; padding:.35rem .85rem; font-size:.78rem; font-weight:600; color:#60708d; cursor:pointer; text-decoration:none; transition:all .15s; }
    .ftab:hover { border-color:#93c5fd; color:#1d4ed8; }
    .ftab.active { background:linear-gradient(95deg,#3b82f6,#6d5efc); color:#fff; border-color:transparent; }
    .ftab .cnt { background:rgba(0,0,0,.1); border-radius:10px; padding:.05rem .4rem; font-size:.68rem; margin-left:.3rem; }
    .ftab.active .cnt { background:rgba(255,255,255,.25); }
    .sub-row { transition:background .12s; }
    .sub-row:hover { background:#f8faff; }
    .plan-pro  { background:linear-gradient(135deg,#fef9c3,#fde68a); color:#854d0e; }
    .plan-free { background:#f3f4f6; color:#4b5563; }
    .plan-chip { font-size:.7rem; font-weight:800; padding:.2rem .6rem; border-radius:.4rem; text-transform:uppercase; letter-spacing:.05em; }
    .status-active   { background:#dcfce7; color:#166534; }
    .status-cancelled{ background:#fee2e2; color:#991b1b; }
    .status-expired  { background:#fff7ed; color:#9a3412; }
    .status-chip { font-size:.7rem; font-weight:700; padding:.15rem .55rem; border-radius:.4rem; }
    .days-left-ok     { color:#16a34a; }
    .days-left-warn   { color:#d97706; }
    .days-left-danger { color:#dc2626; }
    .stat-mini { text-align:center; }
    .stat-mini .v { font-weight:800; font-size:1rem; color:#1a2648; }
    .stat-mini .l { font-size:.68rem; color:#60708d; }
    .action-form { display:inline; }
    .btn-sa { font-size:.75rem; padding:.28rem .7rem; border-radius:.5rem; border:1px solid; cursor:pointer; font-weight:600; transition:all .12s; }
    .btn-sa-green  { background:#f0fdf4; border-color:#86efac; color:#16a34a; }
    .btn-sa-green:hover  { background:#dcfce7; }
    .btn-sa-red    { background:#fff1f2; border-color:#fca5a5; color:#dc2626; }
    .btn-sa-red:hover    { background:#fee2e2; }
    .btn-sa-blue   { background:#eff6ff; border-color:#93c5fd; color:#1d4ed8; }
    .btn-sa-blue:hover   { background:#dbeafe; }
    .extend-form { display:flex; gap:.3rem; align-items:center; }
    .months-input { width:60px; border:1px solid #d1d5db; border-radius:.5rem; padding:.28rem .5rem; font-size:.78rem; text-align:center; }
</style>
@endsection

@section('content')
<section class="section-card p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h2 class="section-title">Suscripciones</h2>
            <p class="section-subtitle">Gestiona planes, activa Pro manualmente y monitorea vencimientos.</p>
        </div>
        <form method="GET" class="d-flex gap-2" style="max-width:280px;">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input type="text" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Buscar usuario...">
            <button type="submit" class="btn btn-primary-soft btn-sm">Buscar</button>
        </form>
    </div>

    @if(session('ok'))
    <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:.75rem;padding:.85rem 1rem;margin-bottom:1rem;font-size:.85rem;color:#166534;font-weight:600;">
        ✅ {{ session('ok') }}
    </div>
    @endif

    {{-- Stats rápidas --}}
    <div class="row g-2 mb-3">
        <div class="col-3"><div class="stat-mini p-2 rounded-3" style="background:#f0fdf4;border:1px solid #bbf7d0;"><div class="v">{{ $stats['pro'] }}</div><div class="l">Pro activas</div></div></div>
        <div class="col-3"><div class="stat-mini p-2 rounded-3" style="background:#f3f4f6;border:1px solid #e5e7eb;"><div class="v">{{ $stats['free'] }}</div><div class="l">Free</div></div></div>
        <div class="col-3"><div class="stat-mini p-2 rounded-3" style="background:#fef9c3;border:1px solid #fde68a;"><div class="v">{{ $stats['expiring'] }}</div><div class="l">Por vencer</div></div></div>
        <div class="col-3"><div class="stat-mini p-2 rounded-3" style="background:#fff7ed;border:1px solid #fed7aa;"><div class="v">{{ $stats['expired'] }}</div><div class="l">Vencidas</div></div></div>
    </div>

    {{-- Filtros --}}
    <div class="filter-tabs">
        <a href="{{ route('superadmin.subscriptions.index') }}" class="ftab {{ $filter === 'all' ? 'active' : '' }}">Todas</a>
        <a href="{{ route('superadmin.subscriptions.index') }}?filter=pro" class="ftab {{ $filter === 'pro' ? 'active' : '' }}">Pro activas <span class="cnt">{{ $stats['pro'] }}</span></a>
        <a href="{{ route('superadmin.subscriptions.index') }}?filter=free" class="ftab {{ $filter === 'free' ? 'active' : '' }}">Free</a>
        <a href="{{ route('superadmin.subscriptions.index') }}?filter=expiring" class="ftab {{ $filter === 'expiring' ? 'active' : '' }}">⚠️ Por vencer <span class="cnt">{{ $stats['expiring'] }}</span></a>
        <a href="{{ route('superadmin.subscriptions.index') }}?filter=expired" class="ftab {{ $filter === 'expired' ? 'active' : '' }}">Vencidas <span class="cnt">{{ $stats['expired'] }}</span></a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead style="font-size:.75rem;color:#60708d;text-transform:uppercase;letter-spacing:.04em;background:#f8faff;">
                <tr>
                    <th class="ps-3">Usuario</th>
                    <th>Plan</th>
                    <th>Estado</th>
                    <th class="text-center">🐓 Gallos</th>
                    <th class="text-center">🥚 Gallinas</th>
                    <th class="text-center">Vence</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($subscriptions as $sub)
            @php
                $daysLeft = $sub->ends_at ? (int) now()->diffInDays($sub->ends_at, false) : null;
                $daysClass = $daysLeft === null ? '' : ($daysLeft <= 7 ? 'days-left-danger' : ($daysLeft <= 30 ? 'days-left-warn' : 'days-left-ok'));
                $statusChip = match($sub->status) {
                    'active'    => $sub->ends_at && $sub->ends_at->isPast() ? 'status-expired' : 'status-active',
                    'cancelled' => 'status-cancelled',
                    default     => 'status-expired',
                };
                $statusLabel = $sub->ends_at && $sub->ends_at->isPast() && $sub->status === 'active' ? 'Vencida' : ucfirst($sub->status);
            @endphp
            <tr class="sub-row">
                <td class="ps-3">
                    @if($sub->user)
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#6d5efc);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.9rem;flex-shrink:0;">
                            {{ strtoupper(substr($sub->user->name,0,1)) }}
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:.87rem;">{{ $sub->user->name }}</div>
                            <div style="font-size:.72rem;color:#60708d;">{{ $sub->user->email }}</div>
                        </div>
                    </div>
                    @else
                    <span style="font-size:.8rem;color:#9baac7;">Tenant: {{ Str::limit($sub->tenant_id,14,'…') }}</span>
                    @endif
                </td>
                <td><span class="plan-chip {{ $sub->plan === 'pro' ? 'plan-pro' : 'plan-free' }}">{{ strtoupper($sub->plan) }}</span></td>
                <td><span class="status-chip {{ $statusChip }}">{{ $statusLabel }}</span></td>
                <td class="text-center" style="font-size:.83rem;font-weight:700;">{{ $sub->gallos_count }}</td>
                <td class="text-center" style="font-size:.83rem;font-weight:700;">{{ $sub->gallinas_count }}</td>
                <td class="text-center" style="font-size:.8rem;">
                    @if($sub->ends_at)
                        <div style="font-weight:600;">{{ $sub->ends_at->format('d/m/Y') }}</div>
                        <div class="small {{ $daysClass }}">
                            {{ $daysLeft >= 0 ? $daysLeft.'d restantes' : abs($daysLeft).'d vencida' }}
                        </div>
                    @else <span class="text-muted">—</span> @endif
                </td>
                <td>
                    <div class="d-flex gap-1 flex-wrap">
                        {{-- Activar/Extender --}}
                        <form action="{{ route('superadmin.subscriptions.activate', $sub->id) }}" method="POST" class="action-form">
                            @csrf
                            <div class="extend-form">
                                <input type="number" name="months" value="12" min="1" max="36" class="months-input" title="Meses a activar/extender">
                                <button type="submit" class="btn-sa btn-sa-green">⬆ Pro</button>
                            </div>
                        </form>
                        {{-- Cancelar --}}
                        @if($sub->status === 'active')
                        <form action="{{ route('superadmin.subscriptions.cancel', $sub->id) }}" method="POST" class="action-form" onsubmit="return confirm('¿Cancelar esta suscripción?')">
                            @csrf
                            <button type="submit" class="btn-sa btn-sa-red">Cancelar</button>
                        </form>
                        @endif
                        {{-- Ver usuario --}}
                        @if($sub->user)
                        <a href="{{ route('superadmin.users.show', $sub->user->id) }}" class="btn-sa btn-sa-blue text-decoration-none">Ver datos</a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted py-5">Sin suscripciones para mostrar.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-3">
        {{ $subscriptions->links() }}
    </div>
</section>
@endsection

@extends('layouts.app')

@section('styles')
<style>
    .detail-header { background: linear-gradient(135deg, #1a2648, #2a3a6a); border-radius: 1rem; padding: 1.5rem 2rem; color: #fff; margin-bottom: 1.5rem; }
    .detail-avatar { width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #f97316); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.8rem; flex-shrink: 0; border: 3px solid rgba(255,255,255,.3); }
    .detail-section { background: #fff; border: 1px solid #e8eef8; border-radius: 1rem; padding: 1.25rem 1.5rem; margin-bottom: 1.25rem; box-shadow: 0 4px 16px rgba(16,39,77,.05); }
    .detail-section h6 { font-weight: 700; color: #1a2648; margin-bottom: 1rem; display: flex; align-items: center; gap: .5rem; }
    .detail-section h6 .material-symbols-outlined { font-size: 1.1rem; }
    .stat-box { text-align: center; padding: 1rem; border: 1px solid #e8eef8; border-radius: .85rem; }
    .stat-box-val { font-size: 1.8rem; font-weight: 800; }
    .stat-box-lbl { font-size: .78rem; color: #60708d; margin-top: .15rem; }
    .data-table { width: 100%; font-size: .83rem; }
    .data-table th { background: #f8faff; color: #60708d; font-weight: 600; padding: .6rem .85rem; font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; }
    .data-table td { padding: .55rem .85rem; border-bottom: 1px solid #f0f4fc; }
    .data-table tr:last-child td { border-bottom: none; }
    .badge-activo { background: #dcfce7; color: #166534; }
    .badge-inactivo, .badge-fallecido, .badge-fallecida { background: #fee2e2; color: #991b1b; }
    .badge-vendido { background: #dbeafe; color: #1d4ed8; }
    .status-pill { font-size: .72rem; border-radius: .4rem; padding: .15rem .55rem; font-weight: 700; }
</style>
@endsection

@section('content')
<div class="mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
    <a href="{{ route('superadmin.users.index') }}" style="display:inline-flex;align-items:center;gap:.35rem;font-size:.82rem;color:#60708d;text-decoration:none;font-weight:600;">
        <span class="material-symbols-outlined" style="font-size:1rem;">arrow_back</span> Volver a Usuarios
    </a>
    <a href="{{ route('superadmin.users.galpones', $user->id) }}" class="btn btn-primary-soft btn-sm">
        <span class="material-symbols-outlined float-start me-1" style="font-size:1rem;">warehouse</span> Galpones extras
    </a>
</div>

<!-- Header usuario -->
<div class="detail-header">
    <div class="d-flex align-items-center gap-3 flex-wrap">
        <div class="detail-avatar">{{ strtoupper(substr($user->name,0,1)) }}</div>
        <div class="flex-grow-1">
            <h3 class="mb-0 fw-bold" style="color:#fff;">{{ $user->name }}</h3>
            <div style="color:rgba(255,255,255,.75);font-size:.9rem;">{{ $user->email }}</div>
            <div class="d-flex gap-2 flex-wrap mt-2">
                @if($user->tenant_id)
                    <span style="background:rgba(255,255,255,.15);color:#fff;font-size:.75rem;border-radius:.4rem;padding:.2rem .65rem;">Tenant: {{ $user->tenant_id }}</span>
                @endif
                <span style="background:rgba(255,255,255,.15);color:#fff;font-size:.75rem;border-radius:.4rem;padding:.2rem .65rem;">Registrado: {{ optional($user->created_at)->format('d/m/Y H:i') ?? '—' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Stats resumen -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-box">
            <div class="stat-box-val" style="color:#3b82f6;">{{ $gallos->count() }}</div>
            <div class="stat-box-lbl">🐓 Gallos</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box">
            <div class="stat-box-val" style="color:#7e22ce;">{{ $gallinas->count() }}</div>
            <div class="stat-box-lbl">🥚 Gallinas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box">
            <div class="stat-box-val" style="color:#166534;">{{ $ventas->count() }}</div>
            <div class="stat-box-lbl">💰 Ventas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box">
            <div class="stat-box-val" style="color:#9a3412;">{{ $compras->count() }}</div>
            <div class="stat-box-lbl">🛒 Compras</div>
        </div>
    </div>
</div>

<!-- Gallos -->
<div class="detail-section">
    <h6><span class="material-symbols-outlined">pets</span> Gallos registrados ({{ $gallos->count() }})</h6>
    @if($gallos->isEmpty())
        <p class="text-muted small mb-0">Sin gallos registrados.</p>
    @else
    <div class="table-responsive">
        <table class="data-table">
            <thead><tr><th>#</th><th>Placa</th><th>Nombre</th><th>Color</th><th>Peleas</th><th>Estatus</th><th>Registrado</th></tr></thead>
            <tbody>
            @foreach($gallos as $g)
            <tr>
                <td style="color:#aab4c8;">{{ $g->id }}</td>
                <td class="fw-semibold">{{ $g->placa }}</td>
                <td>{{ $g->nombre ?: '—' }}</td>
                <td>{{ $g->color ?: '—' }}</td>
                <td>{{ $g->peleas ?? 0 }}</td>
                <td><span class="status-pill badge-{{ strtolower($g->estatus ?? 'inactivo') }}">{{ $g->estatus ?? '—' }}</span></td>
                <td style="color:#60708d;">{{ $g->created_at ? \Carbon\Carbon::parse($g->created_at)->format('d/m/Y') : '—' }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<!-- Gallinas -->
<div class="detail-section">
    <h6><span class="material-symbols-outlined">egg</span> Gallinas registradas ({{ $gallinas->count() }})</h6>
    @if($gallinas->isEmpty())
        <p class="text-muted small mb-0">Sin gallinas registradas.</p>
    @else
    <div class="table-responsive">
        <table class="data-table">
            <thead><tr><th>#</th><th>Placa</th><th>Nombre</th><th>Color</th><th>Estatus</th><th>Registrada</th></tr></thead>
            <tbody>
            @foreach($gallinas as $g)
            <tr>
                <td style="color:#aab4c8;">{{ $g->id }}</td>
                <td class="fw-semibold">{{ $g->placa }}</td>
                <td>{{ $g->nombre ?: '—' }}</td>
                <td>{{ $g->color ?: '—' }}</td>
                <td><span class="status-pill badge-{{ strtolower(str_replace(' ','',($g->estatus ?? 'inactiva'))) }}">{{ $g->estatus ?? '—' }}</span></td>
                <td style="color:#60708d;">{{ $g->created_at ? \Carbon\Carbon::parse($g->created_at)->format('d/m/Y') : '—' }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<!-- Ventas recientes -->
<div class="detail-section">
    <h6><span class="material-symbols-outlined">sell</span> Ventas recientes ({{ $ventas->count() }})</h6>
    @if($ventas->isEmpty())
        <p class="text-muted small mb-0">Sin ventas registradas.</p>
    @else
    <div class="table-responsive">
        <table class="data-table">
            <thead><tr><th>#</th><th>Gallo ID</th><th>Cliente</th><th>Monto</th><th>Fecha</th></tr></thead>
            <tbody>
            @foreach($ventas as $v)
            <tr>
                <td style="color:#aab4c8;">{{ $v->id }}</td>
                <td>{{ $v->gallo_id }}</td>
                <td>{{ $v->nombre_cliente ?: '—' }}</td>
                <td>{{ $v->monto ? '$'.number_format($v->monto,2) : '—' }}</td>
                <td style="color:#60708d;">{{ $v->created_at ? \Carbon\Carbon::parse($v->created_at)->format('d/m/Y') : '—' }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<!-- Compras recientes -->
<div class="detail-section">
    <h6><span class="material-symbols-outlined">shopping_cart</span> Compras recientes ({{ $compras->count() }})</h6>
    @if($compras->isEmpty())
        <p class="text-muted small mb-0">Sin compras registradas.</p>
    @else
    <div class="table-responsive">
        <table class="data-table">
            <thead><tr><th>#</th><th>Total</th><th>Estatus</th><th>Fecha</th></tr></thead>
            <tbody>
            @foreach($compras as $c)
            <tr>
                <td style="color:#aab4c8;">{{ $c->id }}</td>
                <td>{{ isset($c->total) ? '$'.number_format($c->total,2) : '—' }}</td>
                <td>{{ $c->estatus ?? '—' }}</td>
                <td style="color:#60708d;">{{ $c->created_at ? \Carbon\Carbon::parse($c->created_at)->format('d/m/Y') : '—' }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection

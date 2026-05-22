@extends('layouts.app')

@section('styles')
<style>
    .sa-stat-card {
        background: #fff;
        border: 1px solid #e8eef8;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 4px 16px rgba(16,39,77,.06);
        display: flex; align-items: center; gap: 1rem;
    }
    .sa-stat-icon {
        width: 48px; height: 48px; border-radius: .85rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .sa-stat-icon .material-symbols-outlined { font-size: 1.5rem; }
    .sa-stat-val { font-size: 1.5rem; font-weight: 800; line-height: 1; color: var(--app-text); }
    .sa-stat-lbl { font-size: .78rem; color: var(--app-muted); margin-top: .1rem; }
    .user-row { transition: background .15s; }
    .user-row:hover { background: #f8faff; }
    .tenant-badge { font-size: .72rem; background: #eff6ff; color: #1d4ed8; border-radius: .4rem; padding: .15rem .55rem; font-weight: 600; }
    .plan-badge-free { background: #f3f4f6; color: #6b7280; }
    .plan-badge-pro  { background: #dcfce7; color: #166534; }
    .plan-badge { font-size: .72rem; border-radius: .4rem; padding: .15rem .6rem; font-weight: 700; }
    .stat-pill { display: inline-flex; align-items: center; gap: .25rem; font-size: .78rem; font-weight: 600; border-radius: .5rem; padding: .2rem .55rem; }
    .pill-gallos  { background: #eff6ff; color: #1d4ed8; }
    .pill-gallinas{ background: #fdf4ff; color: #7e22ce; }
    .pill-ventas  { background: #f0fdf4; color: #166534; }
    .pill-compras { background: #fff7ed; color: #9a3412; }
</style>
@endsection

@section('content')
<section class="section-card p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="section-title">Control de Usuarios</h2>
            <p class="section-subtitle">Vista super-administrador — datos completos de cada usuario registrado.</p>
        </div>
        <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-outline-soft btn-sm">
            <span class="material-symbols-outlined float-start me-1" style="font-size:1rem;">business</span> Tenants
        </a>
    </div>

    @php
        $totalUsers    = $users->count();
        $totalGallos   = $gallosCounts->sum();
        $totalGallinas = $gallinasCounts->sum();
        $totalVentas   = $ventasCounts->sum();
    @endphp

    <!-- Stats rápidas -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="sa-stat-card">
                <div class="sa-stat-icon" style="background:#eff6ff;">
                    <span class="material-symbols-outlined" style="color:#3b82f6;">group</span>
                </div>
                <div>
                    <div class="sa-stat-val">{{ $totalUsers }}</div>
                    <div class="sa-stat-lbl">Usuarios</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="sa-stat-card">
                <div class="sa-stat-icon" style="background:#eff6ff;">
                    <span class="material-symbols-outlined" style="color:#1d4ed8;">pets</span>
                </div>
                <div>
                    <div class="sa-stat-val">{{ $totalGallos }}</div>
                    <div class="sa-stat-lbl">Gallos registrados</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="sa-stat-card">
                <div class="sa-stat-icon" style="background:#fdf4ff;">
                    <span class="material-symbols-outlined" style="color:#7e22ce;">egg</span>
                </div>
                <div>
                    <div class="sa-stat-val">{{ $totalGallinas }}</div>
                    <div class="sa-stat-lbl">Gallinas registradas</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="sa-stat-card">
                <div class="sa-stat-icon" style="background:#f0fdf4;">
                    <span class="material-symbols-outlined" style="color:#166534;">sell</span>
                </div>
                <div>
                    <div class="sa-stat-val">{{ $totalVentas }}</div>
                    <div class="sa-stat-lbl">Ventas totales</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Búsqueda -->
    <div class="mb-3">
        <input type="text" id="search-users" class="form-control" placeholder="Buscar por nombre, email o tenant..." style="max-width:380px;">
    </div>

    <!-- Tabla de usuarios -->
    <div class="table-responsive">
        <table class="table align-middle" id="tabla-usuarios">
            <thead>
                <tr style="background:#f8faff;font-size:.82rem;color:#60708d;text-transform:uppercase;letter-spacing:.04em;">
                    <th class="ps-3">Usuario</th>
                    <th>Tenant ID</th>
                    <th class="text-center">Gallos</th>
                    <th class="text-center">Gallinas</th>
                    <th class="text-center">Ventas</th>
                    <th class="text-center">Compras</th>
                    <th>Multi-galpón</th>
                    <th>Registrado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
            @php
                $tid = $user->tenant_id;
                $gc  = $gallosCounts[$tid]   ?? 0;
                $gac = $gallinasCounts[$tid]  ?? 0;
                $vc  = $ventasCounts[$tid]    ?? 0;
                $cc  = $comprasCounts[$tid]   ?? 0;
            @endphp
            <tr class="user-row" data-search="{{ strtolower($user->name . ' ' . $user->email . ' ' . $user->tenant_id) }}">
                <td class="ps-3">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#6d5efc);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.95rem;flex-shrink:0;">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:.9rem;">{{ $user->name }}</div>
                            <div style="font-size:.78rem;color:#60708d;">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    @if($tid)
                        <span class="tenant-badge" title="{{ $tid }}">{{ Str::limit($tid, 8, '…') }}</span>
                    @else
                        <span class="text-muted" style="font-size:.8rem;">Sin tenant</span>
                    @endif
                </td>
                <td class="text-center"><span class="stat-pill pill-gallos">🐓 {{ $gc }}</span></td>
                <td class="text-center"><span class="stat-pill pill-gallinas">🥚 {{ $gac }}</span></td>
                <td class="text-center"><span class="stat-pill pill-ventas">💰 {{ $vc }}</span></td>
                <td class="text-center"><span class="stat-pill pill-compras">🛒 {{ $cc }}</span></td>
                <td>
                    @if($user->extra_galpones_enabled)
                        <span class="stat-pill" style="background:#dcfce7;color:#166534;">✓ Activo</span>
                    @else
                        <span class="text-muted" style="font-size:.78rem;">—</span>
                    @endif
                </td>
                <td style="font-size:.82rem;color:#60708d;">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}</td>
                <td class="text-nowrap">
                    <a href="{{ route('superadmin.users.galpones', $user->id) }}" class="btn btn-sm btn-outline-soft me-1" style="font-size:.78rem;padding:.3rem .65rem;" title="Galpones extras">
                        <span class="material-symbols-outlined float-start me-1" style="font-size:.9rem;">warehouse</span> Galpones
                    </a>
                    <a href="{{ route('superadmin.users.show', $user->id) }}" class="btn btn-sm btn-primary-soft" style="font-size:.78rem;padding:.3rem .75rem;">
                        <span class="material-symbols-outlined float-start me-1" style="font-size:.9rem;">visibility</span> Ver
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center text-muted py-5">No hay usuarios registrados.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.getElementById('search-users').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#tabla-usuarios tbody tr').forEach(row => {
        const txt = row.getAttribute('data-search') || '';
        row.style.display = txt.includes(q) ? '' : 'none';
    });
});
</script>
@endsection

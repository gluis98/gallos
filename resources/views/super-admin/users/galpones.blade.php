@extends('layouts.app')

@section('styles')
<style>
    .galpon-hero {
        background: linear-gradient(135deg, #1a2648 0%, #2d4278 55%, #1a3a2a 100%);
        border-radius: 1rem; padding: 1.5rem 2rem; color: #fff; margin-bottom: 1.5rem;
    }
    .galpon-card {
        background: #fff; border: 1px solid #e8eef8; border-radius: 1rem;
        padding: 1.25rem 1.5rem; margin-bottom: 1rem; box-shadow: 0 4px 16px rgba(16,39,77,.05);
    }
    .galpon-pill {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .72rem; font-weight: 700; border-radius: .45rem; padding: .2rem .6rem;
    }
    .pill-primary { background: #dbeafe; color: #1d4ed8; }
    .pill-extra   { background: #fef3c7; color: #92400e; }
    .pill-on      { background: #dcfce7; color: #166534; }
    .pill-off     { background: #f3f4f6; color: #6b7280; }
    .feature-toggle {
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        padding: 1rem 1.25rem; background: #f8faff; border: 1px solid #e8eef8; border-radius: .85rem;
    }
</style>
@endsection

@section('content')
<div class="mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
    <a href="{{ route('superadmin.users.index') }}" style="display:inline-flex;align-items:center;gap:.35rem;font-size:.82rem;color:#60708d;text-decoration:none;font-weight:600;">
        <span class="material-symbols-outlined" style="font-size:1rem;">arrow_back</span> Usuarios
    </a>
    <a href="{{ route('superadmin.users.show', $user->id) }}" class="btn btn-outline-soft btn-sm">Ver datos del usuario</a>
</div>

@if(session('ok'))
<div class="alert alert-success border-0" style="border-radius:.85rem;">{{ session('ok') }}</div>
@endif
@if($errors->any())
<div class="alert alert-danger border-0" style="border-radius:.85rem;">
    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<div class="galpon-hero">
    <div class="d-flex align-items-center gap-3 flex-wrap">
        <div style="width:52px;height:52px;border-radius:1rem;background:rgba(34,197,94,.2);display:flex;align-items:center;justify-content:center;border:1px solid rgba(34,197,94,.35);">
            <span class="material-symbols-outlined" style="font-size:1.5rem;color:#4ade80;">warehouse</span>
        </div>
        <div>
            <h3 class="mb-0 fw-bold">Galpones extras — {{ $user->name }}</h3>
            <div style="color:rgba(255,255,255,.75);font-size:.88rem;">{{ $user->email }}</div>
        </div>
    </div>
</div>

<div class="galpon-card">
    <h5 class="fw-bold mb-3" style="color:#1a2648;">
        <span class="material-symbols-outlined float-start me-1" style="font-size:1.1rem;color:#3b82f6;">toggle_on</span>
        Función multi-galpón
    </h5>
    <p class="text-muted small mb-3">
        Solo si activas esta función el usuario verá un selector en su panel para cambiar entre galpones.
        Cada galpón extra tiene datos independientes (gallos, gallinas, ventas, suscripción).
    </p>
    <div class="feature-toggle">
        <div>
            <div class="fw-semibold">Estado</div>
            <div class="small text-muted">
                @if($user->extra_galpones_enabled)
                    Activo — el usuario puede tener varios galpones asignados.
                @else
                    Inactivo — el usuario solo usa su galpón principal del registro.
                @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            @if($user->extra_galpones_enabled)
                <span class="galpon-pill pill-on"><span class="material-symbols-outlined" style="font-size:.85rem;">check_circle</span> Habilitado</span>
                <form method="post" action="{{ route('superadmin.users.galpones.disable', $user->id) }}" onsubmit="return confirm('¿Desactivar galpones extras? El usuario volverá a ver solo su galpón principal.');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">Desactivar</button>
                </form>
            @else
                <span class="galpon-pill pill-off">Desactivado</span>
                <form method="post" action="{{ route('superadmin.users.galpones.enable', $user->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary-soft">Activar para este usuario</button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="galpon-card">
    <h5 class="fw-bold mb-3" style="color:#1a2648;">
        <span class="material-symbols-outlined float-start me-1" style="font-size:1.1rem;color:#f59e0b;">home</span>
        Galpón principal (registro)
    </h5>
    @if($primaryTenant)
        <div class="d-flex flex-wrap align-items-center gap-2">
            <strong>{{ $primaryTenant->name ?: 'Sin nombre' }}</strong>
            <span class="galpon-pill pill-primary">Principal</span>
            <code style="font-size:.72rem;color:#60708d;">{{ Str::limit($primaryTenant->id, 12, '…') }}</code>
        </div>
    @else
        <p class="text-muted small mb-0">Este usuario no tiene galpón principal asignado.</p>
    @endif
</div>

<div class="galpon-card">
    <h5 class="fw-bold mb-3" style="color:#1a2648;">
        <span class="material-symbols-outlined float-start me-1" style="font-size:1.1rem;color:#22c55e;">add_home</span>
        Galpones extras asignados ({{ $extraGalpones->count() }})
    </h5>

    @if($extraGalpones->isEmpty())
        <p class="text-muted small mb-0">Aún no hay galpones extras. Activa la función y crea el primero con el formulario de abajo.</p>
    @else
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:.85rem;">
                <thead>
                    <tr style="background:#f8faff;color:#60708d;font-size:.75rem;text-transform:uppercase;">
                        <th class="ps-2">Nombre</th>
                        <th>Tenant ID</th>
                        <th>Asignado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($extraGalpones as $extra)
                    <tr>
                        <td class="ps-2">
                            <strong>{{ $extra->displayName() }}</strong>
                            @if($extra->tenant?->status === 'suspended')
                                <span class="galpon-pill" style="background:#fee2e2;color:#991b1b;">Suspendido</span>
                            @endif
                        </td>
                        <td><code style="font-size:.72rem;">{{ Str::limit($extra->tenant_id, 14, '…') }}</code></td>
                        <td style="color:#60708d;">{{ $extra->created_at?->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <form method="post" action="{{ route('superadmin.users.galpones.destroy', [$user->id, $extra->id]) }}" class="d-inline" onsubmit="return confirm('¿Quitar acceso a este galpón? Los datos del galpón no se borran.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size:.75rem;">Quitar acceso</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@if($user->extra_galpones_enabled)
<div class="galpon-card">
    <h5 class="fw-bold mb-3" style="color:#1a2648;">
        <span class="material-symbols-outlined float-start me-1" style="font-size:1.1rem;color:#3b82f6;">add</span>
        Crear nuevo galpón extra
    </h5>
    <form method="post" action="{{ route('superadmin.users.galpones.store', $user->id) }}" class="row g-3">
        @csrf
        <div class="col-md-5">
            <label class="form-label small fw-semibold">Nombre del galpón <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required maxlength="255" placeholder="Ej: Galpón La Vega" value="{{ old('name') }}">
        </div>
        <div class="col-md-5">
            <label class="form-label small fw-semibold">Etiqueta en selector (opcional)</label>
            <input type="text" name="label" class="form-control" maxlength="255" placeholder="Ej: La Vega — Sucursal 2" value="{{ old('label') }}">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary-soft w-100">
                <span class="material-symbols-outlined float-start me-1" style="font-size:1rem;">add</span> Crear
            </button>
        </div>
    </form>
    <p class="text-muted small mt-2 mb-0">Se creará un tenant nuevo con plan gratuito y datos vacíos, independiente del galpón principal.</p>
</div>
@endif
@endsection

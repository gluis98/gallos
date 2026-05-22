@extends('layouts.app')

@section('content')
<section class="section-card p-3 p-md-4">
    <h2 class="section-title">Cuentas</h2>
    <p class="section-subtitle mb-3">Administra las cuentas (organizaciones) registradas en el sistema.</p>
    <form method="post" action="{{ route('superadmin.tenants.store') }}" class="row g-2 mb-3">
        @csrf
        <div class="col-md-6">
            <input type="text" name="name" class="form-control" placeholder="Nombre del tenant" required>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" type="submit">Crear</button>
        </div>
    </form>
    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif
    <div class="table-responsive mt-3">
    <table class="table table-hover align-middle">
        <thead style="background:#f8faff;font-size:.82rem;color:#60708d;">
            <tr><th>ID</th><th>Nombre</th><th>Estado</th><th></th></tr>
        </thead>
        <tbody>
        @foreach($tenants as $t)
            <tr>
                <td><code style="font-size:.78rem;color:#60708d;">{{ Str::limit($t->id, 18, '…') }}</code></td>
                <td class="fw-semibold">{{ $t->name ?? '—' }}</td>
                <td>
                    @if(($t->status ?? '') === 'active')
                        <span style="background:#dcfce7;color:#166534;font-size:.72rem;font-weight:700;padding:.2rem .6rem;border-radius:.4rem;">Activo</span>
                    @else
                        <span style="background:#fee2e2;color:#991b1b;font-size:.72rem;font-weight:700;padding:.2rem .6rem;border-radius:.4rem;">Suspendido</span>
                    @endif
                </td>
                <td class="d-flex gap-1">
                    @if(($t->status ?? '') !== 'suspended')
                        <form method="post" action="{{ route('superadmin.tenants.suspend', $t->id) }}">@csrf<button class="btn btn-sm btn-warning">Suspender</button></form>
                    @else
                        <form method="post" action="{{ route('superadmin.tenants.activate', $t->id) }}">@csrf<button class="btn btn-sm btn-success">Activar</button></form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</section>
@endsection

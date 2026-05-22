@extends('layouts.app')

@section('styles')
<style>
    .status-chip { font-size:.7rem; font-weight:800; padding:.2rem .6rem; border-radius:.4rem; text-transform:uppercase; letter-spacing:.03em; }
    .s-pendiente  { background:#fef9c3; color:#854d0e; }
    .s-verificado { background:#dcfce7; color:#166534; }
    .s-rechazado  { background:#fee2e2; color:#991b1b; }
    .method-chip  { font-size:.72rem; font-weight:700; padding:.15rem .55rem; border-radius:.4rem; }
    .m-zelle      { background:#dbeafe; color:#1d4ed8; }
    .m-pagomovil  { background:#f5f3ff; color:#6d28d9; }
    .m-usdt       { background:#fefce8; color:#854d0e; }
    .pay-row { transition:background .12s; }
    .pay-row:hover { background:#f8faff; }
    .proof-thumb { width:48px; height:48px; border-radius:.5rem; object-fit:cover; border:1px solid #e8eef8; cursor:pointer; }
    .btn-action { font-size:.75rem; padding:.28rem .7rem; border-radius:.5rem; border:1px solid; cursor:pointer; font-weight:600; transition:all .12s; }
    .btn-verify { background:#f0fdf4; border-color:#86efac; color:#16a34a; }
    .btn-verify:hover { background:#dcfce7; }
    .btn-reject { background:#fff1f2; border-color:#fca5a5; color:#dc2626; }
    .btn-reject:hover { background:#fee2e2; }
    .filter-bar { display:flex; gap:.4rem; flex-wrap:wrap; margin-bottom:1rem; }
    .fb-chip { border:1px solid #e8eef8; background:#fff; border-radius:.55rem; padding:.3rem .75rem; font-size:.78rem; font-weight:600; color:#60708d; cursor:pointer; text-decoration:none; transition:all .12s; }
    .fb-chip:hover { border-color:#93c5fd; color:#1d4ed8; }
    .fb-chip.active { background:linear-gradient(95deg,#3b82f6,#6d5efc); color:#fff; border-color:transparent; }
    .amount-big { font-weight:800; color:#16a34a; }
</style>
@endsection

@section('content')
<section class="section-card p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h2 class="section-title">Órdenes de pago</h2>
            <p class="section-subtitle">Revisa y verifica comprobantes para activar el Plan Pro.</p>
        </div>
    </div>

    @if(session('ok'))
    <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:.75rem;padding:.85rem 1rem;margin-bottom:1rem;font-size:.85rem;color:#166534;font-weight:600;">
        ✅ {{ session('ok') }}
    </div>
    @endif

    @php
        $pendienteCount  = $orders->where('status','pendiente')->count();
        $verificadoCount = $orders->where('status','verificado')->count();
        $rechazadoCount  = $orders->where('status','rechazado')->count();
        $totalRev = $orders->where('status','verificado')->sum('amount');
        $filtro = request('status','');
        $filtered = $filtro ? $orders->where('status',$filtro) : $orders;
    @endphp

    {{-- Stats --}}
    <div class="row g-2 mb-3">
        <div class="col-4"><div style="text-align:center;padding:.75rem;background:#fef9c3;border:1px solid #fde68a;border-radius:.85rem;"><div style="font-weight:900;font-size:1.2rem;color:#854d0e;">{{ $pendienteCount }}</div><div style="font-size:.72rem;color:#a16207;">Pendientes</div></div></div>
        <div class="col-4"><div style="text-align:center;padding:.75rem;background:#dcfce7;border:1px solid #bbf7d0;border-radius:.85rem;"><div style="font-weight:900;font-size:1.2rem;color:#166534;">{{ $verificadoCount }}</div><div style="font-size:.72rem;color:#15803d;">${{ number_format($totalRev,2) }} recaudados</div></div></div>
        <div class="col-4"><div style="text-align:center;padding:.75rem;background:#fee2e2;border:1px solid #fca5a5;border-radius:.85rem;"><div style="font-weight:900;font-size:1.2rem;color:#991b1b;">{{ $rechazadoCount }}</div><div style="font-size:.72rem;color:#dc2626;">Rechazados</div></div></div>
    </div>

    {{-- Filtros --}}
    <div class="filter-bar">
        <a href="{{ route('superadmin.payments.index') }}" class="fb-chip {{ !$filtro ? 'active' : '' }}">Todos ({{ $orders->count() }})</a>
        <a href="{{ route('superadmin.payments.index') }}?status=pendiente" class="fb-chip {{ $filtro === 'pendiente' ? 'active' : '' }}">⏳ Pendientes ({{ $pendienteCount }})</a>
        <a href="{{ route('superadmin.payments.index') }}?status=verificado" class="fb-chip {{ $filtro === 'verificado' ? 'active' : '' }}">✅ Verificados ({{ $verificadoCount }})</a>
        <a href="{{ route('superadmin.payments.index') }}?status=rechazado" class="fb-chip {{ $filtro === 'rechazado' ? 'active' : '' }}">❌ Rechazados ({{ $rechazadoCount }})</a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead style="font-size:.75rem;color:#60708d;text-transform:uppercase;letter-spacing:.04em;background:#f8faff;">
                <tr>
                    <th class="ps-3">#</th>
                    <th>Usuario / Tenant</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Estado</th>
                    <th>Referencia</th>
                    <th>Comprobante</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @php
                $displayOrders = $filtro ? $orders->where('status', $filtro) : $orders;
            @endphp
            @forelse($displayOrders as $o)
            @php
                $user = \App\Models\User::where('tenant_id', $o->tenant_id)->where('is_superadmin', false)->first();
                $methodClass = match($o->method) { 'zelle' => 'm-zelle', 'pagomovil' => 'm-pagomovil', default => 'm-usdt' };
                $methodLabel = match($o->method) { 'zelle' => '💳 Zelle', 'pagomovil' => '📱 Pago Móvil', 'usdt_binance' => '🪙 USDT', default => $o->method };
            @endphp
            <tr class="pay-row">
                <td class="ps-3" style="font-size:.8rem;color:#9baac7;">{{ $o->id }}</td>
                <td>
                    @if($user)
                    <div class="fw-semibold" style="font-size:.87rem;">{{ $user->name }}</div>
                    <div style="font-size:.72rem;color:#60708d;">{{ $user->email }}</div>
                    @else
                    <code style="font-size:.72rem;color:#9baac7;">{{ Str::limit($o->tenant_id,14,'…') }}</code>
                    @endif
                    @if($o->notes)<div style="font-size:.7rem;color:#9baac7;margin-top:.15rem;" title="{{ $o->notes }}">📝 {{ Str::limit($o->notes,40) }}</div>@endif
                </td>
                <td class="amount-big">${{ number_format($o->amount,2) }} <span style="font-size:.75rem;font-weight:500;color:#60708d;">{{ $o->currency }}</span></td>
                <td><span class="method-chip {{ $methodClass }}">{{ $methodLabel }}</span></td>
                <td><span class="status-chip s-{{ $o->status }}">{{ ucfirst($o->status) }}</span></td>
                <td style="font-size:.82rem;">{{ $o->reference ?: '—' }}</td>
                <td>
                    @if($o->proof_path)
                    <a href="{{ asset('storage/'.$o->proof_path) }}" target="_blank">
                        <img src="{{ asset('storage/'.$o->proof_path) }}" class="proof-thumb" alt="Comprobante" onerror="this.style.display='none'">
                    </a>
                    @else <span style="font-size:.75rem;color:#9baac7;">Sin comprobante</span> @endif
                </td>
                <td style="font-size:.78rem;color:#60708d;white-space:nowrap;">
                    {{ optional($o->created_at)->format('d/m/Y') }}<br>
                    <span style="font-size:.68rem;">{{ optional($o->created_at)->format('H:i') }}</span>
                </td>
                <td>
                    <div class="d-flex gap-1">
                    @if($o->status === 'pendiente')
                        <form method="post" action="{{ route('superadmin.payments.verify', $o->id) }}">
                            @csrf
                            <button type="submit" class="btn-action btn-verify" onclick="return confirm('¿Verificar este pago y activar Plan Pro?')">✅ Verificar</button>
                        </form>
                        <form method="post" action="{{ route('superadmin.payments.reject', $o->id) }}">
                            @csrf
                            <button type="submit" class="btn-action btn-reject" onclick="return confirm('¿Rechazar este pago?')">❌ Rechazar</button>
                        </form>
                    @else
                        <span style="font-size:.75rem;color:#9baac7;">—</span>
                    @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center text-muted py-5">Sin órdenes de pago.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

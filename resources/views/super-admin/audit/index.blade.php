@extends('layouts.app')

@section('styles')
<style>
    .audit-entry { display:flex; gap:.85rem; padding:.8rem 1rem; border-bottom:1px solid #f0f4fc; transition:background .1s; }
    .audit-entry:hover { background:#fafbff; }
    .audit-entry:last-child { border-bottom:none; }
    .audit-dot { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:.1rem; }
    .audit-dot .material-symbols-outlined { font-size:1rem; }
    .audit-action-label { font-size:.7rem; font-weight:800; padding:.12rem .5rem; border-radius:.35rem; letter-spacing:.03em; }
    .aa-payment   { background:#dcfce7; color:#166534; }
    .aa-sub       { background:#dbeafe; color:#1d4ed8; }
    .aa-user      { background:#fdf4ff; color:#7c3aed; }
    .aa-settings  { background:#fff7ed; color:#9a3412; }
    .aa-other     { background:#f3f4f6; color:#4b5563; }
    .dot-payment  { background:#f0fdf4; }
    .dot-sub      { background:#eff6ff; }
    .dot-user     { background:#fdf4ff; }
    .dot-settings { background:#fff7ed; }
    .dot-other    { background:#f3f4f6; }
    .txt-payment  { color:#16a34a; }
    .txt-sub      { color:#1d4ed8; }
    .txt-user     { color:#7c3aed; }
    .txt-settings { color:#d97706; }
    .txt-other    { color:#6b7280; }
    .action-filter { display:flex; gap:.35rem; flex-wrap:wrap; }
    .af-chip { border:1px solid #e5e7eb; background:#f9fafb; border-radius:.5rem; padding:.25rem .65rem; font-size:.75rem; font-weight:600; color:#4b5563; cursor:pointer; text-decoration:none; transition:all .12s; }
    .af-chip:hover { border-color:#93c5fd; color:#1d4ed8; }
    .af-chip.active { background:linear-gradient(95deg,#3b82f6,#6d5efc); color:#fff; border-color:transparent; }
    .pagination-bar { display:flex; gap:.35rem; justify-content:center; margin-top:1.25rem; flex-wrap:wrap; }
    .pg-btn { border:1px solid #e8eef8; background:#fff; border-radius:.55rem; padding:.3rem .7rem; font-size:.78rem; color:#374151; text-decoration:none; transition:all .12s; }
    .pg-btn:hover { border-color:#93c5fd; color:#1d4ed8; }
    .pg-btn.active { background:linear-gradient(95deg,#3b82f6,#6d5efc); color:#fff; border-color:transparent; }
    .pg-btn.disabled { opacity:.4; pointer-events:none; }
    .extra-json { background:#f8faff; border:1px solid #e8eef8; border-radius:.5rem; padding:.5rem .75rem; font-size:.72rem; color:#4b5563; font-family:monospace; margin-top:.4rem; }
</style>
@endsection

@section('content')
<section class="section-card p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h2 class="section-title">Auditoría del sistema</h2>
            <p class="section-subtitle">Historial completo de movimientos y acciones registradas — {{ number_format($total) }} entradas.</p>
        </div>
        <form method="GET" class="d-flex gap-2">
            <input type="hidden" name="action" value="{{ $action }}">
            <input type="text" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Buscar..." style="width:200px;">
            <button type="submit" class="btn btn-primary-soft btn-sm">Buscar</button>
            @if($search || $action)
            <a href="{{ route('superadmin.audit.index') }}" class="btn btn-outline-soft btn-sm">Limpiar</a>
            @endif
        </form>
    </div>

    {{-- Filtro por tipo de acción --}}
    @if(count($actionTypes) > 0)
    <div class="action-filter mb-3">
        <a href="{{ route('superadmin.audit.index') }}?q={{ $search }}" class="af-chip {{ !$action ? 'active' : '' }}">Todas</a>
        @foreach($actionTypes as $at)
        <a href="{{ route('superadmin.audit.index') }}?action={{ urlencode($at) }}&q={{ $search }}" class="af-chip {{ $action === $at ? 'active' : '' }}">
            {{ $at }}
        </a>
        @endforeach
    </div>
    @endif

    <div style="background:#fff;border:1px solid #e8eef8;border-radius:1rem;overflow:hidden;">
        @forelse($items as $entry)
        @php
            $cat = match(true) {
                str_starts_with($entry['action'],'payment')     => 'payment',
                str_starts_with($entry['action'],'subscription')=> 'sub',
                str_starts_with($entry['action'],'user')        => 'user',
                str_starts_with($entry['action'],'settings')    => 'settings',
                default                                         => 'other',
            };
            $icon = match($cat) {
                'payment'  => 'payments',
                'sub'      => 'workspace_premium',
                'user'     => 'person_add',
                'settings' => 'settings',
                default    => 'history',
            };
        @endphp
        <div class="audit-entry">
            <div class="audit-dot dot-{{ $cat }}">
                <span class="material-symbols-outlined txt-{{ $cat }}">{{ $icon }}</span>
            </div>
            <div class="flex-grow-1" style="min-width:0;">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                    <span class="audit-action-label aa-{{ $cat }}">{{ $entry['action'] }}</span>
                    @if(!empty($entry['user_name']))
                    <span style="font-size:.72rem;color:#60708d;">{{ $entry['user_name'] }} ({{ $entry['user_email'] ?? '' }})</span>
                    @endif
                    <span style="font-size:.7rem;color:#9baac7;margin-left:auto;">{{ \Carbon\Carbon::parse($entry['created_at'])->format('d/m/Y H:i:s') }}</span>
                </div>
                <div style="font-size:.83rem;color:#1a2648;">{{ $entry['description'] }}</div>
                @if(!empty($entry['extra']) && is_array($entry['extra']) && count($entry['extra']))
                <div class="extra-json">{{ json_encode($entry['extra'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</div>
                @endif
                <div style="font-size:.68rem;color:#9baac7;margin-top:.25rem;">IP: {{ $entry['ip'] ?? '—' }}</div>
            </div>
        </div>
        @empty
        <div class="text-center text-muted py-5">Sin entradas de auditoría registradas.</div>
        @endforelse
    </div>

    {{-- Paginación manual --}}
    @if($lastPage > 1)
    <div class="pagination-bar">
        <a href="?page={{ max(1,$page-1) }}&q={{ $search }}&action={{ $action }}" class="pg-btn {{ $page <= 1 ? 'disabled' : '' }}">← Anterior</a>
        @for($p = max(1,$page-3); $p <= min($lastPage,$page+3); $p++)
        <a href="?page={{ $p }}&q={{ $search }}&action={{ $action }}" class="pg-btn {{ $p === $page ? 'active' : '' }}">{{ $p }}</a>
        @endfor
        <a href="?page={{ min($lastPage,$page+1) }}&q={{ $search }}&action={{ $action }}" class="pg-btn {{ $page >= $lastPage ? 'disabled' : '' }}">Siguiente →</a>
    </div>
    <div class="text-center mt-2" style="font-size:.75rem;color:#9baac7;">
        Página {{ $page }} de {{ $lastPage }} · {{ $total }} entradas en total
    </div>
    @endif
</section>
@endsection

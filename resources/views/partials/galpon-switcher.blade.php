@php
    $galponService = app(\App\Services\UserGalponService::class);
    $galponOptions = auth()->check() && ! auth()->user()->is_superadmin
        ? $galponService->galponOptionsForNavbar(auth()->user())
        : collect();
@endphp
@if($galponOptions->count() > 1)
<style>
    .galpon-switch-wrap { position: relative; }
    .galpon-switch-btn {
        display: flex; align-items: center; gap: .4rem;
        background: linear-gradient(135deg, rgba(34,197,94,.22), rgba(59,130,246,.18));
        border: 1px solid rgba(74,222,128,.35);
        color: #ecfdf5; border-radius: .65rem;
        padding: .28rem .55rem .28rem .45rem;
        font-size: .72rem; font-weight: 700; cursor: pointer;
        max-width: 200px; transition: all .18s ease;
    }
    .galpon-switch-btn:hover { border-color: rgba(74,222,128,.6); background: linear-gradient(135deg, rgba(34,197,94,.32), rgba(59,130,246,.25)); }
    .galpon-switch-btn .material-symbols-outlined { font-size: .95rem; color: #4ade80; flex-shrink: 0; }
    .galpon-switch-label { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .galpon-switch-menu {
        display: none; position: absolute; top: calc(100% + 6px); left: 0; z-index: 1050;
        min-width: 240px; background: #fff; border: 1px solid #e8eef8;
        border-radius: .85rem; box-shadow: 0 12px 40px rgba(16,39,77,.18);
        overflow: hidden; padding: .35rem 0;
    }
    .galpon-switch-menu.show { display: block; }
    .galpon-switch-menu-title {
        padding: .5rem 1rem .35rem; font-size: .68rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .06em; color: #9baac7;
    }
    .galpon-switch-item {
        display: flex; align-items: center; gap: .5rem; width: 100%;
        border: none; background: none; text-align: left;
        padding: .55rem 1rem; font-size: .82rem; font-weight: 600;
        color: #1a2648; cursor: pointer; transition: background .12s;
    }
    .galpon-switch-item:hover { background: #f0f7ff; }
    .galpon-switch-item.active { background: #eff6ff; color: #1d4ed8; }
    .galpon-switch-item .material-symbols-outlined { font-size: 1rem; color: #22c55e; }
    .galpon-switch-item .badge-mini {
        font-size: .62rem; font-weight: 800; border-radius: .35rem;
        padding: .1rem .4rem; background: #dbeafe; color: #1d4ed8;
    }
    @media (max-width: 480px) {
        .galpon-switch-btn { max-width: 140px; }
    }
</style>
<div class="galpon-switch-wrap" id="galpon-switch-wrap">
    @php $active = $galponOptions->firstWhere('is_active', true); @endphp
    <button type="button" class="galpon-switch-btn" id="galpon-switch-btn" title="Cambiar galpón activo" aria-haspopup="listbox" aria-expanded="false">
        <span class="material-symbols-outlined">warehouse</span>
        <span class="galpon-switch-label" id="galpon-switch-current">{{ $active['name'] ?? 'Galpón' }}</span>
        <span class="material-symbols-outlined" style="font-size:1rem;opacity:.7;">expand_more</span>
    </button>
    <div class="galpon-switch-menu" id="galpon-switch-menu" role="listbox">
        <div class="galpon-switch-menu-title">Mis galpones</div>
        @foreach($galponOptions as $opt)
        <button type="button"
            class="galpon-switch-item {{ $opt['is_active'] ? 'active' : '' }}"
            data-tenant-id="{{ $opt['id'] }}"
            data-name="{{ $opt['name'] }}"
            role="option"
            aria-selected="{{ $opt['is_active'] ? 'true' : 'false' }}">
            <span class="material-symbols-outlined">{{ $opt['is_active'] ? 'check_circle' : 'radio_button_unchecked' }}</span>
            <span style="flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;">{{ $opt['name'] }}</span>
            @if($opt['is_primary'])<span class="badge-mini">Principal</span>@endif
        </button>
        @endforeach
    </div>
</div>
<script>
(function() {
    const wrap = document.getElementById('galpon-switch-wrap');
    if (!wrap) return;
    const btn = document.getElementById('galpon-switch-btn');
    const menu = document.getElementById('galpon-switch-menu');
    const current = document.getElementById('galpon-switch-current');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        menu.classList.toggle('show');
        btn.setAttribute('aria-expanded', menu.classList.contains('show'));
    });
    document.addEventListener('click', function() {
        menu.classList.remove('show');
        btn.setAttribute('aria-expanded', 'false');
    });
    menu.querySelectorAll('.galpon-switch-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.stopPropagation();
            if (item.classList.contains('active')) {
                menu.classList.remove('show');
                return;
            }
            const tenantId = item.dataset.tenantId;
            const name = item.dataset.name;
            item.disabled = true;
            fetch('{{ route('tenant.galpon.switch') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ tenant_id: tenantId })
            })
            .then(function(r) { return r.json().then(function(d) { return { ok: r.ok, d: d }; }); })
            .then(function(res) {
                if (!res.ok) throw new Error(res.d.message || 'Error al cambiar galpón');
                window.location.reload();
            })
            .catch(function(err) {
                alert(err.message || 'No se pudo cambiar de galpón.');
                item.disabled = false;
            });
        });
    });
})();
</script>
@endif

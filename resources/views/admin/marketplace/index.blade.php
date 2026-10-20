@extends('layouts.app')

@section('title', 'Mis publicaciones — Marketplace')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h1 class="h4 mb-0" style="font-weight:800;">Marketplace</h1>
        <p class="text-muted small mb-0">Publica tus aves para vender en Galpon.cc</p>
    </div>
    <x-module-create module="marketplace">
        <button type="button" class="btn btn-primary" id="btn-new-pub" data-bs-toggle="modal" data-bs-target="#modal-publicar">
            <span class="material-symbols-outlined" style="font-size:1rem;vertical-align:-2px;">add</span>
            Nueva publicación
        </button>
    </x-module-create>
</div>

@if(!empty(($planModules['marketplace']['pro'] ?? false)) && !($isProUser ?? false))
    <x-upgrade-cta feature="marketplace" />
@endif

<div id="pub-list" class="row g-3"></div>
<div id="pub-empty" class="text-center text-muted py-5" style="display:none;">
    <span class="material-symbols-outlined" style="font-size:3rem;opacity:.3;">storefront</span>
    <p class="mt-2">Aún no tienes publicaciones activas.</p>
</div>

<div class="modal fade" id="modal-publicar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Publicar ave en Marketplace</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tipo de ave</label>
                    <select id="pub-tipo" class="form-select">
                        <option value="gallo">Gallo</option>
                        <option value="gallina">Gallina</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ave (ID / placa)</label>
                    <input type="number" id="pub-ave-id" class="form-control" placeholder="ID del gallo o gallina">
                </div>
                <div class="mb-3">
                    <label class="form-label">Precio (USD)</label>
                    <input type="number" id="pub-precio" class="form-control" min="0" step="0.01">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea id="pub-desc" class="form-control" rows="3" maxlength="2000"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-save-pub">Publicar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

async function loadPublicaciones() {
    const res = await fetch('/api/publicaciones', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
    const json = await res.json();
    const items = json.data || [];
    const list = document.getElementById('pub-list');
    const empty = document.getElementById('pub-empty');
    list.innerHTML = '';
    if (!items.length) { empty.style.display = 'block'; return; }
    empty.style.display = 'none';
    items.forEach(p => {
        const col = document.createElement('div');
        col.className = 'col-md-6';
        col.innerHTML = `<div class="card h-100"><div class="card-body">
            <div class="d-flex justify-content-between"><strong>#${p.id}</strong>
            <span class="badge ${p.activo ? 'bg-success' : 'bg-secondary'}">${p.activo ? 'Activa' : 'Inactiva'}</span></div>
            <div class="text-muted small">Ave ID: ${p.ave_id}</div>
            <div class="fw-bold mt-1">$${parseFloat(p.precio).toFixed(2)}</div>
            <p class="small mb-2">${p.descripcion || ''}</p>
            ${p.activo ? `<button class="btn btn-sm btn-outline-danger" onclick="desactivar(${p.id})">Desactivar</button>` : ''}
        </div></div>`;
        list.appendChild(col);
    });
}

async function desactivar(id) {
    if (!confirm('¿Desactivar esta publicación?')) return;
    await fetch(`/api/publicaciones/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, credentials: 'same-origin' });
    loadPublicaciones();
}

document.getElementById('btn-save-pub').addEventListener('click', async () => {
    const body = {
        ave_type: document.getElementById('pub-tipo').value,
        ave_id: parseInt(document.getElementById('pub-ave-id').value, 10),
        precio: parseFloat(document.getElementById('pub-precio').value),
        descripcion: document.getElementById('pub-desc').value,
    };
    const res = await fetch('/api/publicaciones', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        credentials: 'same-origin',
        body: JSON.stringify(body),
    });
    const json = await res.json();
    if (!res.ok) {
        if (json.upgrade_url) {
            if (confirm(json.msj + '\n\n¿Ir a activar Pro?')) window.location = json.upgrade_url;
        } else {
            alert(json.msj || json.message || 'Error al publicar');
        }
        return;
    }
    bootstrap.Modal.getInstance(document.getElementById('modal-publicar')).hide();
    loadPublicaciones();
});

loadPublicaciones();
</script>
@endsection

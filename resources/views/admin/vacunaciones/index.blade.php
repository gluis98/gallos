@extends('layouts.app')

@section('title', 'Vacunación Avícola')

@section('styles')
<style>
    .vac-badge { font-size:.72rem; font-weight:700; padding:.22rem .6rem; border-radius:.45rem; white-space:nowrap; }
    .vac-badge.proxima  { background:#fef9c3; color:#854d0e; }
    .vac-badge.vencida  { background:#fee2e2; color:#991b1b; }
    .vac-badge.aplicada { background:#f0fdf4; color:#166534; }
    .stat-mini { background:#fff; border:1px solid #e5e9f2; border-radius:.85rem; padding:1rem 1.25rem; }
    .stat-mini .val { font-size:1.5rem; font-weight:800; color:#1a2648; line-height:1; }
    .stat-mini .lbl { font-size:.73rem; color:#60708d; margin-top:.2rem; }
    .tab-pills .nav-link { border-radius:.65rem; font-size:.85rem; font-weight:600; color:#60708d; border:1px solid transparent; padding:.4rem .9rem; }
    .tab-pills .nav-link.active { background:linear-gradient(95deg,#3b82f6,#6d5efc); color:#fff; border-color:transparent; }
    .vacuna-row:hover { background:#f8faff; }
    .proxima-alert { background:#fff7ed; border:1px solid #fed7aa; border-radius:.85rem; padding:.75rem 1rem; }
</style>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h1 class="section-title mb-0">
            <span class="material-symbols-outlined align-middle me-1" style="font-size:1.6rem;color:#22c55e;">vaccines</span>
            Vacunación Avícola
        </h1>
        <p class="section-subtitle mb-0">Historial médico y control de vacunas de tus aves</p>
    </div>
    <button class="btn btn-primary-soft px-4" onclick="openModalVac()">
        <span class="material-symbols-outlined align-middle me-1" style="font-size:1rem;">add</span>
        Registrar vacuna
    </button>
</div>

{{-- Estadísticas --}}
<div class="row g-3 mb-4" id="stats-row">
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="val" id="stat-total-g">—</div>
            <div class="lbl">Vacunas a Gallos</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="val" id="stat-total-ga">—</div>
            <div class="lbl">Vacunas a Gallinas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="val text-warning" id="stat-proximas">—</div>
            <div class="lbl">Próximas (30 días)</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini">
            <div class="val text-primary" id="stat-vacuna-top">—</div>
            <div class="lbl">Vacuna más aplicada</div>
        </div>
    </div>
</div>

{{-- Alertas próximas --}}
<div id="proximas-alert-wrapper" class="mb-3" style="display:none;">
    <div class="proxima-alert d-flex align-items-center gap-2">
        <span class="material-symbols-outlined" style="color:#f59e0b;font-size:1.1rem;">notification_important</span>
        <span id="proximas-alert-text" style="font-size:.85rem;color:#92400e;font-weight:600;"></span>
        <button class="btn btn-sm ms-auto" style="background:#fff;border:1px solid #fed7aa;color:#92400e;border-radius:.5rem;font-size:.75rem;font-weight:700;" onclick="filterProximas()">
            Ver próximas
        </button>
    </div>
</div>

{{-- Filtros y tabla --}}
<div class="section-card p-0 overflow-hidden">
    {{-- Barra de filtros --}}
    <div class="p-3 border-bottom d-flex flex-wrap gap-2 align-items-center" style="background:#fafbfe;">
        <ul class="nav tab-pills gap-1 flex-grow-1 flex-wrap">
            <li><button class="nav-link active" onclick="setTipoFiltro('todos', this)">Todas</button></li>
            <li><button class="nav-link" onclick="setTipoFiltro('gallo', this)">Gallos</button></li>
            <li><button class="nav-link" onclick="setTipoFiltro('gallina', this)">Gallinas</button></li>
            <li><button class="nav-link" id="btn-proximas-tab" onclick="filterProximas()">⏰ Próximas</button></li>
        </ul>
        <div class="input-group" style="max-width:220px;">
            <span class="input-group-text" style="background:#fff;border-right:0;border-color:#d7dfed;">
                <span class="material-symbols-outlined" style="font-size:.9rem;color:#94a3b8;">search</span>
            </span>
            <input type="text" id="search-vacuna" class="form-control border-start-0" placeholder="Buscar vacuna..."
                style="border-color:#d7dfed;" oninput="debounceSearch()">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:.86rem;">
            <thead style="background:#f8faff;border-bottom:1px solid #e5e9f2;">
                <tr>
                    <th class="px-4 py-3 fw-700 text-muted">Ave</th>
                    <th class="px-3 py-3 fw-700 text-muted">Vacuna</th>
                    <th class="px-3 py-3 fw-700 text-muted d-none d-md-table-cell">Dosis / Vía</th>
                    <th class="px-3 py-3 fw-700 text-muted">Fecha</th>
                    <th class="px-3 py-3 fw-700 text-muted d-none d-md-table-cell">Próxima</th>
                    <th class="px-3 py-3 fw-700 text-muted d-none d-lg-table-cell">Veterinario</th>
                    <th class="px-3 py-3 fw-700 text-muted text-end">Acciones</th>
                </tr>
            </thead>
            <tbody id="tbl-vacunaciones">
                <tr><td colspan="7" class="text-center py-5" style="color:#94a3b8;">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div> Cargando...
                </td></tr>
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2" style="background:#fafbfe;font-size:.82rem;color:#60708d;" id="pagination-row" style="display:none!important;">
        <span id="pagination-info"></span>
        <div id="pagination-links" class="d-flex gap-1"></div>
    </div>
</div>

{{-- ═══ Modal registrar vacunación ═══ --}}
<div id="modal-vac" style="display:none;position:fixed;inset:0;z-index:2000;background:rgba(10,20,50,.55);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:1rem;">
<div style="background:#fff;border-radius:1.2rem;width:min(600px,100%);max-height:90vh;overflow-y:auto;box-shadow:0 24px 64px rgba(10,20,50,.22);animation:slideUp .25s ease;">
    <div style="background:linear-gradient(135deg,#1a2648,#1e3a8a);padding:1.25rem 1.5rem;display:flex;justify-content:space-between;align-items:center;border-radius:1.2rem 1.2rem 0 0;">
        <div>
            <h5 style="margin:0;color:#fff;font-weight:700;font-size:1rem;" id="modal-vac-title">Registrar Vacunación</h5>
            <small style="color:rgba(255,255,255,.65);">Historial médico del ave</small>
        </div>
        <button onclick="closeModalVac()" style="background:rgba(255,255,255,.15);border:none;color:#fff;border-radius:50%;width:32px;height:32px;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">✕</button>
    </div>
    <form id="form-vac" style="padding:1.5rem;" onsubmit="submitVac(event)">
        <input type="hidden" id="vac-id" value="">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-600 small">Tipo de ave *</label>
                <select id="vac-ave-type" class="form-select" required onchange="loadAves()">
                    <option value="">Seleccionar...</option>
                    <option value="gallo">Gallo</option>
                    <option value="gallina">Gallina</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600 small">Ave *</label>
                <select id="vac-ave-id" class="form-select" required>
                    <option value="">Primero selecciona el tipo</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-600 small">Vacuna / Medicamento *</label>
                <input type="text" id="vac-vacuna" class="form-control" required
                    placeholder="Ej: Newcastle, Marek, Gumboro, Bronquitis Infecciosa...">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600 small">Descripción</label>
                <input type="text" id="vac-descripcion" class="form-control" placeholder="Descripción adicional">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-600 small">Dosis</label>
                <input type="text" id="vac-dosis" class="form-control" placeholder="Ej: 0.5 ml">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-600 small">Vía</label>
                <select id="vac-via" class="form-select">
                    <option value="">—</option>
                    <option value="Subcutánea">Subcutánea</option>
                    <option value="Intramuscular">Intramuscular</option>
                    <option value="Ocular">Ocular</option>
                    <option value="Nasal">Nasal</option>
                    <option value="Oral / Agua">Oral / Agua</option>
                    <option value="Ala Web">Ala Web</option>
                    <option value="Spray">Spray</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600 small">Fecha de aplicación *</label>
                <input type="date" id="vac-fecha" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600 small">Próxima dosis</label>
                <input type="date" id="vac-proxima" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600 small">Lote / N° de vial</label>
                <input type="text" id="vac-lote" class="form-control" placeholder="Ej: LOT-2025-A">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600 small">Veterinario / Aplicador</label>
                <input type="text" id="vac-vet" class="form-control" placeholder="Nombre">
            </div>
            <div class="col-12">
                <label class="form-label fw-600 small">Notas</label>
                <textarea id="vac-notas" rows="2" class="form-control" placeholder="Observaciones, reacciones, etc."></textarea>
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-end mt-4">
            <button type="button" onclick="closeModalVac()" class="btn btn-outline-soft">Cancelar</button>
            <button type="submit" class="btn btn-primary-soft px-4">
                <span class="material-symbols-outlined align-middle me-1" style="font-size:.9rem;">save</span>
                Guardar
            </button>
        </div>
    </form>
</div>
</div>

{{-- Modal detalle --}}
<div id="modal-vac-detail" style="display:none;position:fixed;inset:0;z-index:2000;background:rgba(10,20,50,.55);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:1rem;">
<div style="background:#fff;border-radius:1.2rem;width:min(520px,100%);box-shadow:0 24px 64px rgba(10,20,50,.22);animation:slideUp .25s ease;">
    <div style="background:linear-gradient(135deg,#065f46,#059669);padding:1.25rem 1.5rem;display:flex;justify-content:space-between;align-items:center;border-radius:1.2rem 1.2rem 0 0;">
        <h5 style="margin:0;color:#fff;font-weight:700;font-size:1rem;">Detalle de Vacunación</h5>
        <button onclick="closeDetail()" style="background:rgba(255,255,255,.2);border:none;color:#fff;border-radius:50%;width:32px;height:32px;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">✕</button>
    </div>
    <div id="detail-body" style="padding:1.5rem;"></div>
</div>
</div>

<style>
@keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
#modal-vac { display:none!important; }
#modal-vac.open { display:flex!important; }
#modal-vac-detail { display:none!important; }
#modal-vac-detail.open { display:flex!important; }
</style>
@endsection

@section('scripts')
<script>
const API = '/api/vacunaciones';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let currentPage = 1, currentAveType = '', currentSearch = '', showProximas = false;
let searchTimer = null;

// ─── Cargar estadísticas ───────────────────────────────────────────────────
async function loadStats() {
    const res = await fetch('/api/vacunaciones/estadisticas', {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    });
    if (!res.ok) return;
    const data = await res.json();
    document.getElementById('stat-total-g').textContent  = data.total_gallos   ?? 0;
    document.getElementById('stat-total-ga').textContent = data.total_gallinas  ?? 0;
    document.getElementById('stat-proximas').textContent = data.proximas_30d    ?? 0;
    document.getElementById('stat-vacuna-top').textContent = data.por_vacuna?.[0]?.vacuna ?? '—';
    if (data.proximas_30d > 0) {
        document.getElementById('proximas-alert-wrapper').style.display = '';
        document.getElementById('proximas-alert-text').textContent =
            `⚠ Tienes ${data.proximas_30d} vacuna${data.proximas_30d > 1 ? 's' : ''} próxima${data.proximas_30d > 1 ? 's' : ''} en los próximos 30 días`;
    }
}

// ─── Cargar vacunaciones ───────────────────────────────────────────────────
async function loadVacunaciones(page = 1) {
    const tbody = document.getElementById('tbl-vacunaciones');
    tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4" style="color:#94a3b8;"><div class="spinner-border spinner-border-sm me-2" role="status"></div> Cargando...</td></tr>`;

    const params = new URLSearchParams({ page });
    if (currentAveType) params.set('ave_type', currentAveType);
    if (currentSearch)  params.set('vacuna', currentSearch);
    if (showProximas)   params.set('proximas', '1');

    const res = await fetch(`${API}?${params}`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    });
    if (!res.ok) { tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">Error al cargar datos</td></tr>`; return; }

    const data = await res.json();
    renderTable(data);
    renderPagination(data);
    currentPage = page;
}

function renderTable(data) {
    const rows = data.data || [];
    const tbody = document.getElementById('tbl-vacunaciones');
    if (!rows.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5" style="color:#94a3b8;">
            <span class="material-symbols-outlined d-block mb-2" style="font-size:2.5rem;">vaccines</span>
            No se encontraron vacunaciones
        </td></tr>`;
        return;
    }
    tbody.innerHTML = rows.map(v => {
        const estadoBadge = getEstadoBadge(v.proxima_fecha);
        const tipo = v.ave_type === 'gallo'
            ? `<span style="background:#eff6ff;color:#2563eb;padding:.15rem .5rem;border-radius:.4rem;font-size:.72rem;font-weight:700;">🐓 Gallo</span>`
            : `<span style="background:#f0fdf4;color:#166534;padding:.15rem .5rem;border-radius:.4rem;font-size:.72rem;font-weight:700;">🥚 Gallina</span>`;
        return `<tr class="vacuna-row">
            <td class="px-4 py-3">
                ${tipo}
                <div class="fw-600 mt-1" style="font-size:.82rem;color:#1a2648;">${v.ave_nombre || '#'+v.ave_id}</div>
            </td>
            <td class="px-3 py-3">
                <div class="fw-600" style="color:#1a2648;">${v.vacuna}</div>
                ${v.descripcion ? `<div style="font-size:.75rem;color:#94a3b8;">${v.descripcion}</div>` : ''}
            </td>
            <td class="px-3 py-3 d-none d-md-table-cell" style="color:#60708d;font-size:.82rem;">
                ${v.dosis ? `<div>${v.dosis}</div>` : ''}
                ${v.via_administracion ? `<div style="font-size:.75rem;">${v.via_administracion}</div>` : '—'}
            </td>
            <td class="px-3 py-3" style="color:#374151;font-size:.83rem;">${formatDate(v.fecha_aplicacion)}</td>
            <td class="px-3 py-3 d-none d-md-table-cell">
                ${v.proxima_fecha ? `${estadoBadge}<div style="font-size:.75rem;color:#60708d;margin-top:.2rem;">${formatDate(v.proxima_fecha)}</div>` : '<span style="color:#94a3b8;">—</span>'}
            </td>
            <td class="px-3 py-3 d-none d-lg-table-cell" style="color:#60708d;font-size:.82rem;">${v.veterinario || '—'}</td>
            <td class="px-3 py-3 text-end">
                <div class="d-flex gap-1 justify-content-end">
                    <button onclick="showDetail(${JSON.stringify(v).replace(/"/g,'&quot;')})" class="btn btn-sm btn-outline-soft" title="Ver detalle">
                        <span class="material-symbols-outlined" style="font-size:.85rem;">visibility</span>
                    </button>
                    <button onclick="editVac(${JSON.stringify(v).replace(/"/g,'&quot;')})" class="btn btn-sm btn-outline-soft" title="Editar">
                        <span class="material-symbols-outlined" style="font-size:.85rem;">edit</span>
                    </button>
                    <button onclick="deleteVac(${v.id})" class="btn btn-sm" style="border:1px solid #fee2e2;color:#ef4444;background:#fff;border-radius:.7rem;" title="Eliminar">
                        <span class="material-symbols-outlined" style="font-size:.85rem;">delete</span>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function getEstadoBadge(proximaFecha) {
    if (!proximaFecha) return '';
    const hoy = new Date(); hoy.setHours(0,0,0,0);
    const proxima = new Date(proximaFecha + 'T00:00:00');
    const diff = Math.ceil((proxima - hoy) / 86400000);
    if (diff < 0)  return `<span class="vac-badge vencida">Vencida</span>`;
    if (diff <= 30) return `<span class="vac-badge proxima">En ${diff}d</span>`;
    return `<span class="vac-badge aplicada">Próxima OK</span>`;
}

function formatDate(d) {
    if (!d) return '—';
    const [y,m,day] = d.split('-');
    return `${day}/${m}/${y}`;
}

function renderPagination(data) {
    const row = document.getElementById('pagination-row');
    const info = document.getElementById('pagination-info');
    const links = document.getElementById('pagination-links');
    if (!data.last_page || data.last_page <= 1) { row.style.display = 'none'; return; }
    row.style.display = '';
    info.textContent = `${data.from || 0}–${data.to || 0} de ${data.total}`;
    links.innerHTML = '';
    for (let p = 1; p <= data.last_page; p++) {
        const btn = document.createElement('button');
        btn.className = 'btn btn-sm' + (p === data.current_page ? ' btn-primary-soft' : ' btn-outline-soft');
        btn.style.minWidth = '36px';
        btn.textContent = p;
        btn.onclick = () => loadVacunaciones(p);
        links.appendChild(btn);
    }
}

// ─── Filtros ───────────────────────────────────────────────────────────────
function setTipoFiltro(tipo, btn) {
    currentAveType = tipo === 'todos' ? '' : tipo;
    showProximas = false;
    document.querySelectorAll('.tab-pills .nav-link').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    loadVacunaciones(1);
}

function filterProximas() {
    showProximas = !showProximas;
    currentAveType = '';
    document.querySelectorAll('.tab-pills .nav-link').forEach(b => b.classList.remove('active'));
    document.getElementById('btn-proximas-tab').classList.toggle('active', showProximas);
    loadVacunaciones(1);
}

function debounceSearch() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        currentSearch = document.getElementById('search-vacuna').value.trim();
        loadVacunaciones(1);
    }, 400);
}

// ─── Modal registrar ──────────────────────────────────────────────────────
function openModalVac() {
    document.getElementById('modal-vac-title').textContent = 'Registrar Vacunación';
    document.getElementById('form-vac').reset();
    document.getElementById('vac-id').value = '';
    document.getElementById('vac-fecha').value = new Date().toISOString().split('T')[0];
    document.getElementById('modal-vac').classList.add('open');
}

function closeModalVac() {
    document.getElementById('modal-vac').classList.remove('open');
}

function editVac(v) {
    document.getElementById('modal-vac-title').textContent = 'Editar Vacunación';
    document.getElementById('vac-id').value = v.id;
    document.getElementById('vac-ave-type').value = v.ave_type;
    document.getElementById('vac-vacuna').value = v.vacuna || '';
    document.getElementById('vac-descripcion').value = v.descripcion || '';
    document.getElementById('vac-dosis').value = v.dosis || '';
    document.getElementById('vac-via').value = v.via_administracion || '';
    document.getElementById('vac-fecha').value = v.fecha_aplicacion || '';
    document.getElementById('vac-proxima').value = v.proxima_fecha || '';
    document.getElementById('vac-lote').value = v.lote || '';
    document.getElementById('vac-vet').value = v.veterinario || '';
    document.getElementById('vac-notas').value = v.notas || '';
    loadAves(v.ave_id);
    document.getElementById('modal-vac').classList.add('open');
}

async function loadAves(selectedId = null) {
    const tipo = document.getElementById('vac-ave-type').value;
    const sel = document.getElementById('vac-ave-id');
    if (!tipo) { sel.innerHTML = '<option value="">Primero selecciona el tipo</option>'; return; }
    sel.innerHTML = '<option value="">Cargando...</option>';
    const endpoint = tipo === 'gallo' ? '/api/gallos' : '/api/gallinas';
    const res = await fetch(`${endpoint}?per_page=200`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    });
    if (!res.ok) { sel.innerHTML = '<option value="">Error al cargar</option>'; return; }
    const data = await res.json();
    const items = data.data || data;
    sel.innerHTML = '<option value="">Seleccionar ave...</option>' +
        items.map(a => `<option value="${a.id}" ${a.id == selectedId ? 'selected' : ''}>${a.nombre || a.codigo || '#'+a.id}</option>`).join('');
}

async function submitVac(e) {
    e.preventDefault();
    const id = document.getElementById('vac-id').value;
    const payload = {
        ave_id:             document.getElementById('vac-ave-id').value,
        ave_type:           document.getElementById('vac-ave-type').value,
        vacuna:             document.getElementById('vac-vacuna').value,
        descripcion:        document.getElementById('vac-descripcion').value,
        dosis:              document.getElementById('vac-dosis').value,
        via_administracion: document.getElementById('vac-via').value,
        fecha_aplicacion:   document.getElementById('vac-fecha').value,
        proxima_fecha:      document.getElementById('vac-proxima').value || null,
        lote:               document.getElementById('vac-lote').value,
        veterinario:        document.getElementById('vac-vet').value,
        notas:              document.getElementById('vac-notas').value,
    };
    const url    = id ? `${API}/${id}` : API;
    const method = id ? 'PUT' : 'POST';
    const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify(payload),
    });
    const result = await res.json();
    if (!res.ok) {
        const msgs = result.errors ? Object.values(result.errors).flat().join('\n') : (result.message || 'Error');
        Swal.fire({ icon: 'error', title: 'Error', text: msgs });
        return;
    }
    closeModalVac();
    Swal.fire({ icon: 'success', title: id ? 'Actualizado' : 'Registrado', timer: 1800, showConfirmButton: false });
    loadVacunaciones(currentPage);
    loadStats();
}

async function deleteVac(id) {
    const confirm = await Swal.fire({
        icon: 'warning', title: '¿Eliminar vacunación?',
        text: 'Esta acción no se puede deshacer.',
        showCancelButton: true, confirmButtonText: 'Sí, eliminar',
        confirmButtonColor: '#ef4444', cancelButtonText: 'Cancelar',
    });
    if (!confirm.isConfirmed) return;
    const res = await fetch(`${API}/${id}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
    });
    if (res.ok) {
        Swal.fire({ icon: 'success', title: 'Eliminado', timer: 1500, showConfirmButton: false });
        loadVacunaciones(currentPage);
        loadStats();
    }
}

// ─── Detalle ──────────────────────────────────────────────────────────────
function showDetail(v) {
    const tipos = { 'gallo': '🐓 Gallo', 'gallina': '🥚 Gallina' };
    document.getElementById('detail-body').innerHTML = `
        <div class="row g-3" style="font-size:.85rem;">
            <div class="col-6"><div style="color:#94a3b8;font-size:.74rem;text-transform:uppercase;letter-spacing:.05em;">Ave</div>
                <div class="fw-600" style="color:#1a2648;">${tipos[v.ave_type] || v.ave_type} — ${v.ave_nombre || '#'+v.ave_id}</div></div>
            <div class="col-6"><div style="color:#94a3b8;font-size:.74rem;text-transform:uppercase;letter-spacing:.05em;">Vacuna</div>
                <div class="fw-600" style="color:#1a2648;">${v.vacuna}</div></div>
            ${v.descripcion ? `<div class="col-12"><div style="color:#94a3b8;font-size:.74rem;text-transform:uppercase;letter-spacing:.05em;">Descripción</div><div>${v.descripcion}</div></div>` : ''}
            <div class="col-4"><div style="color:#94a3b8;font-size:.74rem;text-transform:uppercase;letter-spacing:.05em;">Dosis</div>
                <div>${v.dosis || '—'}</div></div>
            <div class="col-4"><div style="color:#94a3b8;font-size:.74rem;text-transform:uppercase;letter-spacing:.05em;">Vía</div>
                <div>${v.via_administracion || '—'}</div></div>
            <div class="col-4"><div style="color:#94a3b8;font-size:.74rem;text-transform:uppercase;letter-spacing:.05em;">Lote</div>
                <div>${v.lote || '—'}</div></div>
            <div class="col-6"><div style="color:#94a3b8;font-size:.74rem;text-transform:uppercase;letter-spacing:.05em;">Fecha aplicación</div>
                <div class="fw-600">${formatDate(v.fecha_aplicacion)}</div></div>
            <div class="col-6"><div style="color:#94a3b8;font-size:.74rem;text-transform:uppercase;letter-spacing:.05em;">Próxima dosis</div>
                <div class="fw-600">${formatDate(v.proxima_fecha)}</div></div>
            <div class="col-12"><div style="color:#94a3b8;font-size:.74rem;text-transform:uppercase;letter-spacing:.05em;">Veterinario</div>
                <div>${v.veterinario || '—'}</div></div>
            ${v.notas ? `<div class="col-12"><div style="color:#94a3b8;font-size:.74rem;text-transform:uppercase;letter-spacing:.05em;">Notas</div>
                <div style="background:#f8faff;padding:.7rem;border-radius:.5rem;">${v.notas}</div></div>` : ''}
        </div>
        <div class="d-flex gap-2 justify-content-end mt-4">
            <button onclick="closeDetail()" class="btn btn-outline-soft">Cerrar</button>
            <button onclick="closeDetail();editVac(${JSON.stringify(v).replace(/"/g,'&quot;')})" class="btn btn-primary-soft px-4">Editar</button>
        </div>`;
    document.getElementById('modal-vac-detail').classList.add('open');
}

function closeDetail() { document.getElementById('modal-vac-detail').classList.remove('open'); }

// ─── Init ─────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    loadVacunaciones();
    loadStats();
});
</script>
@endsection

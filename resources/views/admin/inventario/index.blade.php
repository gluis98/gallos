@extends('layouts.app')

@section('styles')
<style>
.inv-card { background:#fff; border:1px solid #e8eef8; border-radius:1rem; overflow:hidden; transition:all .2s; box-shadow:0 4px 14px rgba(16,39,77,.06); }
.inv-card:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(16,39,77,.12); }
.inv-badge { display:inline-flex; align-items:center; gap:.3rem; font-size:.68rem; font-weight:700; border-radius:20px; padding:.2rem .6rem; }
.stock-ok  { background:#dcfce7; color:#15803d; }
.stock-low { background:#fef3c7; color:#92400e; }
.stock-out { background:#fee2e2; color:#991b1b; }
.price-tag { font-size:1.15rem; font-weight:900; color:#1a2648; }
.bs-tag    { font-size:.72rem; color:#60708d; font-weight:500; }
.field-s   { border:1.5px solid #d7dfed; border-radius:.65rem; padding:.5rem .75rem; font-size:.88rem; width:100%; outline:none; font-family:inherit; }
.field-s:focus { border-color:#8eb6ff; box-shadow:0 0 0 .18rem rgba(59,130,246,.16); }
.label-s   { font-size:.8rem; font-weight:600; color:#374151; margin-bottom:.3rem; display:block; }
.sidebar-section { background:#f8faff; border:1px solid #e8eef8; border-radius:.85rem; padding:1rem; margin-bottom:1rem; }
.sidebar-section h6 { font-size:.82rem; font-weight:800; color:#1a2648; margin-bottom:.75rem; display:flex; align-items:center; gap:.4rem; }
.sidebar-section h6 .material-symbols-outlined { font-size:1rem; color:#3b82f6; }
.profit-bar-wrap { background:#e8eef8; border-radius:20px; height:6px; overflow:hidden; }
.profit-bar { background:linear-gradient(90deg,#3b82f6,#8b5cf6); height:100%; border-radius:20px; transition:width .4s; }
</style>
@endsection

@section('content')
{{-- Sidebar offcanvas --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="sidebar-inv" style="width:min(520px,100vw);">
    <div style="background:linear-gradient(135deg,#1a2648,#2d4278);padding:1.25rem 1.5rem;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h5 id="sidebar-inv-title" style="margin:0;color:#fff;font-weight:800;font-size:1rem;">Nuevo ítem de inventario</h5>
            <small style="color:rgba(255,255,255,.6);font-size:.72rem;">Completa los datos del producto</small>
        </div>
        <button data-bs-dismiss="offcanvas" style="background:rgba(255,255,255,.15);border:none;color:#fff;border-radius:50%;width:32px;height:32px;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">✕</button>
    </div>
    <div class="offcanvas-body p-0" style="overflow-y:auto;">
        <form id="form-inv" enctype="multipart/form-data" style="padding:1.25rem;">
            @csrf
            <input type="hidden" id="inv-edit-id" name="_edit_id" value="">

            <div class="sidebar-section">
                <h6><span class="material-symbols-outlined">inventory_2</span> Información del producto</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="label-s">Nombre <span style="color:#ef4444">*</span></label>
                        <input type="text" name="nombre" class="field-s" placeholder="Ej: Suplemento vitamínico A" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="label-s">Categoría</label>
                        <input type="text" name="categoria" class="field-s" id="inv-categoria" placeholder="Medicamentos, Alimentos...">
                    </div>
                    <div class="col-sm-6">
                        <label class="label-s">Unidad de medida</label>
                        <select name="unidad_medida" class="field-s">
                            <option value="unidad">Unidad</option>
                            <option value="kg">Kilogramo (kg)</option>
                            <option value="g">Gramo (g)</option>
                            <option value="litro">Litro (L)</option>
                            <option value="ml">Mililitro (mL)</option>
                            <option value="saco">Saco</option>
                            <option value="caja">Caja</option>
                            <option value="rollo">Rollo</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="label-s">Descripción</label>
                        <textarea name="descripcion" class="field-s" rows="2" placeholder="Descripción opcional..."></textarea>
                    </div>
                </div>
            </div>

            <div class="sidebar-section">
                <h6><span class="material-symbols-outlined">payments</span> Precios y utilidad</h6>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="label-s">Costo unitario (USD) <span style="color:#ef4444">*</span></label>
                        <div style="display:flex;align-items:center;border:1.5px solid #d7dfed;border-radius:.65rem;overflow:hidden;">
                            <span style="background:#f3f6fd;padding:.5rem .7rem;font-size:.85rem;font-weight:700;color:#374151;border-right:1.5px solid #d7dfed;">$</span>
                            <input type="number" name="costo_unitario" id="inv-costo" class="field-s" style="border:none;border-radius:0;" min="0" step="0.0001" placeholder="0.00" oninput="calcPrecio()" required>
                        </div>
                        <small id="costo-bs-hint" class="bs-tag mt-1 d-block"></small>
                    </div>
                    <div class="col-sm-6">
                        <label class="label-s">% Utilidad / Ganancia</label>
                        <div style="display:flex;align-items:center;border:1.5px solid #d7dfed;border-radius:.65rem;overflow:hidden;">
                            <input type="number" name="utilidad_porcentaje" id="inv-utilidad" class="field-s" style="border:none;border-radius:0;" min="0" step="0.01" placeholder="0" value="0" oninput="calcPrecio()">
                            <span style="background:#f3f6fd;padding:.5rem .7rem;font-size:.85rem;font-weight:700;color:#374151;border-left:1.5px solid #d7dfed;">%</span>
                        </div>
                        <div class="profit-bar-wrap mt-1"><div class="profit-bar" id="profit-bar" style="width:0%"></div></div>
                    </div>
                    <div class="col-sm-6">
                        <label class="label-s">Precio de venta (USD)</label>
                        <div style="display:flex;align-items:center;border:1.5px solid #d7dfed;border-radius:.65rem;overflow:hidden;">
                            <span style="background:#f3f6fd;padding:.5rem .7rem;font-size:.85rem;font-weight:700;color:#374151;border-right:1.5px solid #d7dfed;">$</span>
                            <input type="number" name="precio_venta" id="inv-precio" class="field-s" style="border:none;border-radius:0;" min="0" step="0.0001" placeholder="Auto" oninput="onPrecioManual()">
                        </div>
                        <small id="precio-bs-hint" class="bs-tag mt-1 d-block"></small>
                    </div>
                    <div class="col-sm-6">
                        <label class="label-s">Stock inicial</label>
                        <input type="number" name="stock_inicial" id="inv-stock" class="field-s" min="0" step="0.01" placeholder="0" value="0">
                    </div>
                </div>
                {{-- Resumen de precios --}}
                <div id="inv-summary" style="margin-top:1rem;background:linear-gradient(135deg,#1a2648,#2d4278);border-radius:.85rem;padding:1rem;display:none;">
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.5rem;text-align:center;">
                        <div>
                            <div style="font-size:.65rem;color:rgba(255,255,255,.6);margin-bottom:.2rem;">COSTO</div>
                            <div id="sum-costo" style="font-size:.95rem;font-weight:900;color:#fff;"></div>
                            <div id="sum-costo-bs" style="font-size:.65rem;color:#fde68a;"></div>
                        </div>
                        <div style="border-left:1px solid rgba(255,255,255,.15);border-right:1px solid rgba(255,255,255,.15);">
                            <div style="font-size:.65rem;color:rgba(255,255,255,.6);margin-bottom:.2rem;">UTILIDAD</div>
                            <div id="sum-util" style="font-size:.95rem;font-weight:900;color:#4ade80;"></div>
                        </div>
                        <div>
                            <div style="font-size:.65rem;color:rgba(255,255,255,.6);margin-bottom:.2rem;">P. VENTA</div>
                            <div id="sum-precio" style="font-size:.95rem;font-weight:900;color:#fbbf24;"></div>
                            <div id="sum-precio-bs" style="font-size:.65rem;color:#fde68a;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar-section">
                <h6><span class="material-symbols-outlined">photo_camera</span> Foto e información adicional</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="label-s">Foto del producto</label>
                        <label for="inv-foto" style="display:flex;flex-direction:column;align-items:center;justify-content:center;border:2px dashed #b8c8e8;border-radius:.85rem;padding:1.25rem;cursor:pointer;background:#f8fbff;transition:all .2s;" id="dropzone-inv" ondragover="this.style.borderColor='#6ea4ff'" ondragleave="this.style.borderColor='#b8c8e8'">
                            <span class="material-symbols-outlined" style="font-size:2rem;color:#94a3b8;">add_photo_alternate</span>
                            <span style="font-size:.78rem;color:#60708d;margin-top:.4rem;">Haz click o arrastra una imagen</span>
                            <img id="inv-foto-preview" src="" style="display:none;max-height:120px;border-radius:.5rem;margin-top:.75rem;object-fit:contain;">
                        </label>
                        <input type="file" name="foto" id="inv-foto" accept="image/*" style="display:none;" onchange="previewFoto(this)">
                    </div>
                    <div class="col-12">
                        <label class="label-s">Notas</label>
                        <textarea name="notas" class="field-s" rows="2" placeholder="Notas adicionales sobre el producto..."></textarea>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:.75rem;padding-bottom:1rem;">
                <button type="button" data-bs-dismiss="offcanvas" style="flex:1;background:#f3f6fd;border:1.5px solid #d7dfed;border-radius:.75rem;padding:.7rem;font-family:inherit;font-size:.88rem;font-weight:600;color:#374151;cursor:pointer;">Cancelar</button>
                <button type="submit" id="btn-inv-submit" style="flex:2;background:linear-gradient(135deg,#1a2648,#3b82f6);border:none;border-radius:.75rem;padding:.7rem;font-family:inherit;font-size:.9rem;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;">
                    <span class="material-symbols-outlined" style="font-size:1rem;">save</span> Guardar ítem
                </button>
            </div>
        </form>
    </div>
</div>

<section id="inv-module" class="section-card p-3 p-md-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h2 class="section-title">📦 Inventario</h2>
            <p class="section-subtitle">Gestiona tus productos, insumos y materiales con costos y precios en tiempo real.</p>
        </div>
        <button class="btn btn-primary-soft" data-bs-toggle="offcanvas" data-bs-target="#sidebar-inv" onclick="resetInvForm()">
            <span class="material-symbols-outlined float-start me-2">add_circle</span> Nuevo ítem
        </button>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4" id="inv-stats">
        <div class="col-6 col-md-3">
            <div style="background:linear-gradient(135deg,#1a2648,#2d4278);border-radius:.9rem;padding:1rem;text-align:center;color:#fff;">
                <div style="font-size:1.6rem;font-weight:900;" id="stat-total">—</div>
                <div style="font-size:.72rem;opacity:.7;margin-top:.2rem;">Ítems registrados</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div style="background:linear-gradient(135deg,#15803d,#16a34a);border-radius:.9rem;padding:1rem;text-align:center;color:#fff;">
                <div style="font-size:1.6rem;font-weight:900;" id="stat-stock-ok">—</div>
                <div style="font-size:.72rem;opacity:.8;margin-top:.2rem;">Con stock disponible</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div style="background:linear-gradient(135deg,#b45309,#d97706);border-radius:.9rem;padding:1rem;text-align:center;color:#fff;">
                <div style="font-size:1.6rem;font-weight:900;" id="stat-stock-low">—</div>
                <div style="font-size:.72rem;opacity:.8;margin-top:.2rem;">Stock bajo (≤5)</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div style="background:linear-gradient(135deg,#991b1b,#dc2626);border-radius:.9rem;padding:1rem;text-align:center;color:#fff;">
                <div style="font-size:1.6rem;font-weight:900;" id="stat-stock-out">—</div>
                <div style="font-size:.72rem;opacity:.8;margin-top:.2rem;">Sin stock</div>
            </div>
        </div>
    </div>

    {{-- Búsqueda --}}
    <div class="mb-3">
        <input type="text" id="inv-search" class="form-control" placeholder="🔍 Buscar por nombre o categoría..." oninput="filterInv(this.value)">
    </div>

    {{-- Grid de cards --}}
    <div id="inv-grid" class="row g-3"></div>
</section>
@endsection

@section('scripts')
<script>
const TASA = {{ \App\Services\DollarRateService::getCachedRate() }};
let allItems = [];
let precioManual = false;

// ── Cargar inventario ──
async function loadInv() {
    await ModuleLoader.run('#inv-module', async () => {
        const res  = await fetch('/api/inventario', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() } });
        const data = await res.json();
        allItems   = data.data || [];
        renderInv(allItems);
        updateStats(allItems);
    }, { message: 'Cargando inventario...', minHeight: '320px' });
}

function updateStats(items) {
    document.getElementById('stat-total').textContent     = items.length;
    document.getElementById('stat-stock-ok').textContent  = items.filter(i => i.stock_actual > 5).length;
    document.getElementById('stat-stock-low').textContent = items.filter(i => i.stock_actual > 0 && i.stock_actual <= 5).length;
    document.getElementById('stat-stock-out').textContent = items.filter(i => i.stock_actual <= 0).length;
}

function filterInv(q) {
    const term = q.toLowerCase();
    renderInv(allItems.filter(i => i.nombre.toLowerCase().includes(term) || (i.categoria || '').toLowerCase().includes(term)));
}

function renderInv(items) {
    const grid = document.getElementById('inv-grid');
    if (!items.length) {
        grid.innerHTML = `
        <div class="col-12 text-center py-5">
            <div style="background:#f8faff;border:2px dashed #d4dded;border-radius:1.2rem;padding:3rem 2rem;">
                <span class="material-symbols-outlined" style="font-size:3.5rem;color:#94a3b8;display:block;margin-bottom:1rem;">inventory_2</span>
                <h5 style="color:#60708d;font-weight:700;margin-bottom:.4rem;">Inventario vacío</h5>
                <p style="color:#94a3b8;font-size:.88rem;margin-bottom:1.5rem;">Registra tu primer ítem de inventario para comenzar a gestionar tus productos e insumos.</p>
                <button class="btn btn-primary-soft" data-bs-toggle="offcanvas" data-bs-target="#sidebar-inv" onclick="resetInvForm()">
                    <span class="material-symbols-outlined float-start me-2">add_circle</span> Agregar primer ítem
                </button>
            </div>
        </div>`;
        return;
    }
    grid.innerHTML = items.map(item => {
        const stockClass = item.stock_actual <= 0 ? 'stock-out' : item.stock_actual <= 5 ? 'stock-low' : 'stock-ok';
        const stockIcon  = item.stock_actual <= 0 ? '⚠' : item.stock_actual <= 5 ? '↓' : '✓';
        const pvBs = item.precio_venta_bs ? `<div class="bs-tag">Bs. ${fmt(item.precio_venta_bs)}</div>` : '';
        const cuBs = item.costo_bs ? `<div class="bs-tag">Bs. ${fmt(item.costo_bs)}</div>` : '';
        const ganancia = item.costo_unitario > 0 ? ((item.precio_venta - item.costo_unitario) / item.costo_unitario * 100).toFixed(1) : 0;
        return `
        <div class="col-sm-6 col-lg-4">
            <div class="inv-card">
                ${item.foto_url ? `<img src="${item.foto_url}" style="width:100%;height:140px;object-fit:cover;">` : `<div style="width:100%;height:140px;background:linear-gradient(135deg,#1e3a5f,#2d6a9f);display:flex;align-items:center;justify-content:center;"><span class="material-symbols-outlined" style="font-size:3rem;color:rgba(255,255,255,.3);">inventory_2</span></div>`}
                <div style="padding:.85rem;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:.5rem;">
                        <div>
                            <div style="font-size:.9rem;font-weight:800;color:#1a2648;">${item.nombre}</div>
                            ${item.categoria ? `<div style="font-size:.7rem;color:#60708d;">${item.categoria}</div>` : ''}
                        </div>
                        <span class="inv-badge ${stockClass}">${stockIcon} ${item.stock_actual} ${item.unidad_medida}</span>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin:.75rem 0;padding:.65rem;background:#f8faff;border-radius:.65rem;">
                        <div>
                            <div style="font-size:.6rem;font-weight:700;color:#94a3b8;text-transform:uppercase;">COSTO</div>
                            <div class="price-tag" style="font-size:.9rem;">$${fmt(item.costo_unitario)}</div>
                            ${cuBs}
                        </div>
                        <div>
                            <div style="font-size:.6rem;font-weight:700;color:#94a3b8;text-transform:uppercase;">P. VENTA</div>
                            <div class="price-tag" style="font-size:.9rem;color:#15803d;">$${fmt(item.precio_venta)}</div>
                            ${pvBs}
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
                        <div style="font-size:.72rem;color:#60708d;">
                            <span style="background:#dcfce7;color:#15803d;border-radius:20px;padding:.15rem .5rem;font-weight:700;font-size:.68rem;">+${item.utilidad_porcentaje}% utilidad</span>
                            ${ganancia > 0 ? `<span style="margin-left:.35rem;">= +$${fmt(item.precio_venta - item.costo_unitario)} / ${item.unidad_medida}</span>` : ''}
                        </div>
                    </div>
                    <div style="display:flex;gap:.5rem;">
                        <button onclick="editInv(${item.id})" class="btn btn-sm btn-outline-soft" style="flex:1;font-size:.78rem;">
                            <span class="material-symbols-outlined" style="font-size:.85rem;">edit</span> Editar
                        </button>
                        <button onclick="deleteInv(${item.id})" class="btn btn-sm" style="flex:1;background:#fee2e2;color:#991b1b;border:1px solid #fecaca;border-radius:.6rem;font-size:.78rem;">
                            <span class="material-symbols-outlined" style="font-size:.85rem;">delete</span> Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
}

// ── Formulario ──
function resetInvForm() {
    document.getElementById('form-inv').reset();
    document.getElementById('inv-edit-id').value = '';
    document.getElementById('sidebar-inv-title').textContent = 'Nuevo ítem de inventario';
    document.getElementById('inv-foto-preview').style.display = 'none';
    document.getElementById('inv-summary').style.display = 'none';
    precioManual = false;
}

function editInv(id) {
    const item = allItems.find(i => i.id === id);
    if (!item) return;
    resetInvForm();
    document.getElementById('inv-edit-id').value = id;
    document.getElementById('sidebar-inv-title').textContent = 'Editar ítem';
    const f = document.getElementById('form-inv');
    f.querySelector('[name="nombre"]').value              = item.nombre || '';
    f.querySelector('[name="descripcion"]').value         = item.descripcion || '';
    f.querySelector('[name="categoria"]').value           = item.categoria || '';
    f.querySelector('[name="unidad_medida"]').value        = item.unidad_medida || 'unidad';
    f.querySelector('[name="costo_unitario"]').value       = item.costo_unitario || '';
    f.querySelector('[name="utilidad_porcentaje"]').value  = item.utilidad_porcentaje || 0;
    f.querySelector('[name="precio_venta"]').value         = item.precio_venta || '';
    f.querySelector('[name="notas"]').value               = item.notas || '';
    if (item.foto_url) {
        const prev = document.getElementById('inv-foto-preview');
        prev.src = item.foto_url; prev.style.display = 'block';
    }
    calcPrecio();
    const bsOff = new bootstrap.Offcanvas(document.getElementById('sidebar-inv'));
    bsOff.show();
}

async function deleteInv(id) {
    const r = await Swal.fire({ title:'¿Eliminar ítem?', text:'Esta acción no se puede deshacer.', icon:'warning', showCancelButton:true, confirmButtonText:'Eliminar', confirmButtonColor:'#dc2626' });
    if (!r.isConfirmed) return;
    await fetch(`/api/inventario/${id}`, { method:'DELETE', headers:{ 'X-CSRF-TOKEN':csrf() } });
    loadInv();
}

document.getElementById('form-inv').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-inv-submit');
    btn.disabled = true;
    const editId = document.getElementById('inv-edit-id').value;
    const fd = new FormData(this);
    if (editId) fd.append('_method', 'PUT');
    const url    = editId ? `/api/inventario/${editId}` : '/api/inventario';
    const method = editId ? 'POST' : 'POST';
    const res = await fetch(url, { method, body: fd, headers:{ 'X-CSRF-TOKEN':csrf() } });
    const data = await res.json();
    btn.disabled = false;
    if (!res.ok) { Swal.fire({ icon:'error', title:'Error', text: data.message || JSON.stringify(data.errors) }); return; }
    Swal.fire({ icon:'success', title: data.msj, timer:1400, showConfirmButton:false });
    bootstrap.Offcanvas.getInstance(document.getElementById('sidebar-inv'))?.hide();
    loadInv();
});

// ── Cálculo de precio ──
function calcPrecio() {
    const costo    = parseFloat(document.getElementById('inv-costo').value) || 0;
    const utilidad = parseFloat(document.getElementById('inv-utilidad').value) || 0;
    if (!precioManual) {
        const precio = costo * (1 + utilidad / 100);
        document.getElementById('inv-precio').value = precio > 0 ? precio.toFixed(4) : '';
    }
    updateSummary();
    document.getElementById('profit-bar').style.width = Math.min(utilidad, 100) + '%';
    if (TASA > 0) {
        document.getElementById('costo-bs-hint').textContent  = costo > 0 ? `≈ Bs. ${fmt(costo * TASA)}` : '';
    }
}
function onPrecioManual() { precioManual = true; updateSummary(); }
function updateSummary() {
    const costo    = parseFloat(document.getElementById('inv-costo').value) || 0;
    const utilidad = parseFloat(document.getElementById('inv-utilidad').value) || 0;
    const precio   = parseFloat(document.getElementById('inv-precio').value) || costo * (1 + utilidad / 100);
    if (costo <= 0) { document.getElementById('inv-summary').style.display = 'none'; return; }
    document.getElementById('inv-summary').style.display = 'block';
    document.getElementById('sum-costo').textContent     = `$${fmt(costo)}`;
    document.getElementById('sum-util').textContent      = `+${utilidad.toFixed(1)}%`;
    document.getElementById('sum-precio').textContent    = `$${fmt(precio)}`;
    if (TASA > 0) {
        document.getElementById('sum-costo-bs').textContent  = `Bs. ${fmt(costo * TASA)}`;
        document.getElementById('sum-precio-bs').textContent = `Bs. ${fmt(precio * TASA)}`;
        document.getElementById('precio-bs-hint').textContent = precio > 0 ? `≈ Bs. ${fmt(precio * TASA)}` : '';
    }
}

function previewFoto(input) {
    const file = input.files?.[0];
    const prev = document.getElementById('inv-foto-preview');
    if (file) { prev.src = URL.createObjectURL(file); prev.style.display = 'block'; }
    else { prev.style.display = 'none'; }
}

function fmt(n) { return parseFloat(n || 0).toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
function csrf() { return document.querySelector('meta[name="csrf-token"]').content; }

loadInv();
</script>
@endsection

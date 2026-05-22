@extends('layouts.app')

@section('styles')
<style>
.item-row-c { background:#fff; border:1.5px solid #e8eef8; border-radius:.85rem; padding:1rem; margin-bottom:.75rem; position:relative; transition:all .2s; }
.item-row-c:hover { border-color:#b8d4ff; }
.tipo-badge { display:inline-flex; align-items:center; gap:.3rem; font-size:.7rem; font-weight:700; border-radius:20px; padding:.2rem .65rem; }
.tipo-gallo    { background:#fef3c7; color:#92400e; }
.tipo-gallina  { background:#fce7f3; color:#9d174d; }
.tipo-inv      { background:#dbeafe; color:#1d4ed8; }
.compra-card   { background:#fff; border:1px solid #e8eef8; border-radius:1rem; padding:1rem; margin-bottom:.75rem; box-shadow:0 2px 8px rgba(16,39,77,.05); transition:all .2s; }
.compra-card:hover { box-shadow:0 6px 20px rgba(16,39,77,.1); }
.field-c { border:1.5px solid #d7dfed; border-radius:.65rem; padding:.5rem .75rem; font-size:.88rem; width:100%; outline:none; font-family:inherit; }
.field-c:focus { border-color:#8eb6ff; box-shadow:0 0 0 .18rem rgba(59,130,246,.16); }
.bs-tag-c { font-size:.68rem; color:#60708d; font-weight:500; margin-top:.2rem; display:block; line-height:1.3; }
</style>
@endsection

@section('content')
{{-- Sidebar offcanvas compras --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="sidebar-compra" style="width:min(600px,100vw);">
    <div style="background:linear-gradient(135deg,#1a2648,#2d4278);padding:1.25rem 1.5rem;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h5 style="margin:0;color:#fff;font-weight:800;font-size:1rem;">📦 Nueva compra</h5>
            <small style="color:rgba(255,255,255,.6);font-size:.72rem;">Registra la compra por proveedor</small>
        </div>
        <button data-bs-dismiss="offcanvas" style="background:rgba(255,255,255,.15);border:none;color:#fff;border-radius:50%;width:32px;height:32px;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">✕</button>
    </div>
    <div class="offcanvas-body" style="padding:1.25rem;overflow-y:auto;">
        <form id="form-compra" enctype="multipart/form-data" novalidate>
            @csrf
            {{-- Proveedor --}}
            <div style="background:#f8faff;border:1px solid #e8eef8;border-radius:.85rem;padding:1rem;margin-bottom:1rem;">
                <h6 style="font-size:.82rem;font-weight:800;color:#1a2648;margin-bottom:.75rem;display:flex;align-items:center;gap:.4rem;">
                    <span class="material-symbols-outlined" style="font-size:1rem;color:#3b82f6;">person</span> Datos del proveedor
                </h6>
                <div class="row g-2">
                    <div class="col-sm-5">
                        <label class="label-s" style="font-size:.78rem;font-weight:600;color:#374151;margin-bottom:.25rem;display:block;">Proveedor <span style="color:#ef4444">*</span></label>
                        <input type="text" class="field-c" name="proveedor_nombre" required placeholder="Nombre del proveedor">
                    </div>
                    <div class="col-sm-4">
                        <label class="label-s" style="font-size:.78rem;font-weight:600;color:#374151;margin-bottom:.25rem;display:block;">Teléfono</label>
                        <input type="text" class="field-c" name="proveedor_telefono" placeholder="0414-0000000">
                    </div>
                    <div class="col-sm-3">
                        <label class="label-s" style="font-size:.78rem;font-weight:600;color:#374151;margin-bottom:.25rem;display:block;">Fecha <span style="color:#ef4444">*</span></label>
                        <input type="date" class="field-c" name="fecha_compra" value="{{ now()->toDateString() }}" required>
                    </div>
                </div>
            </div>

            {{-- Ítems --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.35rem;">
                <span style="font-size:.85rem;font-weight:800;color:#1a2648;">Renglones de compra</span>
                <button type="button" id="btn-add-item-c" style="background:linear-gradient(135deg,#3b82f6,#6d5efc);border:none;color:#fff;border-radius:.65rem;padding:.35rem .85rem;font-size:.78rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:.3rem;">
                    <span class="material-symbols-outlined" style="font-size:.9rem;">add</span> Agregar
                </button>
            </div>
            <p class="text-muted mb-2" style="font-size:.72rem;">Los renglones tipo gallo o gallina se añaden automáticamente al módulo correspondiente.</p>
            <div id="items-compra"></div>

            <div style="margin-top:.5rem;">
                <label style="font-size:.78rem;font-weight:600;color:#374151;margin-bottom:.25rem;display:block;">Observaciones generales</label>
                <textarea class="field-c" name="observaciones" rows="2" placeholder="Notas de la compra..."></textarea>
            </div>

            {{-- Total preview --}}
            <div style="background:linear-gradient(135deg,#1a2648,#2d4278);border-radius:.85rem;padding:.85rem 1rem;margin-top:1rem;display:flex;justify-content:space-between;align-items:center;">
                <span style="color:rgba(255,255,255,.7);font-size:.82rem;font-weight:600;">Total de la compra</span>
                <div style="text-align:right;">
                    <span id="compra-total-preview" style="color:#fbbf24;font-size:1.25rem;font-weight:900;display:block;">$0.00</span>
                    <span id="compra-total-bs" style="font-size:.68rem;color:rgba(255,255,255,.65);font-weight:600;"></span>
                </div>
            </div>

            <div style="display:flex;gap:.75rem;margin-top:1rem;">
                <button type="button" data-bs-dismiss="offcanvas" style="flex:1;background:#f3f6fd;border:1.5px solid #d7dfed;border-radius:.75rem;padding:.7rem;font-family:inherit;font-size:.88rem;font-weight:600;color:#374151;cursor:pointer;">Cancelar</button>
                <button type="submit" style="flex:2;background:linear-gradient(135deg,#1a2648,#3b82f6);border:none;border-radius:.75rem;padding:.7rem;font-family:inherit;font-size:.9rem;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;">
                    <span class="material-symbols-outlined" style="font-size:1rem;">save</span> Guardar compra
                </button>
            </div>
        </form>
    </div>
</div>

<section id="compras-module" class="section-card p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="section-title">Compras</h2>
            <p class="section-subtitle">Registra compras de gallos, gallinas o ítems de inventario.</p>
        </div>
        <button class="btn btn-primary-soft" data-bs-toggle="offcanvas" data-bs-target="#sidebar-compra">
            <span class="material-symbols-outlined float-start me-2">add_circle</span> Nueva compra
        </button>
    </div>

    <input type="text" class="form-control mb-3" id="search-compras" placeholder="🔍 Buscar por proveedor o fecha..." oninput="filterCompras(this.value)">

    <div id="compras-list"></div>
</section>
@endsection

@section('scripts')
<script>
const TASA_C = {{ \App\Services\DollarRateService::getCachedRate() }};
let allCompras = [];
let itemIdx = 0;
let invItems = [];

async function init() {
    await ModuleLoader.run('#compras-module', async () => {
        const [rInv, rCompras] = await Promise.all([
            fetch('/api/inventario', { headers:{ 'Accept':'application/json', 'X-CSRF-TOKEN':csrf() } }),
            fetch('/api/compras', { headers:{ 'Accept':'application/json', 'X-CSRF-TOKEN':csrf() } }),
        ]);
        const d = await rInv.json();
        const dC = await rCompras.json();
        invItems = d.data || [];
        allCompras = dC.data || [];
        renderCompras(allCompras);
    }, { message: 'Cargando compras...', minHeight: '280px' });
    addItemRow();
}

function csrf() { return document.querySelector('meta[name="csrf-token"]').content; }
function fmt(n) { return parseFloat(n||0).toLocaleString('es-VE',{minimumFractionDigits:2,maximumFractionDigits:2}); }

async function parseApiResponse(response) {
    const text = await response.text();
    try {
        return JSON.parse(text);
    } catch {
        const snippet = text.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim().slice(0, 200);
        throw new Error(response.ok ? 'Respuesta inválida del servidor.' : (snippet || `Error ${response.status}`));
    }
}

function syncCompraRowValidation(row) {
    const tipo = row.querySelector('.tipo-sel')?.value;
    const isInv = tipo === 'inventario';
    const invSel = row.querySelector('.inv-item-sel');
    const costo = row.querySelector('.costo-input');
    const qty = row.querySelector('.qty-input');
    if (invSel) invSel.required = isInv;
    if (costo) costo.required = true;
    if (qty) qty.required = isInv;
    row.querySelectorAll('.inv-select-wrap input, .inv-select-wrap select').forEach(el => {
        if (!isInv) el.removeAttribute('required');
    });
}

function validateCompraForm() {
    const form = document.getElementById('form-compra');
    const proveedor = form.querySelector('[name="proveedor_nombre"]')?.value?.trim();
    const fecha = form.querySelector('[name="fecha_compra"]')?.value;
    if (!proveedor) {
        Swal.fire({ icon: 'warning', title: 'Proveedor requerido', text: 'Indica el nombre del proveedor.' });
        return false;
    }
    if (!fecha) {
        Swal.fire({ icon: 'warning', title: 'Fecha requerida', text: 'Selecciona la fecha de la compra.' });
        return false;
    }
    const rows = document.querySelectorAll('.item-row-c');
    if (!rows.length) {
        Swal.fire({ icon: 'warning', title: 'Sin renglones', text: 'Agrega al menos un ítem a la compra.' });
        return false;
    }
    for (const row of rows) {
        syncCompraRowValidation(row);
        const tipo = row.querySelector('.tipo-sel')?.value;
        const costo = parseFloat(row.querySelector('.costo-input')?.value);
        if (!Number.isFinite(costo) || costo < 0) {
            Swal.fire({ icon: 'warning', title: 'Costo inválido', text: 'Cada renglón debe tener un costo en USD.' });
            return false;
        }
        if (tipo === 'inventario') {
            const invId = row.querySelector('.inv-item-sel')?.value;
            const qty = parseFloat(row.querySelector('.qty-input')?.value);
            if (!invId) {
                Swal.fire({ icon: 'warning', title: 'Inventario', text: 'Selecciona el ítem de inventario en cada renglón de tipo inventario.' });
                return false;
            }
            if (!Number.isFinite(qty) || qty <= 0) {
                Swal.fire({ icon: 'warning', title: 'Cantidad', text: 'Indica una cantidad mayor a cero para inventario.' });
                return false;
            }
        }
    }
    return true;
}

function invOptions() {
    return invItems.map(i => {
        const bs = TASA_C > 0 ? ` · Bs. ${fmt(i.costo_unitario * TASA_C)}` : '';
        return `<option value="${i.id}" data-costo="${i.costo_unitario}">${i.nombre} (stock: ${i.stock_actual} ${i.unidad_medida}) — $${fmt(i.costo_unitario)}${bs}</option>`;
    }).join('');
}

function updateRowCostoBs(row) {
    const hint = row.querySelector('.costo-bs-hint');
    const lineHint = row.querySelector('.linea-bs-hint');
    if (!hint && !lineHint) return;

    const costo = parseFloat(row.querySelector('.costo-input')?.value) || 0;
    const qty = parseFloat(row.querySelector('.qty-input')?.value) || 1;
    const tipo = row.querySelector('.tipo-sel')?.value;
    const isInv = tipo === 'inventario';

    if (hint) {
        hint.textContent = (TASA_C > 0 && costo > 0) ? `≈ Bs. ${fmt(costo * TASA_C)} / ud.` : '';
    }
    if (lineHint) {
        if (TASA_C > 0 && isInv && costo > 0 && qty > 0) {
            const lineUsd = costo * qty;
            lineHint.textContent = `Línea: $${fmt(lineUsd)} ≈ Bs. ${fmt(lineUsd * TASA_C)}`;
            lineHint.style.display = '';
        } else {
            lineHint.textContent = '';
            lineHint.style.display = 'none';
        }
    }
}

function addItemRow() {
    const idx = itemIdx++;
    const row = document.createElement('div');
    row.className = 'item-row-c';
    row.dataset.idx = idx;
    row.innerHTML = `
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.65rem;">
        <span style="font-size:.78rem;font-weight:700;color:#60708d;">Rengón #${idx+1}</span>
        <button type="button" onclick="removeRow(this)" style="background:#fee2e2;border:none;color:#991b1b;border-radius:.5rem;padding:.2rem .5rem;font-size:.7rem;cursor:pointer;">✕ Quitar</button>
    </div>
    <div class="row g-2">
        <div class="col-sm-3">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Tipo <span style="color:#ef4444">*</span></label>
            <select class="field-c tipo-sel" name="items[${idx}][tipo_ave]" onchange="onTipoChange(this,${idx})" required>
                <option value="gallo">🐓 Gallo</option>
                <option value="gallina">🐔 Gallina</option>
                <option value="inventario">📦 Inventario</option>
            </select>
        </div>
        <div class="col-sm-9 inv-select-wrap" id="inv-wrap-${idx}" style="display:none;">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Ítem de inventario <span style="color:#ef4444">*</span></label>
            <select class="field-c inv-item-sel" name="items[${idx}][inventario_id]" onchange="onInvSelect(this,${idx})">
                <option value="">Selecciona ítem...</option>
                ${invOptions()}
            </select>
        </div>
        <div class="col-sm-4 ave-field" id="placa-wrap-${idx}">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Placa</label>
            <input type="text" class="field-c" name="items[${idx}][placa]" placeholder="PLC-001">
        </div>
        <div class="col-sm-5 ave-field" id="nombre-wrap-${idx}">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Nombre</label>
            <input type="text" class="field-c" name="items[${idx}][nombre]" placeholder="Nombre del ave">
        </div>
        <div class="col-sm-3 inv-qty-wrap" id="qty-wrap-${idx}" style="display:none;">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Cantidad <span style="color:#ef4444">*</span></label>
            <input type="number" class="field-c qty-input" name="items[${idx}][cantidad]" min="0.01" step="0.01" value="1" oninput="recalcTotal()">
        </div>
        <div class="col-sm-3">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Costo ($) <span style="color:#ef4444">*</span></label>
            <input type="number" class="field-c costo-input" name="items[${idx}][costo]" min="0" step="0.01" placeholder="0.00" oninput="recalcTotal()" required>
            <small class="costo-bs-hint bs-tag-c"></small>
            <small class="linea-bs-hint bs-tag-c" style="display:none;color:#3b82f6;"></small>
        </div>
        <div class="col-sm-4 ave-field" id="color-wrap-${idx}">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Color</label>
            <input type="text" class="field-c" name="items[${idx}][color]" placeholder="Rojo, giro...">
        </div>
        <div class="col-12">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Observaciones</label>
            <input type="text" class="field-c" name="items[${idx}][observaciones]" placeholder="Nota adicional...">
        </div>
    </div>`;
    document.getElementById('items-compra').appendChild(row);
    syncCompraRowValidation(row);
    updateRowCostoBs(row);
}

function onTipoChange(sel, idx) {
    const isInv = sel.value === 'inventario';
    document.getElementById(`inv-wrap-${idx}`).style.display     = isInv ? '' : 'none';
    document.getElementById(`qty-wrap-${idx}`).style.display     = isInv ? '' : 'none';
    document.getElementById(`placa-wrap-${idx}`).style.display   = isInv ? 'none' : '';
    document.getElementById(`nombre-wrap-${idx}`).style.display  = isInv ? 'none' : '';
    document.getElementById(`color-wrap-${idx}`).style.display   = isInv ? 'none' : '';
    const row = sel.closest('.item-row-c');
    syncCompraRowValidation(row);
    updateRowCostoBs(row);
}

function onInvSelect(sel, idx) {
    const opt = sel.options[sel.selectedIndex];
    const costo = parseFloat(opt.dataset.costo) || 0;
    const costoInput = document.querySelector(`.item-row-c[data-idx="${idx}"] .costo-input`);
    if (costoInput) { costoInput.value = costo.toFixed(4); recalcTotal(); }
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.item-row-c');
    if (rows.length <= 1) return;
    btn.closest('.item-row-c').remove();
    recalcTotal();
}

function recalcTotal() {
    let total = 0;
    document.querySelectorAll('.item-row-c').forEach(row => {
        const costo = parseFloat(row.querySelector('.costo-input')?.value) || 0;
        const qty   = parseFloat(row.querySelector('.qty-input')?.value)   || 1;
        const tipo  = row.querySelector('.tipo-sel')?.value;
        total += tipo === 'inventario' ? costo * qty : costo;
        updateRowCostoBs(row);
    });
    document.getElementById('compra-total-preview').textContent = `$${fmt(total)}`;
    const bsEl = document.getElementById('compra-total-bs');
    if (bsEl) bsEl.textContent = TASA_C > 0 ? `Bs. ${fmt(total * TASA_C)}` : '';
}

document.getElementById('btn-add-item-c').addEventListener('click', addItemRow);

document.getElementById('form-compra').addEventListener('submit', async function(e) {
    e.preventDefault();
    if (!validateCompraForm()) return;

    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) submitBtn.disabled = true;

    Swal.fire({ title: 'Guardando compra...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
        const fd = new FormData(form);
        const res = await fetch('/api/compras', {
            method: 'POST',
            body: fd,
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json',
            },
        });
        const data = await parseApiResponse(res);
        if (!res.ok) {
            const errText = data.message || data.msj
                || (data.errors ? Object.values(data.errors).flat().join('\n') : null)
                || 'No se pudo guardar la compra.';
            Swal.fire({ icon: 'error', title: 'Error', text: errText });
            return;
        }
        Swal.fire({ icon: 'success', title: data.msj || 'Compra registrada', timer: 1500, showConfirmButton: false });
        form.reset();
        document.getElementById('items-compra').innerHTML = '';
        itemIdx = 0;
        addItemRow();
        recalcTotal();
        bootstrap.Offcanvas.getInstance(document.getElementById('sidebar-compra'))?.hide();
        loadCompras();
    } catch (err) {
        Swal.fire({ icon: 'error', title: 'Error', text: err.message || 'Error de conexión al guardar.' });
    } finally {
        if (submitBtn) submitBtn.disabled = false;
    }
});

async function loadCompras() {
    await ModuleLoader.run('#compras-module', async () => {
        const res = await fetch('/api/compras', { headers:{ 'Accept':'application/json', 'X-CSRF-TOKEN':csrf() } });
        const d = await res.json();
        allCompras = d.data || [];
        renderCompras(allCompras);
    }, { message: 'Actualizando compras...', minHeight: '240px' });
}

function filterCompras(q) {
    const t = q.toLowerCase();
    renderCompras(allCompras.filter(c => (c.proveedor?.name||'').toLowerCase().includes(t) || (c.fecha_compra||'').includes(t)));
}

function renderCompras(rows) {
    const el = document.getElementById('compras-list');
    if (!rows.length) {
        el.innerHTML = `<div style="text-align:center;padding:3rem 1rem;background:#f8faff;border:2px dashed #d4dded;border-radius:1.2rem;">
            <span class="material-symbols-outlined" style="font-size:3rem;color:#94a3b8;display:block;margin-bottom:.75rem;">shopping_cart</span>
            <h5 style="color:#60708d;font-weight:700;">Sin compras registradas</h5>
            <p style="color:#94a3b8;font-size:.88rem;">Registra tu primera compra haciendo clic en "Nueva compra".</p>
        </div>`;
        return;
    }
    el.innerHTML = rows.map(c => {
        const items = (c.items||[]).map(it => {
            const tipo = it.tipo_ave === 'inventario' ? `<span class="tipo-badge tipo-inv">📦 ${it.nombre||'Inventario'}</span>` :
                         it.tipo_ave === 'gallina'    ? `<span class="tipo-badge tipo-gallina">🐔 ${it.placa||''}</span>` :
                                                         `<span class="tipo-badge tipo-gallo">🐓 ${it.placa||''}</span>`;
            return `<div style="display:flex;align-items:center;justify-content:space-between;font-size:.78rem;padding:.3rem 0;border-bottom:1px solid #f1f5fb;">${tipo}<span style="font-weight:700;color:#1a2648;">$${fmt(it.costo)}</span></div>`;
        }).join('');
        return `<div class="compra-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:.5rem;margin-bottom:.65rem;">
                <div>
                    <div style="font-weight:800;color:#1a2648;font-size:.95rem;">${c.proveedor?.name||'Sin proveedor'}</div>
                    <div style="font-size:.72rem;color:#60708d;">${c.fecha_compra||''} · ${(c.items||[]).length} ítem(s)</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:1.1rem;font-weight:900;color:#1d4ed8;">$${fmt(c.total)}</div>
                    ${TASA_C > 0 ? `<div style="font-size:.68rem;color:#60708d;">Bs. ${fmt(c.total * TASA_C)}</div>` : ''}
                </div>
            </div>
            <div>${items}</div>
            <div style="display:flex;justify-content:flex-end;margin-top:.65rem;">
                <button onclick="deleteCompra(${c.id})" style="background:#fee2e2;border:1px solid #fecaca;color:#991b1b;border-radius:.6rem;padding:.3rem .75rem;font-size:.75rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:.3rem;">
                    <span class="material-symbols-outlined" style="font-size:.9rem;">delete</span> Eliminar
                </button>
            </div>
        </div>`;
    }).join('');
}

async function deleteCompra(id) {
    const r = await Swal.fire({ title:'¿Eliminar compra?', icon:'warning', showCancelButton:true, confirmButtonText:'Eliminar', confirmButtonColor:'#dc2626' });
    if (!r.isConfirmed) return;
    await fetch(`/api/compras/${id}`, { method:'DELETE', headers:{ 'X-CSRF-TOKEN':csrf() } });
    loadCompras();
}

init();
</script>
@endsection

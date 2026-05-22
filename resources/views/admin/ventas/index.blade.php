@extends('layouts.app')

@section('styles')
<style>
.venta-card { background:#fff; border:1px solid #e8eef8; border-radius:1rem; padding:1rem; margin-bottom:.75rem; box-shadow:0 2px 8px rgba(16,39,77,.05); transition:all .2s; }
.venta-card:hover { box-shadow:0 6px 20px rgba(16,39,77,.1); }
.field-v { border:1.5px solid #d7dfed; border-radius:.65rem; padding:.5rem .75rem; font-size:.88rem; width:100%; outline:none; font-family:inherit; }
.field-v:focus { border-color:#8eb6ff; box-shadow:0 0 0 .18rem rgba(59,130,246,.16); }
.item-row-v { background:#fff; border:1.5px solid #e8eef8; border-radius:.85rem; padding:1rem; margin-bottom:.75rem; }
.stat-ingreso { background:linear-gradient(135deg,#15803d,#16a34a); border-radius:.9rem; padding:1rem; color:#fff; text-align:center; }
</style>
@endsection

@section('content')
{{-- Sidebar Ventas --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="sidebar-venta" style="width:min(600px,100vw);">
    <div style="background:linear-gradient(135deg,#15803d,#16a34a);padding:1.25rem 1.5rem;display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h5 style="margin:0;color:#fff;font-weight:800;font-size:1rem;">💰 Nueva venta</h5>
            <small style="color:rgba(255,255,255,.7);font-size:.72rem;">Registra gallos, gallinas o inventario</small>
        </div>
        <button data-bs-dismiss="offcanvas" style="background:rgba(255,255,255,.2);border:none;color:#fff;border-radius:50%;width:32px;height:32px;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">✕</button>
    </div>
    <div class="offcanvas-body" style="padding:1.25rem;overflow-y:auto;">
        <form id="form-venta-masiva">
            @csrf
            <div style="background:#f8faff;border:1px solid #e8eef8;border-radius:.85rem;padding:1rem;margin-bottom:1rem;">
                <h6 style="font-size:.82rem;font-weight:800;color:#1a2648;margin-bottom:.75rem;display:flex;align-items:center;gap:.4rem;">
                    <span class="material-symbols-outlined" style="font-size:1rem;color:#16a34a;">person</span> Datos del cliente
                </h6>
                <div class="row g-2">
                    <div class="col-sm-5">
                        <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Cliente <span style="color:#ef4444">*</span></label>
                        <input type="text" class="field-v" name="nombre_cliente" required placeholder="Nombre del cliente">
                    </div>
                    <div class="col-sm-3">
                        <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Teléfono</label>
                        <input type="text" class="field-v" name="telefono" placeholder="0414-...">
                    </div>
                    <div class="col-sm-4">
                        <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Fecha <span style="color:#ef4444">*</span></label>
                        <input type="date" class="field-v" name="fecha" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="col-sm-4">
                        <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Tipo de venta</label>
                        <select class="field-v" name="tipo_venta">
                            <option value="directa">Directa</option>
                            <option value="marketplace">Marketplace</option>
                        </select>
                    </div>
                    <div class="col-sm-8">
                        <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Observaciones</label>
                        <input type="text" class="field-v" name="observaciones_global" placeholder="Nota general...">
                    </div>
                </div>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.65rem;">
                <span style="font-size:.85rem;font-weight:800;color:#1a2648;">Renglones de venta</span>
                <button type="button" id="btn-add-sale" style="background:linear-gradient(135deg,#15803d,#16a34a);border:none;color:#fff;border-radius:.65rem;padding:.35rem .85rem;font-size:.78rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:.3rem;">
                    <span class="material-symbols-outlined" style="font-size:.9rem;">add</span> Agregar
                </button>
            </div>
            <div id="items-venta"></div>

            <div style="background:linear-gradient(135deg,#15803d,#16a34a);border-radius:.85rem;padding:.85rem 1rem;margin-top:1rem;display:flex;justify-content:space-between;align-items:center;">
                <span style="color:rgba(255,255,255,.8);font-size:.82rem;font-weight:600;">Total de la venta</span>
                <div style="text-align:right;">
                    <div id="venta-total-preview" style="color:#fff;font-size:1.2rem;font-weight:900;">$0.00</div>
                    <div id="venta-total-bs" style="color:rgba(255,255,255,.7);font-size:.72rem;"></div>
                </div>
            </div>

            <div style="display:flex;gap:.75rem;margin-top:1rem;">
                <button type="button" data-bs-dismiss="offcanvas" style="flex:1;background:#f3f6fd;border:1.5px solid #d7dfed;border-radius:.75rem;padding:.7rem;font-family:inherit;font-size:.88rem;font-weight:600;color:#374151;cursor:pointer;">Cancelar</button>
                <button type="submit" style="flex:2;background:linear-gradient(135deg,#15803d,#16a34a);border:none;border-radius:.75rem;padding:.7rem;font-family:inherit;font-size:.9rem;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;">
                    <span class="material-symbols-outlined" style="font-size:1rem;">save</span> Guardar ventas
                </button>
            </div>
        </form>
    </div>
</div>

<section id="ventas-module" class="section-card p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="section-title">Ventas</h2>
            <p class="section-subtitle">Registra ventas de gallos, gallinas e ítems de inventario.</p>
        </div>
        <button class="btn btn-primary-soft" style="background:linear-gradient(135deg,#15803d,#16a34a);border:none;" data-bs-toggle="offcanvas" data-bs-target="#sidebar-venta">
            <span class="material-symbols-outlined float-start me-2">add_circle</span> Nueva venta
        </button>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-ingreso">
                <div style="font-size:1.4rem;font-weight:900;" id="stat-total-usd">$0.00</div>
                <div style="font-size:.7rem;opacity:.8;">Total ingresos USD</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div style="background:linear-gradient(135deg,#1a2648,#2d4278);border-radius:.9rem;padding:1rem;text-align:center;color:#fff;">
                <div style="font-size:1.4rem;font-weight:900;" id="stat-total-bs">—</div>
                <div style="font-size:.7rem;opacity:.7;">Total ingresos Bs.</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div style="background:#fff;border:1px solid #e8eef8;border-radius:.9rem;padding:1rem;text-align:center;">
                <div style="font-size:1.4rem;font-weight:900;color:#1a2648;" id="stat-count">0</div>
                <div style="font-size:.7rem;color:#60708d;">Ventas registradas</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div style="background:#fff;border:1px solid #e8eef8;border-radius:.9rem;padding:1rem;text-align:center;">
                <div style="font-size:1.4rem;font-weight:900;color:#16a34a;" id="stat-inv-count">0</div>
                <div style="font-size:.7rem;color:#60708d;">Ventas de inventario</div>
            </div>
        </div>
    </div>

    <input type="text" class="form-control mb-3" placeholder="🔍 Buscar por cliente, monto o fecha..." oninput="filterVentas(this.value)">
    <div id="ventas-list"></div>
</section>
@endsection

@section('scripts')
<script>
const TASA_V = {{ \App\Services\DollarRateService::getCachedRate() }};
let allVentas = [];
let gallos = [];
let invItems = [];
let saleIdx = 0;

async function initVentas() {
    await ModuleLoader.run('#ventas-module', async () => {
        const [rGallos, rInv, rVentas] = await Promise.all([
            fetch('/api/gallos', { headers:{ 'Accept':'application/json','X-CSRF-TOKEN':csrf() } }),
            fetch('/api/inventario', { headers:{ 'Accept':'application/json','X-CSRF-TOKEN':csrf() } }),
            fetch('/api/ventas', { headers:{ 'Accept':'application/json','X-CSRF-TOKEN':csrf() } }),
        ]);
        const dG = await rGallos.json();
        const dI = await rInv.json();
        const dV = await rVentas.json();
        gallos   = (dG.data||[]).filter(g => g.estatus !== 'Vendido' && g.estatus !== 'Fallecido');
        invItems = dI.data || [];
        allVentas = dV.data || [];
        renderVentas(allVentas);
    }, { message: 'Cargando ventas...', minHeight: '320px' });
    addSaleRow();
}

function csrf() { return document.querySelector('meta[name="csrf-token"]').content; }
function fmt(n) { return parseFloat(n||0).toLocaleString('es-VE',{minimumFractionDigits:2,maximumFractionDigits:2}); }

function gallosOptions() { return gallos.map(g => `<option value="${g.id}" data-precio="${g.precio_venta||0}">${g.placa} — ${g.nombre||'Sin nombre'} (${g.estatus})</option>`).join(''); }
function invOptions()    { return invItems.map(i => `<option value="${i.id}" data-precio="${i.precio_venta}" data-stock="${i.stock_actual}" data-um="${i.unidad_medida}">📦 ${i.nombre} | stock: ${i.stock_actual} ${i.unidad_medida} | $${fmt(i.precio_venta)}</option>`).join(''); }

function addSaleRow() {
    const idx = saleIdx++;
    const row = document.createElement('div');
    row.className = 'item-row-v';
    row.dataset.idx = idx;
    row.innerHTML = `
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.6rem;">
        <span style="font-size:.78rem;font-weight:700;color:#60708d;">Rengón #${idx+1}</span>
        <button type="button" onclick="removeSaleRow(this)" style="background:#fee2e2;border:none;color:#991b1b;border-radius:.5rem;padding:.2rem .5rem;font-size:.7rem;cursor:pointer;">✕</button>
    </div>
    <div class="row g-2">
        <div class="col-sm-4">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Tipo <span style="color:#ef4444">*</span></label>
            <select class="field-v tipo-sel-v" name="tipo_item_${idx}" onchange="onSaleTipo(this,${idx})">
                <option value="gallo">🐓 Gallo</option>
                <option value="gallina">🐔 Gallina (manual)</option>
                <option value="inventario">📦 Inventario</option>
            </select>
        </div>
        <div class="col-sm-8" id="sv-gallo-wrap-${idx}">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Gallo <span style="color:#ef4444">*</span></label>
            <select class="field-v gallo-select" name="items[${idx}][gallo_id]">
                <option value="">Selecciona gallo...</option>
                ${gallosOptions()}
            </select>
        </div>
        <div class="col-sm-8" id="sv-inv-wrap-${idx}" style="display:none;">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Ítem de inventario <span style="color:#ef4444">*</span></label>
            <select class="field-v inv-sel-v" name="items[${idx}][inventario_id]" onchange="onSaleInvSelect(this,${idx})">
                <option value="">Selecciona ítem...</option>
                ${invOptions()}
            </select>
        </div>
        <div class="col-sm-4 sv-qty-wrap" id="sv-qty-wrap-${idx}" style="display:none;">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Cantidad <span style="color:#ef4444">*</span></label>
            <input type="number" class="field-v qty-sel-v" name="items[${idx}][cantidad]" min="0.01" step="0.01" value="1" oninput="recalcVentaTotal()">
        </div>
        <div class="col-sm-4">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Monto ($) <span style="color:#ef4444">*</span></label>
            <input type="number" class="field-v monto-sel-v" name="items[${idx}][monto]" min="0" step="0.01" placeholder="0.00" oninput="recalcVentaTotal()" required>
        </div>
        <div class="col-sm-4 sv-gallina-desc" id="sv-gallina-${idx}" style="display:none;">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Descripción</label>
            <input type="text" class="field-v" name="items[${idx}][desc_gallina]" placeholder="Placa/nombre gallina...">
        </div>
        <div style="grid-column:1/-1;">
            <div id="sv-inv-stock-info-${idx}" style="display:none;font-size:.72rem;padding:.35rem .65rem;background:#eff6ff;border-radius:.5rem;color:#1d4ed8;"></div>
        </div>
        <div class="col-12">
            <label style="font-size:.75rem;font-weight:600;color:#374151;margin-bottom:.2rem;display:block;">Observación</label>
            <input type="text" class="field-v" name="items[${idx}][observaciones]" placeholder="Nota del rengón...">
        </div>
    </div>`;
    document.getElementById('items-venta').appendChild(row);
    // TomSelect for gallo
    const sel = row.querySelector('.gallo-select');
    if (window.TomSelect && sel) new TomSelect(sel, { create:false, allowEmptyOption:true, placeholder:'Busca por placa...' });
}

function onSaleTipo(sel, idx) {
    const tipo = sel.value;
    document.getElementById(`sv-gallo-wrap-${idx}`).style.display   = tipo === 'gallo' ? '' : 'none';
    document.getElementById(`sv-inv-wrap-${idx}`).style.display     = tipo === 'inventario' ? '' : 'none';
    document.getElementById(`sv-qty-wrap-${idx}`).style.display     = tipo === 'inventario' ? '' : 'none';
    document.getElementById(`sv-gallina-${idx}`).style.display      = tipo === 'gallina' ? '' : 'none';
    document.getElementById(`sv-inv-stock-info-${idx}`).style.display = 'none';
}

function onSaleInvSelect(sel, idx) {
    const opt = sel.options[sel.selectedIndex];
    if (!opt.value) return;
    const precio = parseFloat(opt.dataset.precio) || 0;
    const stock  = parseFloat(opt.dataset.stock) || 0;
    const um     = opt.dataset.um || 'unidad';
    const montoInput = document.querySelector(`.item-row-v[data-idx="${idx}"] .monto-sel-v`);
    const qtyInput   = document.querySelector(`.item-row-v[data-idx="${idx}"] .qty-sel-v`);
    if (montoInput) { montoInput.value = precio.toFixed(2); recalcVentaTotal(); }
    const info = document.getElementById(`sv-inv-stock-info-${idx}`);
    if (info) {
        info.style.display = '';
        info.innerHTML = `📦 Stock disponible: <strong>${stock} ${um}</strong> · Precio unitario: <strong>$${fmt(precio)}</strong>${TASA_V > 0 ? ` = <strong>Bs. ${fmt(precio * TASA_V)}</strong>` : ''}`;
        if (stock <= 0) info.style.background = '#fee2e2', info.style.color = '#991b1b';
        else if (stock <= 5) info.style.background = '#fef3c7', info.style.color = '#92400e';
        else info.style.background = '#eff6ff', info.style.color = '#1d4ed8';
    }
    // Actualizar monto si hay qty
    if (qtyInput) {
        qtyInput.addEventListener('input', () => {
            const qty = parseFloat(qtyInput.value) || 1;
            if (montoInput) { montoInput.value = (precio * qty).toFixed(2); recalcVentaTotal(); }
        });
    }
}

function removeSaleRow(btn) {
    const rows = document.querySelectorAll('.item-row-v');
    if (rows.length <= 1) return;
    btn.closest('.item-row-v').remove();
    recalcVentaTotal();
}

function recalcVentaTotal() {
    let total = 0;
    document.querySelectorAll('.item-row-v').forEach(row => {
        total += parseFloat(row.querySelector('.monto-sel-v')?.value) || 0;
    });
    document.getElementById('venta-total-preview').textContent = `$${fmt(total)}`;
    document.getElementById('venta-total-bs').textContent = TASA_V > 0 ? `Bs. ${fmt(total * TASA_V)}` : '';
}

document.getElementById('btn-add-sale').addEventListener('click', addSaleRow);

document.getElementById('form-venta-masiva').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const base = {
        nombre_cliente: formData.get('nombre_cliente'),
        telefono:       formData.get('telefono') || '',
        fecha:          formData.get('fecha'),
        tipo_venta:     formData.get('tipo_venta') || 'directa',
        observaciones_global: formData.get('observaciones_global') || '',
    };
    const rows = document.querySelectorAll('.item-row-v');
    if (!rows.length) { Swal.fire({ icon:'warning', text:'Agrega al menos un rengón.' }); return; }
    Swal.fire({ title:'Procesando...', allowOutsideClick:false, didOpen:()=>Swal.showLoading() });
    try {
        for (const row of rows) {
            const idx  = row.dataset.idx;
            const tipo = row.querySelector('.tipo-sel-v').value;
            const monto = row.querySelector('.monto-sel-v').value;
            if (!monto) throw new Error('Ingresa el monto en todos los renglones.');
            const payload = new FormData();
            payload.append('nombre_cliente', base.nombre_cliente);
            payload.append('telefono',       base.telefono);
            payload.append('fecha',          base.fecha);
            payload.append('tipo_venta',     base.tipo_venta);
            payload.append('tipo_item',      tipo);
            payload.append('monto',          monto);
            payload.append('observaciones',  [base.observaciones_global, row.querySelector(`[name="items[${idx}][observaciones]"]`)?.value].filter(Boolean).join(' | '));
            if (tipo === 'gallo') {
                const galloId = row.querySelector('.gallo-select')?.value;
                if (!galloId) throw new Error('Selecciona un gallo.');
                payload.append('gallo_id', galloId);
            } else if (tipo === 'inventario') {
                const invId = row.querySelector('.inv-sel-v')?.value;
                const qty   = row.querySelector('.qty-sel-v')?.value || 1;
                if (!invId) throw new Error('Selecciona un ítem de inventario.');
                payload.append('inventario_id', invId);
                payload.append('cantidad',      qty);
            }
            const res = await fetch('/api/ventas', { method:'POST', body:payload, headers:{ 'X-CSRF-TOKEN':csrf() } });
            const result = await res.json();
            if (!res.ok) throw new Error(result.message || result.msj || 'Error al registrar venta.');
        }
        Swal.fire({ icon:'success', title:'Ventas registradas', timer:1400, showConfirmButton:false });
        this.reset();
        document.getElementById('items-venta').innerHTML = '';
        saleIdx = 0;
        addSaleRow();
        bootstrap.Offcanvas.getInstance(document.getElementById('sidebar-venta'))?.hide();
        loadVentas();
    } catch(err) {
        Swal.fire({ icon:'error', title:'Error', text: err.message });
    }
});

async function loadVentas() {
    await ModuleLoader.run('#ventas-module', async () => {
        const res = await fetch('/api/ventas', { headers:{ 'Accept':'application/json','X-CSRF-TOKEN':csrf() } });
        const d = await res.json();
        allVentas = d.data || [];
        renderVentas(allVentas);
    }, { message: 'Actualizando ventas...', minHeight: '240px' });
}

function filterVentas(q) {
    const t = q.toLowerCase();
    renderVentas(allVentas.filter(v => (v.nombre_cliente||'').toLowerCase().includes(t) || (v.fecha||v.created_at||'').includes(t) || String(v.monto||v.precio||0).includes(t)));
}

function renderVentas(rows) {
    const el = document.getElementById('ventas-list');
    let totalUsd = 0, invCount = 0;
    rows.forEach(v => { totalUsd += parseFloat(v.monto||v.precio||0); if (v.tipo_item === 'inventario') invCount++; });
    document.getElementById('stat-total-usd').textContent  = `$${fmt(totalUsd)}`;
    document.getElementById('stat-total-bs').textContent   = TASA_V > 0 ? `Bs. ${fmt(totalUsd * TASA_V)}` : '—';
    document.getElementById('stat-count').textContent      = rows.length;
    document.getElementById('stat-inv-count').textContent  = invCount;

    if (!rows.length) {
        el.innerHTML = `<div style="text-align:center;padding:3rem 1rem;background:#f8faff;border:2px dashed #d4dded;border-radius:1.2rem;">
            <span class="material-symbols-outlined" style="font-size:3rem;color:#94a3b8;display:block;margin-bottom:.75rem;">point_of_sale</span>
            <h5 style="color:#60708d;font-weight:700;">Sin ventas registradas</h5>
            <p style="color:#94a3b8;font-size:.88rem;">Registra tu primera venta para comenzar a ver tus ingresos.</p>
        </div>`;
        return;
    }

    el.innerHTML = rows.map(v => {
        const monto = parseFloat(v.monto||v.precio||0);
        const tipoLabel = v.tipo_item === 'inventario' ? '📦 Inventario' : v.tipo_item === 'gallina' ? '🐔 Gallina' : '🐓 Gallo';
        const tipoColor = v.tipo_item === 'inventario' ? '#1d4ed8' : v.tipo_item === 'gallina' ? '#9d174d' : '#92400e';
        const tipoBg    = v.tipo_item === 'inventario' ? '#dbeafe' : v.tipo_item === 'gallina' ? '#fce7f3' : '#fef3c7';
        const articulo  = v.inventario?.nombre || v.gallo?.placa || `Gallo #${v.gallo_id||'?'}`;
        return `<div class="venta-card">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:.5rem;">
                <div>
                    <div style="font-weight:800;color:#1a2648;font-size:.95rem;">${v.nombre_cliente||'Sin cliente'}</div>
                    <div style="font-size:.72rem;color:#60708d;margin-top:.15rem;">
                        ${moment(v.fecha||v.created_at).format('DD/MM/YYYY')} · 
                        <span style="background:${tipoBg};color:${tipoColor};border-radius:20px;padding:.1rem .5rem;font-weight:700;font-size:.68rem;">${tipoLabel}</span>
                        ${v.cantidad && v.tipo_item==='inventario' ? `× ${v.cantidad}` : ''} — ${articulo}
                    </div>
                    ${v.observaciones ? `<div style="font-size:.72rem;color:#94a3b8;margin-top:.1rem;">${v.observaciones}</div>` : ''}
                </div>
                <div style="text-align:right;">
                    <div style="font-size:1.1rem;font-weight:900;color:#15803d;">$${fmt(monto)}</div>
                    ${TASA_V > 0 ? `<div style="font-size:.68rem;color:#60708d;">Bs. ${fmt(monto * TASA_V)}</div>` : ''}
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;margin-top:.65rem;">
                <button onclick="deleteVenta(${v.id})" style="background:#fee2e2;border:1px solid #fecaca;color:#991b1b;border-radius:.6rem;padding:.3rem .75rem;font-size:.75rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:.3rem;">
                    <span class="material-symbols-outlined" style="font-size:.9rem;">delete</span> Eliminar
                </button>
            </div>
        </div>`;
    }).join('');
}

async function deleteVenta(id) {
    const r = await Swal.fire({ title:'¿Eliminar venta?', icon:'warning', showCancelButton:true, confirmButtonText:'Eliminar', confirmButtonColor:'#dc2626' });
    if (!r.isConfirmed) return;
    const res = await fetch(`/api/ventas/${id}`, { method:'DELETE', headers:{ 'X-CSRF-TOKEN':csrf() } });
    const d = await res.json();
    Swal.fire({ icon:'success', title: d.msj||'Eliminada', timer:1200, showConfirmButton:false });
    loadVentas();
}

initVentas();
</script>
@endsection

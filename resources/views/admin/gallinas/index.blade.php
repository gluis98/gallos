@extends('layouts.app')

@section('styles')
<style>
    .ave-card {
        background: #fff; border: 1px solid #e8eef8; border-radius: 1.2rem;
        overflow: hidden; box-shadow: 0 4px 18px rgba(16,39,77,.07);
        transition: transform .22s ease, box-shadow .22s ease; height: 100%;
    }
    .ave-card:hover { transform: translateY(-5px); box-shadow: 0 16px 40px rgba(16,39,77,.14); }
    .ave-photo-wrap { position: relative; height: 210px; overflow: hidden; background: #f9f0ff; }
    .ave-photo-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform .35s ease; }
    .ave-card:hover .ave-photo-wrap img { transform: scale(1.05); }
    .ave-photo-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(10,20,50,.65) 0%, transparent 55%); }
    .ave-status-chip { position: absolute; top: 10px; left: 10px; font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; padding: .22rem .7rem; border-radius: 20px; backdrop-filter: blur(6px); }
    .sc-activa    { background: rgba(124,58,237,.85); color: #fff; }
    .sc-inactiva  { background: rgba(107,114,128,.8);  color: #fff; }
    .sc-vendido   { background: rgba(29,78,216,.85);   color: #fff; }
    .sc-fallecida { background: rgba(153,27,27,.85);   color: #fff; }
    .ave-color-pip { position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; border-radius: 50%; border: 2.5px solid rgba(255,255,255,.85); box-shadow: 0 2px 8px rgba(0,0,0,.25); }
    .ave-photo-bottom { position: absolute; bottom: 10px; left: 12px; right: 12px; display: flex; align-items: flex-end; justify-content: space-between; }
    .ave-placa-overlay { font-size: 1.15rem; font-weight: 800; color: #fff; text-shadow: 0 2px 8px rgba(0,0,0,.5); line-height: 1.15; }
    .ave-nombre-overlay { font-size: .78rem; color: rgba(255,255,255,.8); text-shadow: 0 1px 4px rgba(0,0,0,.4); }
    .ave-card-body { padding: .9rem 1rem .6rem; }
    .ave-info-row { display: flex; align-items: center; gap: .4rem; font-size: .78rem; color: #4a5568; padding: .25rem 0; border-bottom: 1px solid #f3f6fc; }
    .ave-info-row:last-child { border-bottom: none; }
    .ave-info-row .material-symbols-outlined { font-size: .9rem; color: #9baac7; flex-shrink: 0; }
    .ave-info-val { font-weight: 600; color: #1a2648; margin-left: auto; }
    .ave-actions-bar { display: flex; align-items: center; justify-content: flex-end; gap: .2rem; padding: .55rem .85rem; border-top: 1px solid #f0f4fc; background: #fdf9ff; }
    .ave-btn { width: 34px; height: 34px; border-radius: .6rem; border: 1px solid #e8eef8; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .15s; color: #60708d; text-decoration: none; padding: 0; }
    .ave-btn .material-symbols-outlined { font-size: 1.05rem; }
    .ave-btn:hover { background: #f5f3ff; border-color: #c4b5fd; color: #7c3aed; }
    .ave-btn.danger:hover  { background: #fff1f2; border-color: #fca5a5; color: #dc2626; }
    .ave-btn.success:hover { background: #f0fdf4; border-color: #86efac; color: #16a34a; }
    #sidebar-gallina { width: min(520px, 100vw); }
    .sidebar-header-purple { background: linear-gradient(135deg, #4c1d95 0%, #6d28d9 100%); padding: 1.25rem 1.5rem; }
    .sidebar-header-purple .offcanvas-title { color: #fff; font-weight: 700; font-size: 1.1rem; }
    .sidebar-header-purple small { color: rgba(255,255,255,.65); font-size: .78rem; }
    .sidebar-section { border: 1px solid #ede9fe; border-radius: .85rem; padding: 1rem 1.1rem; margin-bottom: 1rem; background: #fdf9ff; }
    .sidebar-section-title { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #7c3aed; margin-bottom: .75rem; display: flex; align-items: center; gap: .4rem; }
    .sidebar-section-title .material-symbols-outlined { font-size: .95rem; }
    .form-label-sm { font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }
    .required-star { color: #ef4444; }
    #sidebar-gallina .form-control, #sidebar-gallina .form-select { font-size: .88rem; border-radius: .65rem; }
</style>
@endsection

@section('content')

{{-- ═══ SIDEBAR: Registrar/Editar Gallina ═══ --}}
<div class="offcanvas offcanvas-end" id="sidebar-gallina" tabindex="-1" data-bs-scroll="true">
    <div class="offcanvas-header sidebar-header-purple">
        <div>
            <h5 class="offcanvas-title" id="sidebar-gallina-title">🥚 Registrar Gallina</h5>
            <small>Completa los datos del animal</small>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body">
        <form id="form-gallo">
            @csrf

            <div class="sidebar-section">
                <div class="sidebar-section-title"><span class="material-symbols-outlined">family_restroom</span> Genealogía</div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label-sm">Padre</label>
                        <select class="form-control form-select-sm" name="padre_id" id="padre_id"></select>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">Madre</label>
                        <select class="form-control form-select-sm" name="madre_id" id="madre_id"></select>
                    </div>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title"><span class="material-symbols-outlined">badge</span> Identificación</div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label-sm">Placa <span class="required-star">*</span></label>
                        <input type="text" class="form-control" name="placa" id="placa" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre">
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">Marca nacimiento <span class="required-star">*</span></label>
                        <input type="text" class="form-control" name="marca_nacimiento" id="marca_nacimiento" required>
                    </div>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title"><span class="material-symbols-outlined">palette</span> Características físicas</div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label-sm">Color <span class="required-star">*</span></label>
                        <select class="form-control" name="color" id="color" required>
                            <option value="Zambo">Zambo</option>
                            <option value="Melao">Melao</option>
                            <option value="Giro">Giro</option>
                            <option value="Marañon">Marañon</option>
                            <option value="Calica">Calica</option>
                            <option value="Pinto">Pinto</option>
                            <option value="Jabao">Jabao</option>
                            <option value="Camagüey">Camagüey</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">Color alternativo</label>
                        <input type="text" class="form-control" name="color_alternativo" id="color_alternativo">
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">Cresta <span class="required-star">*</span></label>
                        <select class="form-control" name="cresta" id="cresta" required>
                            <option value="Lisa">Lisa</option>
                            <option value="Roseta">Roseta</option>
                            <option value="Pava">Pava</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title"><span class="material-symbols-outlined">event</span> Datos de nacimiento</div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label-sm">Fecha <span class="required-star">*</span></label>
                        <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">Luna <span class="required-star">*</span></label>
                        <select class="form-control" name="luna" id="luna" required>
                            <option value="Nueva">Nueva</option>
                            <option value="Creciente cóncava">Creciente</option>
                            <option value="Cuarto creciente">Cuarto creciente</option>
                            <option value="Creciente gibosa">Creciente gibosa</option>
                            <option value="Llena">Llena</option>
                            <option value="Menguante gibosa">Menguante</option>
                            <option value="Cuarto menguante">Cuarto menguante</option>
                            <option value="Menguante cóncava">Menguante cóncava</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title"><span class="material-symbols-outlined">photo_camera</span> Fotos</div>
                <div class="dropzone-lite" id="dropzone-gallina">
                    <span class="material-symbols-outlined" style="font-size:2rem;color:#9baac7;display:block;margin-bottom:.4rem;">cloud_upload</span>
                    <strong>Arrastra imágenes aquí</strong> o haz clic
                    <div class="small text-secondary mt-1">Selección múltiple con vista previa instantánea.</div>
                </div>
                <input type="file" multiple class="d-none" name="imagen[]" id="imagen" accept="image/*">
                <div class="mt-2">
                    <img id="preview-main-gallina" src="{{ asset('img/avatar-2.png') }}" class="w-100 rounded-3" style="height:160px;object-fit:cover;border:1px solid #ede9fe;" alt="Vista previa">
                </div>
                <div id="preview-gallina" class="preview-grid mt-2"></div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title"><span class="material-symbols-outlined">info</span> Estado y notas</div>
                <div class="mb-2">
                    <label class="form-label-sm">Estatus <span class="required-star">*</span></label>
                    <select class="form-control" name="estatus" id="estatus">
                        <option>Activa</option>
                        <option>Inactiva</option>
                        <option>Fallecida</option>
                    </select>
                </div>
                <div>
                    <label class="form-label-sm">Observaciones</label>
                    <textarea class="form-control" name="observaciones" id="observaciones" rows="3" placeholder="Notas adicionales..."></textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="button" class="btn btn-outline-soft flex-fill" data-bs-dismiss="offcanvas">Cancelar</button>
                <button type="submit" class="btn flex-fill text-white fw-bold" style="background:linear-gradient(135deg,#7c3aed,#6d28d9);border:none;border-radius:.7rem;" id="btn-save">
                    <span class="material-symbols-outlined float-start me-1">save</span> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══ Modal Pedigree ═══ --}}
<div class="modal fade" id="modal-pedigree" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5">Pedigree</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body overflow-auto">
            <div id="pedigree-section" style="min-height:240px;"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
</div>

{{-- ═══ Modal Venta ═══ --}}
<div class="modal fade" id="modal-venta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="border-radius:1rem;overflow:hidden;">
        <div class="modal-header" style="background:linear-gradient(135deg,#4c1d95,#6d28d9);">
            <h1 class="modal-title fs-5 text-white fw-bold">💰 Vender gallina</h1>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form id="form-venta">
            <div class="modal-body p-4">
                @csrf
                <div class="p-3 rounded-3 mb-3" style="background:#f5f3ff;border:1px solid #c4b5fd;">
                    <div class="fw-bold" style="font-size:.9rem;color:#6d28d9;">Gallina a vender</div>
                    <div class="fw-bold fs-5" id="placa-venta"></div>
                </div>
                <input type="hidden" name="tipo_item" value="gallina">
                <input type="hidden" name="gallina_id">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label-sm">Nombre del cliente</label>
                        <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente">
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" id="telefono">
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">Monto</label>
                        <input type="text" class="form-control" name="monto" id="monto">
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn text-white fw-bold" style="background:linear-gradient(135deg,#7c3aed,#6d28d9);border:none;border-radius:.7rem;">
                    <span class="material-symbols-outlined float-start me-1">sell</span> Confirmar venta
                </button>
            </div>
        </form>
      </div>
    </div>
</div>

{{-- ═══ Contenido principal ═══ --}}
<section class="section-card p-3 p-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="section-title">Gallinas</h2>
            <p class="section-subtitle">Gestión integral con formularios optimizados y vista responsive.</p>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2 mb-3">
        <button class="btn text-white fw-semibold" style="background:linear-gradient(135deg,#7c3aed,#6d28d9);border:none;border-radius:.7rem;" data-bs-toggle="offcanvas" data-bs-target="#sidebar-gallina">
            <span class="material-symbols-outlined float-start me-2">add_circle</span>
            Nueva gallina
        </button>
        <a href="{{ route('report.all.gallinas') }}" class="btn btn-outline-soft" target="_blank">
            <span class="material-symbols-outlined float-start me-2">picture_as_pdf</span>
            PDF
        </a>
    </div>

    <ul class="nav nav-tabs mb-3 gallinas-tabs" id="gallinas-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link active" data-gallinas-tab="activas" id="tab-gallinas-activas">
                En plantel
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" data-gallinas-tab="vendidas" id="tab-gallinas-vendidas">
                Vendidas
            </button>
        </li>
    </ul>

    <div class="mb-3" style="position:relative;">
        <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9baac7;font-size:1.1rem;pointer-events:none;">search</span>
        <input type="text" class="form-control" id="dato" placeholder="Buscar por placa, marca o color..." style="padding-left:38px;" oninput="searchGallinas(this.value)">
    </div>

    <div id="gallos-section" class="row g-3"></div>
</section>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
    let padreSelect = null, madreSelect = null, currentId = null;
    let allGallinas = [], gallinasTab = 'activas';
    const sidebarEl = document.getElementById('sidebar-gallina');
    const sidebar   = new bootstrap.Offcanvas(sidebarEl);

    const colorMap = {
        'Zambo':'#3e2723','Melao':'#c47c2b','Giro':'#8d6e63',
        'Marañon':'#795548','Calica':'#f5deb3','Pinto':'#607d8b',
        'Jabao':'#a1887f','Camagüey':'#6d4c41'
    };
    const statusClass = {
        'Activa':'sc-activa','Inactiva':'sc-inactiva','Vendido':'sc-vendido','Fallecida':'sc-fallecida'
    };

    getGallinas();
    loadParentSelectors();
    setupImageDropzone('#dropzone-gallina','#imagen','#preview-gallina');

    $('#gallinas-tabs').on('click', '[data-gallinas-tab]', function() {
        gallinasTab = $(this).data('gallinas-tab');
        $('#gallinas-tabs .nav-link').removeClass('active');
        $(this).addClass('active');
        renderGallinasList(filterGallinasByTab(allGallinas));
    });

    function filterGallinasByTab(items) {
        if (gallinasTab === 'vendidas') {
            return items.filter(e => e.estatus === 'Vendido');
        }
        return items.filter(e => e.estatus !== 'Vendido');
    }

    sidebarEl.addEventListener('show.bs.offcanvas', function() {
        if (!currentId) document.getElementById('sidebar-gallina-title').textContent = '🥚 Registrar Gallina';
    });

    function buildCardHTML(e) {
        const imgSrc = e.gallinas_imagenes?.length
            ? `files/gallinas/${e.id}/${e.gallinas_imagenes[0].imagen}`
            : `img/avatar-2.png`;
        const sc = statusClass[e.estatus] || 'sc-inactiva';
        const cp = colorMap[e.color] || '#9baac7';
        const fecha = e.fecha_nacimiento ? moment(e.fecha_nacimiento).format('DD MMM YYYY') : '—';
        const sellBtn = (e.estatus !== 'Vendido' && e.estatus !== 'Fallecida')
            ? `<button class="ave-btn success sell" data-id="${e.id}" data-placa="${e.placa}" title="Vender gallina">
                   <span class="material-symbols-outlined">sell</span>
               </button>` : '';

        return `
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="ave-card">
            <div class="ave-photo-wrap">
              <img src="${imgSrc}" alt="Foto ${e.placa}">
              <div class="ave-photo-overlay"></div>
              <span class="ave-status-chip ${sc}">${e.estatus || 'N/D'}</span>
              <span class="ave-color-pip" style="background:${cp}" title="${e.color || ''}"></span>
              <div class="ave-photo-bottom">
                <div>
                  <div class="ave-placa-overlay">${e.placa}</div>
                  <div class="ave-nombre-overlay">${e.nombre || 'Sin nombre'}</div>
                </div>
              </div>
            </div>
            <div class="ave-card-body">
              <div class="ave-info-row">
                <span class="material-symbols-outlined">palette</span> Color
                <span class="ave-info-val">${e.color || '—'}</span>
              </div>
              <div class="ave-info-row">
                <span class="material-symbols-outlined">loyalty</span> Marca
                <span class="ave-info-val">${e.marca_nacimiento || '—'}</span>
              </div>
              <div class="ave-info-row">
                <span class="material-symbols-outlined">cake</span> Nacimiento
                <span class="ave-info-val">${fecha}</span>
              </div>
              <div class="ave-info-row">
                <span class="material-symbols-outlined">brightness_2</span> Luna
                <span class="ave-info-val">${e.luna || '—'}</span>
              </div>
            </div>
            <div class="ave-actions-bar">
              ${sellBtn}
              <button class="ave-btn pedigree" data-id="${e.id}" title="Pedigree">
                <span class="material-symbols-outlined">account_tree</span>
              </button>
              <a href="report/show-gallina/${e.id}" class="ave-btn" title="Ficha PDF" target="_blank">
                <span class="material-symbols-outlined">picture_as_pdf</span>
              </a>
              <button class="ave-btn edit" data-id="${e.id}" title="Editar gallina">
                <span class="material-symbols-outlined">edit</span>
              </button>
              <button class="ave-btn danger delete" data-id="${e.id}" title="Eliminar gallina">
                <span class="material-symbols-outlined">delete_forever</span>
              </button>
            </div>
          </div>
        </div>`;
    }

    function setupImageDropzone(dropzoneSelector, inputSelector, previewSelector) {
        const dropzone = document.querySelector(dropzoneSelector);
        const input    = document.querySelector(inputSelector);
        const preview  = document.querySelector(previewSelector);
        const mainPrev = document.querySelector('#preview-main-gallina');
        if (!dropzone || !input || !preview) return;
        const renderPreview = (files) => {
            preview.innerHTML = '';
            const imgs = Array.from(files).filter(f => f.type.startsWith('image/'));
            if (imgs[0] && mainPrev) mainPrev.src = URL.createObjectURL(imgs[0]);
            imgs.forEach(file => {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.classList.add('cursor-pointer');
                img.onload = () => URL.revokeObjectURL(img.src);
                img.addEventListener('click', () => { if (mainPrev) mainPrev.src = img.src; });
                preview.appendChild(img);
            });
        };
        dropzone.addEventListener('click', () => input.click());
        ['dragenter','dragover'].forEach(ev => dropzone.addEventListener(ev, e => { e.preventDefault(); dropzone.classList.add('is-dragover'); }));
        ['dragleave','drop'].forEach(ev => dropzone.addEventListener(ev, e => { e.preventDefault(); dropzone.classList.remove('is-dragover'); }));
        dropzone.addEventListener('drop', e => { if (e.dataTransfer?.files?.length) { input.files = e.dataTransfer.files; renderPreview(e.dataTransfer.files); } });
        input.addEventListener('change', () => renderPreview(input.files));
    }

    function apiHeaders() {
        return {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '',
        };
    }

    function optionLabel(e) {
        const nombre = e.nombre ? ' — ' + e.nombre : '';
        return (e.placa || 'Sin placa') + nombre;
    }

    function fillNativeSelect(selectEl, items, emptyText, excludeId) {
        if (!selectEl) return;
        const list = (items || []).filter(i => !excludeId || String(i.id) !== String(excludeId));
        selectEl.innerHTML = '';
        const empty = document.createElement('option');
        empty.value = '';
        empty.textContent = emptyText;
        selectEl.appendChild(empty);
        list.forEach(item => {
            const opt = document.createElement('option');
            opt.value = String(item.id);
            opt.textContent = optionLabel(item);
            selectEl.appendChild(opt);
        });
    }

    function ensureTomSelect(selectEl, placeholder, store) {
        if (!window.TomSelect || !selectEl) return store;
        if (store) {
            store.sync();
            return store;
        }
        return new TomSelect(selectEl, {
            create: false,
            allowEmptyOption: true,
            placeholder: placeholder,
            maxOptions: 500,
        });
    }

    async function loadParentSelectors(excludeGallinaId) {
        const padreEl = document.getElementById('padre_id');
        const madreEl = document.getElementById('madre_id');
        if (!padreEl || !madreEl) return;

        try {
            const [gallosRes, gallinasRes] = await Promise.all([
                fetch('/api/gallos', { credentials: 'same-origin', headers: apiHeaders() }),
                fetch('/api/gallinas', { credentials: 'same-origin', headers: apiHeaders() }),
            ]);

            if (!gallosRes.ok || !gallinasRes.ok) {
                console.error('Error al cargar listas', gallosRes.status, gallinasRes.status);
                fillNativeSelect(padreEl, [], '— Sin padre —', null);
                fillNativeSelect(madreEl, [], '— Sin madre —', excludeGallinaId);
                padreSelect = ensureTomSelect(padreEl, 'Buscar gallo (padre)...', padreSelect);
                madreSelect = ensureTomSelect(madreEl, 'Buscar gallina (madre)...', madreSelect);
                return;
            }

            const gallosPayload = await gallosRes.json();
            const gallinasPayload = await gallinasRes.json();

            const gallos = (gallosPayload.data || []).filter(g => g.estatus !== 'Vendido' && g.estatus !== 'Fallecido');
            const gallinas = (gallinasPayload.data || []).filter(g => g.estatus !== 'Vendido' && g.estatus !== 'Fallecida');
            fillNativeSelect(padreEl, gallos, '— Sin padre —', null);
            fillNativeSelect(madreEl, gallinas, '— Sin madre —', excludeGallinaId);

            padreSelect = ensureTomSelect(padreEl, 'Buscar gallo (padre)...', padreSelect);
            madreSelect = ensureTomSelect(madreEl, 'Buscar gallina (madre)...', madreSelect);
        } catch (e) {
            console.error('No se pudieron cargar padres/madres', e);
        }
    }

    $(document).on('click', '.sell', function(){
        const id = $(this).data('id'), placa = $(this).data('placa');
        $('#placa-venta').html(placa);
        $('[name=gallina_id]').val(id);
        $('#modal-venta').modal('show');
    });

    async function parseApiResponse(response) {
        const text = await response.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            return { message: text || ('Error del servidor (' + response.status + ')') };
        }
    }

    $(document).on('submit', '#form-gallo', function(e){
        e.preventDefault();
        const form = new FormData(this);
        Swal.fire({ title:'Guardando...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });
        fetch('/api/gallinas', {
            method:'POST',
            body:form,
            credentials:'same-origin',
            headers: apiHeaders(),
        })
        .then(async r => {
            const data = await parseApiResponse(r);
            if (!r.ok) throw new Error(data.message || data.msj || 'No se pudo guardar la gallina');
            return data;
        })
        .then(data => {
            Swal.fire({ icon:'success', title:data.msj || 'Guardado', timer:1600, showConfirmButton:false });
            getGallinas(); loadParentSelectors();
            $('#form-gallo')[0].reset();
            if (padreSelect) padreSelect.clear();
            if (madreSelect) madreSelect.clear();
            sidebar.hide();
        })
        .catch(err => Swal.fire({ icon:'error', title: err.message || 'Error al guardar' }));
    });

    $(document).on('submit', '#form-gallo-edit', function(e){
        e.preventDefault();
        const form = new FormData(this);
        form.append('_method','PUT');
        Swal.fire({ title:'Actualizando...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });
        fetch(`/api/gallinas/${currentId}`, {
            method:'POST',
            body:form,
            credentials:'same-origin',
            headers: apiHeaders(),
        })
        .then(async r => {
            const data = await parseApiResponse(r);
            if (!r.ok) throw new Error(data.message || data.msj || 'No se pudo actualizar');
            return data;
        })
        .then(data => {
            Swal.fire({ icon:'success', title: data.msj || 'Gallina actualizada', timer:1600, showConfirmButton:false });
            getGallinas(); loadParentSelectors();
            $('#form-gallo-edit')[0].reset();
            $('#form-gallo-edit').attr('id','form-gallo');
            currentId = null; sidebar.hide();
        })
        .catch(err => Swal.fire({ icon:'error', title: err.message || 'Error al actualizar' }));
    });

    $(document).on('submit', '#form-venta', function(e){
        e.preventDefault();
        const form = new FormData(this);
        Swal.fire({ title:'Procesando venta...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });
        fetch('/api/ventas', { method:'POST', body:form, credentials:'same-origin', headers: apiHeaders() })
        .then(async r => {
            const data = await parseApiResponse(r);
            if (!r.ok) throw new Error(data.message || data.msj || 'No se pudo registrar la venta');
            return data;
        })
        .then(data => {
            Swal.fire({ icon:'success', title:data.msj, timer:1600, showConfirmButton:false });
            gallinasTab = 'vendidas';
            $('#gallinas-tabs .nav-link').removeClass('active');
            $('#tab-gallinas-vendidas').addClass('active');
            getGallinas();
            $('#form-venta')[0].reset();
            $('#modal-venta').modal('hide');
        })
        .catch(err => Swal.fire({ icon:'error', title: err.message || 'Error en la venta' }));
    });

    $(document).on('click', '.edit', function(e){
        e.preventDefault();
        currentId = $(this).data('id');
        Swal.fire({ title:'Cargando datos...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });
        fetch('api/gallinas/'+currentId).then(r => r.json()).then(async function(res){
            Swal.close();
            document.getElementById('sidebar-gallina-title').textContent = '✏️ Editar Gallina';
            const gh = window.ghRow(res.data.gallos_hijos);
            const padreId = (gh && gh.padre) ? String(gh.padre.id) : '';
            const madreId = (gh && gh.madre) ? String(gh.madre.id) : '';
            await loadParentSelectors(currentId);
            if (padreSelect) padreSelect.setValue(padreId, true);
            if (madreSelect) madreSelect.setValue(madreId, true);
            ['color','cresta','luna','estatus'].forEach(field => {
                $(`#${field} option`).each(function(){ $(this).prop('selected', $(this).val() == res.data[field]); });
            });
            $('input[name=placa]').val(res.data.placa);
            $('input[name=nombre]').val(res.data.nombre);
            $('input[name=marca_nacimiento]').val(res.data.marca_nacimiento);
            $('input[name=color_alternativo]').val(res.data.color_alternativo);
            $('input[name=fecha_nacimiento]').val(res.data.fecha_nacimiento);
            $('[name=observaciones]').val(res.data.observaciones);
            $('#form-gallo').attr('id','form-gallo-edit');
            sidebar.show();
        }).catch(() => { currentId=null; Swal.fire({ icon:'error', title:'Error al cargar datos' }); });
    });

    $(document).on('click', '.delete', function(){
        const id = $(this).data('id');
        Swal.fire({ title:'¿Eliminar esta gallina?', text:'No podrás revertir esta acción.', icon:'warning', showCancelButton:true, confirmButtonColor:'#dc2626', cancelButtonColor:'#6b7280', confirmButtonText:'Sí, eliminar', cancelButtonText:'Cancelar' }).then(r => {
            if (r.isConfirmed) {
                fetch('api/gallinas/'+id, { method:'DELETE' }).then(r => r.json()).then(res => {
                    Swal.fire({ icon:'success', title:'Eliminada', text:res.msj, timer:1500, showConfirmButton:false });
                    getGallinas(); loadParentSelectors();
                });
            }
        });
    });

    $(document).on('click', '.pedigree', async function(e){
        e.preventDefault();
        const id = $(this).data('id');
        const $section = $('#pedigree-section');
        $section.empty();
        $('#modal-pedigree').modal('show');
        await ModuleLoader.run('#pedigree-section', async () => {
            const res = await fetch(`api/gallinas/${id}/pedigree`);
            const payload = await res.json();
            if (!window.PedigreeTree) {
                $section.html('<p class="text-danger">No se cargó el módulo de pedigree.</p>');
                return;
            }
            const host = document.createElement('div');
            $section.empty().append(host);
            PedigreeTree.render(payload.data, host, { consanguinidad: payload.consanguinidad });
        }, { message: 'Cargando árbol genealógico...', minHeight: '240px' });
    });

    function renderGallinasList(items) {
        moment.locale('es');
        if (!items.length) {
            const emptyActivas = gallinasTab !== 'vendidas';
            $('#gallos-section').html(`
            <div class="col-12">
                <div style="text-align:center;padding:4rem 1rem;background:#fdf9ff;border:2px dashed #ddd6fe;border-radius:1.4rem;">
                    <div style="font-size:4rem;margin-bottom:.75rem;">${emptyActivas ? '🐔' : '💰'}</div>
                    <h4 style="color:#4c1d95;font-weight:800;margin-bottom:.4rem;">${emptyActivas ? 'Sin gallinas en plantel' : 'Sin gallinas vendidas'}</h4>
                    <p style="color:#6b7280;font-size:.9rem;max-width:360px;margin:0 auto 1.5rem;">${emptyActivas ? 'Registra tu primera gallina o regístrala desde una compra.' : 'Las gallinas vendidas aparecerán aquí.'}</p>
                    ${emptyActivas ? `<button class="btn btn-primary-soft" data-bs-toggle="offcanvas" data-bs-target="#sidebar-gallina">
                        <span class="material-symbols-outlined float-start me-2">add_circle</span> Registrar primera gallina
                    </button>` : ''}
                </div>
            </div>`);
            initTooltips();
            return;
        }
        $('#gallos-section').html(items.map(buildCardHTML).join(''));
        initTooltips();
    }

    window.searchGallinas = async function(q) {
        if (!q || q.length < 2) { getGallinas(); return; }
        const fd = new FormData();
        fd.append('dato', q);
        await ModuleLoader.run('#gallos-section', async () => {
            const data = await fetch('api/gallinas/search', { method:'POST', body:fd, headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')} }).then(r => r.json());
            allGallinas = data.data || [];
            renderGallinasList(filterGallinasByTab(allGallinas));
        }, { message: 'Buscando gallinas...', minHeight: '200px' });
    };

    async function getGallinas(){
        await ModuleLoader.run('#gallos-section', async () => {
            const data = await fetch('api/gallinas').then(r => r.json());
            allGallinas = data.data || [];
            renderGallinasList(filterGallinasByTab(allGallinas));
        }, { message: 'Cargando gallinas...', minHeight: '280px' });
    }

    function initTooltips(){
        document.querySelectorAll('[title]').forEach(el => {
            if (bootstrap.Tooltip.getInstance(el)) bootstrap.Tooltip.getInstance(el).dispose();
            new bootstrap.Tooltip(el, { trigger:'hover' });
        });
    }
});
</script>
@endsection

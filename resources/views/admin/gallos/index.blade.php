@extends('layouts.app')

@section('styles')
<style>
    /* ── Cards de gallos ─────────────────────────────── */
    .ave-card {
        background: #fff;
        border: 1px solid #e8eef8;
        border-radius: 1.2rem;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(16,39,77,.07);
        transition: transform .22s ease, box-shadow .22s ease;
        height: 100%;
    }
    .ave-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 40px rgba(16,39,77,.14);
    }
    .ave-photo-wrap {
        position: relative;
        height: 210px;
        overflow: hidden;
        background: #f0f4fc;
    }
    .ave-photo-wrap img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform .35s ease;
    }
    .ave-card:hover .ave-photo-wrap img { transform: scale(1.05); }
    .ave-photo-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(10,20,50,.65) 0%, transparent 55%);
    }
    .ave-status-chip {
        position: absolute; top: 10px; left: 10px;
        font-size: .68rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .05em; padding: .22rem .7rem; border-radius: 20px;
        backdrop-filter: blur(6px);
    }
    .sc-activo   { background: rgba(22,163,74,.85);  color: #fff; }
    .sc-inactivo { background: rgba(107,114,128,.8); color: #fff; }
    .sc-vendido  { background: rgba(29,78,216,.85);  color: #fff; }
    .sc-fallecido{ background: rgba(153,27,27,.85);  color: #fff; }
    .ave-color-pip {
        position: absolute; top: 10px; right: 10px;
        width: 20px; height: 20px; border-radius: 50%;
        border: 2.5px solid rgba(255,255,255,.85);
        box-shadow: 0 2px 8px rgba(0,0,0,.25);
    }
    .ave-photo-bottom {
        position: absolute; bottom: 10px; left: 12px; right: 12px;
        display: flex; align-items: flex-end; justify-content: space-between;
    }
    .ave-placa-overlay {
        font-size: 1.15rem; font-weight: 800; color: #fff;
        text-shadow: 0 2px 8px rgba(0,0,0,.5); line-height: 1.15;
    }
    .ave-nombre-overlay {
        font-size: .78rem; color: rgba(255,255,255,.8);
        text-shadow: 0 1px 4px rgba(0,0,0,.4);
    }
    .ave-fights {
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: #fff; border-radius: .6rem; padding: .2rem .55rem;
        text-align: center; line-height: 1.2; min-width: 42px;
    }
    .ave-fights span { font-size: .95rem; font-weight: 800; display: block; }
    .ave-fights small { font-size: .55rem; font-weight: 400; }
    .ave-card-body { padding: .9rem 1rem .6rem; }
    .ave-info-row {
        display: flex; align-items: center; gap: .4rem;
        font-size: .78rem; color: #4a5568; padding: .25rem 0;
        border-bottom: 1px solid #f3f6fc;
    }
    .ave-info-row:last-child { border-bottom: none; }
    .ave-info-row .material-symbols-outlined { font-size: .9rem; color: #9baac7; flex-shrink: 0; }
    .ave-info-val { font-weight: 600; color: #1a2648; margin-left: auto; }
    .ave-actions-bar {
        display: flex; align-items: center; justify-content: flex-end;
        gap: .2rem; padding: .55rem .85rem;
        border-top: 1px solid #f0f4fc;
        background: #fafbff;
    }
    .ave-btn {
        width: 34px; height: 34px; border-radius: .6rem;
        border: 1px solid #e8eef8; background: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .15s; color: #60708d;
        text-decoration: none; padding: 0;
    }
    .ave-btn .material-symbols-outlined { font-size: 1.05rem; }
    .ave-btn:hover { background: #eff6ff; border-color: #bcd4ff; color: #3b82f6; }
    .ave-btn.danger:hover  { background: #fff1f2; border-color: #fca5a5; color: #dc2626; }
    .ave-btn.success:hover { background: #f0fdf4; border-color: #86efac; color: #16a34a; }
    .ave-btn.warning:hover { background: #fffbeb; border-color: #fde68a; color: #d97706; }

    /* ── Sidebar / Offcanvas ─────────────────────────── */
    #sidebar-gallo { width: min(520px, 100vw); }
    .sidebar-header {
        background: linear-gradient(135deg, #1a2648 0%, #2d4278 100%);
        padding: 1.25rem 1.5rem;
    }
    .sidebar-header .offcanvas-title { color: #fff; font-weight: 700; font-size: 1.1rem; }
    .sidebar-header small { color: rgba(255,255,255,.65); font-size: .78rem; }
    .sidebar-section {
        border: 1px solid #e8eef8; border-radius: .85rem;
        padding: 1rem 1.1rem; margin-bottom: 1rem;
        background: #fafbff;
    }
    .sidebar-section-title {
        font-size: .78rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .07em; color: #60708d; margin-bottom: .75rem;
        display: flex; align-items: center; gap: .4rem;
    }
    .sidebar-section-title .material-symbols-outlined { font-size: .95rem; }
    .form-label-sm { font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }
    .required-star { color: #ef4444; }
    #sidebar-gallo .form-control,
    #sidebar-gallo .form-select { font-size: .88rem; border-radius: .65rem; }
</style>
@endsection

@section('content')

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- SIDEBAR: Registrar / Editar Gallo                       --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<div class="offcanvas offcanvas-end" id="sidebar-gallo" tabindex="-1" data-bs-scroll="true">
    <div class="offcanvas-header sidebar-header">
        <div>
            <h5 class="offcanvas-title" id="sidebar-gallo-title">🐓 Registrar Gallo</h5>
            <small>Completa los datos del animal</small>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body">
        <form id="form-gallo">
            @csrf

            <div class="sidebar-section">
                <div class="sidebar-section-title">
                    <span class="material-symbols-outlined">family_restroom</span> Genealogía
                </div>
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
                <div class="sidebar-section-title">
                    <span class="material-symbols-outlined">badge</span> Identificación
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label-sm">Placa <span class="required-star">*</span></label>
                        <input type="text" class="form-control" name="placa" id="placa" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre">
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">Marca nacimiento <span class="required-star">*</span></label>
                        <input type="text" class="form-control" name="marca_nacimiento" id="marca_nacimiento" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">Marca federación</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" name="marca_federacion" id="marca_federacion" placeholder="Opcional">
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">
                    <span class="material-symbols-outlined">palette</span> Características físicas
                </div>
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
                    <div class="col-6">
                        <label class="form-label-sm">Peleas</label>
                        <input type="number" class="form-control" name="peleas" id="peleas" min="0" value="0">
                    </div>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">
                    <span class="material-symbols-outlined">event</span> Datos de nacimiento
                </div>
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
                <div class="sidebar-section-title">
                    <span class="material-symbols-outlined">photo_camera</span> Fotos
                </div>
                <div class="dropzone-lite" id="dropzone-gallo">
                    <span class="material-symbols-outlined" style="font-size:2rem;color:#9baac7;display:block;margin-bottom:.4rem;">cloud_upload</span>
                    <strong>Arrastra imágenes aquí</strong> o haz clic
                    <div class="small text-secondary mt-1">Selección múltiple con vista previa instantánea.</div>
                </div>
                <input type="file" multiple class="d-none" name="imagen[]" id="imagen" accept="image/*">
                <div class="mt-2">
                    <img id="preview-main-gallo" src="{{ asset('img/avatar.png') }}" class="w-100 rounded-3" style="height:160px;object-fit:cover;border:1px solid #e8eef8;" alt="Vista previa">
                </div>
                <div id="preview-gallo" class="preview-grid mt-2"></div>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">
                    <span class="material-symbols-outlined">info</span> Estado y notas
                </div>
                <div class="mb-2">
                    <label class="form-label-sm">Estatus <span class="required-star">*</span></label>
                    <select class="form-control" name="estatus" id="estatus">
                        <option>Activo</option>
                        <option>Inactivo</option>
                        <option>Vendido</option>
                        <option>Fallecido</option>
                    </select>
                </div>
                <div>
                    <label class="form-label-sm">Observaciones</label>
                    <textarea class="form-control" name="observaciones" id="observaciones" rows="3" placeholder="Notas adicionales..."></textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="button" class="btn btn-outline-soft flex-fill" data-bs-dismiss="offcanvas">Cancelar</button>
                <button type="submit" class="btn btn-primary-soft flex-fill" id="btn-save">
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
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
        <div class="modal-header" style="background:linear-gradient(135deg,#166534,#15803d);">
            <h1 class="modal-title fs-5 text-white fw-bold">💰 Vender gallo</h1>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="form-venta">
            <div class="modal-body p-4">
                @csrf
                <div class="p-3 rounded-3 mb-3" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                    <div class="fw-bold text-success" style="font-size:.9rem;">Gallo a vender</div>
                    <div class="fw-bold fs-5" id="placa-venta"></div>
                </div>
                <input type="hidden" name="tipo_item" value="gallo">
                <input type="hidden" name="gallo_id">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label-sm">Nombre del cliente <span class="required-star">*</span></label>
                        <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" id="telefono">
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">Monto <span class="required-star">*</span></label>
                        <input type="text" class="form-control" name="monto" id="monto" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">
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
            <h2 class="section-title">Gallos</h2>
            <p class="section-subtitle">Administra inventario, pedigree y ventas en una sola vista.</p>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2 mb-3">
        <button class="btn btn-primary-soft" data-bs-toggle="offcanvas" data-bs-target="#sidebar-gallo">
            <span class="material-symbols-outlined float-start me-2">add_circle</span>
            Nuevo gallo
        </button>
        <a href="{{ route('report.all') }}" class="btn btn-outline-soft" target="_blank">
            <span class="material-symbols-outlined float-start me-2">picture_as_pdf</span>
            PDF
        </a>
    </div>

    <div id="catalog-share-panel" class="mb-3 p-3 rounded-3" style="background:linear-gradient(135deg,#eff6ff,#f8faff);border:1px solid #c7d9f5;display:none;">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
            <div>
                <div class="fw-bold" style="font-size:.9rem;color:#1a2648;display:flex;align-items:center;gap:.4rem;">
                    <span class="material-symbols-outlined" style="font-size:1.1rem;color:#3b82f6;">share</span>
                    Catálogo para clientes
                </div>
                <p class="mb-0 text-muted" style="font-size:.78rem;">Enlace público sin login. Usa una clave aleatoria de 64 caracteres (no expone el ID de tu cuenta). Si se filtra, usa «Renovar enlace».</p>
            </div>
            <span id="catalog-status-badge" class="badge rounded-pill" style="font-size:.7rem;"></span>
        </div>
        <div class="input-group input-group-sm mb-2">
            <input type="text" class="form-control" id="catalog-url-input" readonly placeholder="Generando enlace...">
            <button type="button" class="btn btn-primary" id="btn-copy-catalog" title="Copiar enlace">
                <span class="material-symbols-outlined" style="font-size:1rem;vertical-align:middle;">content_copy</span>
            </button>
            <a href="#" target="_blank" rel="noopener" class="btn btn-outline-secondary" id="btn-open-catalog" title="Vista previa">
                <span class="material-symbols-outlined" style="font-size:1rem;vertical-align:middle;">open_in_new</span>
            </a>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-catalog-toggle">Activar / desactivar</button>
            <button type="button" class="btn btn-sm btn-outline-danger" id="btn-catalog-regenerate">Renovar enlace</button>
            <small class="text-muted align-self-center" id="catalog-count-hint" style="font-size:.72rem;"></small>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3 gallos-tabs" id="gallos-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link active" data-gallos-tab="activos" id="tab-gallos-activos">
                En plantel
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" data-gallos-tab="vendidos" id="tab-gallos-vendidos">
                Vendidos
            </button>
        </li>
    </ul>

    <div class="mb-3" style="position:relative;">
        <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9baac7;font-size:1.1rem;pointer-events:none;">search</span>
        <input type="text" class="form-control" id="dato" placeholder="Buscar por placa, marca o color..." style="padding-left:38px;" oninput="searchGallos(this.value)">
    </div>

    <div id="gallos-section" class="row g-3"></div>
</section>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
    let padreSelect = null, madreSelect = null, currentId = null;
    let allGallos = [], gallosTab = 'activos';
    const sidebarEl = document.getElementById('sidebar-gallo');
    const sidebar   = new bootstrap.Offcanvas(sidebarEl);

    const colorMap = {
        'Zambo':'#3e2723','Melao':'#c47c2b','Giro':'#8d6e63',
        'Marañon':'#795548','Calica':'#f5deb3','Pinto':'#607d8b',
        'Jabao':'#a1887f','Camagüey':'#6d4c41'
    };
    const statusClass = {
        'Activo':'sc-activo','Inactivo':'sc-inactivo','Vendido':'sc-vendido','Fallecido':'sc-fallecido'
    };

    getGallos();
    loadParentSelectors();
    loadCatalogLink();
    setupImageDropzone('#dropzone-gallo','#imagen','#preview-gallo');

    function apiHeadersCatalog() {
        return {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '',
        };
    }

    async function loadCatalogLink() {
        const panel = document.getElementById('catalog-share-panel');
        try {
            const res = await fetch('/api/catalog', { credentials: 'same-origin', headers: apiHeadersCatalog() });
            const payload = await res.json();
            if (!res.ok) return;
            const d = payload.data || {};
            panel.style.display = '';
            const input = document.getElementById('catalog-url-input');
            const openBtn = document.getElementById('btn-open-catalog');
            const badge = document.getElementById('catalog-status-badge');
            const hint = document.getElementById('catalog-count-hint');
            input.value = d.url || '';
            openBtn.href = d.url || '#';
            openBtn.style.pointerEvents = d.url ? '' : 'none';
            badge.textContent = d.enabled ? 'Activo' : 'Desactivado';
            badge.className = 'badge rounded-pill ' + (d.enabled ? 'bg-success' : 'bg-secondary');
            hint.textContent = [
                d.count != null ? `${d.count} gallo(s) en catálogo` : '',
                d.token_hint || '',
            ].filter(Boolean).join(' · ');
        } catch (e) {
            console.warn('Catálogo:', e);
        }
    }

    $('#btn-copy-catalog').on('click', function() {
        const input = document.getElementById('catalog-url-input');
        if (!input.value) return;
        navigator.clipboard.writeText(input.value).then(() => {
            Swal.fire({ icon: 'success', title: 'Enlace copiado', timer: 1200, showConfirmButton: false });
        }).catch(() => {
            input.select();
            document.execCommand('copy');
            Swal.fire({ icon: 'success', title: 'Enlace copiado', timer: 1200, showConfirmButton: false });
        });
    });

    $('#btn-catalog-regenerate').on('click', function() {
        Swal.fire({
            title: '¿Renovar enlace?',
            text: 'El enlace anterior dejará de funcionar.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Renovar',
        }).then(r => {
            if (!r.isConfirmed) return;
            fetch('/api/catalog/regenerate', { method: 'POST', credentials: 'same-origin', headers: apiHeadersCatalog() })
                .then(res => res.json())
                .then(data => {
                    Swal.fire({ icon: 'success', title: data.msj || 'Listo', timer: 1800, showConfirmButton: false });
                    loadCatalogLink();
                });
        });
    });

    $('#btn-catalog-toggle').on('click', function() {
        fetch('/api/catalog/toggle', { method: 'POST', credentials: 'same-origin', headers: apiHeadersCatalog() })
            .then(res => res.json())
            .then(data => {
                Swal.fire({ icon: 'success', title: data.msj || 'Actualizado', timer: 1400, showConfirmButton: false });
                loadCatalogLink();
            });
    });

    $('#gallos-tabs').on('click', '[data-gallos-tab]', function() {
        gallosTab = $(this).data('gallos-tab');
        $('#gallos-tabs .nav-link').removeClass('active');
        $(this).addClass('active');
        renderGallosList(filterGallosByTab(allGallos));
    });

    function filterGallosByTab(items) {
        if (gallosTab === 'vendidos') {
            return items.filter(e => e.estatus === 'Vendido');
        }
        return items.filter(e => e.estatus !== 'Vendido');
    }

    sidebarEl.addEventListener('show.bs.offcanvas', function() {
        if (!currentId) {
            document.getElementById('sidebar-gallo-title').textContent = '🐓 Registrar Gallo';
        }
    });

    function buildCardHTML(e) {
        const imgSrc = e.gallos_imagenes?.length
            ? `files/gallos/${e.id}/${e.gallos_imagenes[0].imagen}`
            : `img/avatar.png`;
        const sc = statusClass[e.estatus] || 'sc-inactivo';
        const cp = colorMap[e.color] || '#9baac7';
        const fecha = e.fecha_nacimiento ? moment(e.fecha_nacimiento).format('DD MMM YYYY') : '—';
        const sellBtn = (e.estatus !== 'Vendido' && e.estatus !== 'Fallecido')
            ? `<button class="ave-btn success sell" data-id="${e.id}" data-placa="${e.placa}" title="Vender gallo">
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
                ${e.peleas ? `<div class="ave-fights"><span>${e.peleas}</span><small>peleas</small></div>` : ''}
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
              <a href="report/show/${e.id}" class="ave-btn" title="Ficha PDF" target="_blank">
                <span class="material-symbols-outlined">picture_as_pdf</span>
              </a>
              <button class="ave-btn edit" data-id="${e.id}" title="Editar gallo">
                <span class="material-symbols-outlined">edit</span>
              </button>
              <button class="ave-btn danger delete" data-id="${e.id}" title="Eliminar gallo">
                <span class="material-symbols-outlined">delete_forever</span>
              </button>
            </div>
          </div>
        </div>`;
    }

    function setupImageDropzone(dropzoneSelector, inputSelector, previewSelector) {
        const dropzone  = document.querySelector(dropzoneSelector);
        const input     = document.querySelector(inputSelector);
        const preview   = document.querySelector(previewSelector);
        const mainPrev  = document.querySelector('#preview-main-gallo');
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

    async function loadParentSelectors(excludeGalloId) {
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
                fillNativeSelect(padreEl, [], '— Sin padre —', excludeGalloId);
                fillNativeSelect(madreEl, [], '— Sin madre —', null);
                padreSelect = ensureTomSelect(padreEl, 'Buscar gallo (padre)...', padreSelect);
                madreSelect = ensureTomSelect(madreEl, 'Buscar gallina (madre)...', madreSelect);
                return;
            }

            const gallosPayload = await gallosRes.json();
            const gallinasPayload = await gallinasRes.json();
            const gallos = (gallosPayload.data || []).filter(g => g.estatus !== 'Vendido' && g.estatus !== 'Fallecido');
            const gallinas = (gallinasPayload.data || []).filter(g => g.estatus !== 'Vendido' && g.estatus !== 'Fallecida');

            fillNativeSelect(padreEl, gallos, '— Sin padre —', excludeGalloId);
            fillNativeSelect(madreEl, gallinas, '— Sin madre —', null);

            padreSelect = ensureTomSelect(padreEl, 'Buscar gallo (padre)...', padreSelect);
            madreSelect = ensureTomSelect(madreEl, 'Buscar gallina (madre)...', madreSelect);
        } catch (e) {
            console.error('No se pudieron cargar padres/madres', e);
        }
    }

    // ── Vender ──
    $(document).on('click', '.sell', function(){
        const id    = $(this).data('id');
        const placa = $(this).data('placa');
        $('#placa-venta').html(placa);
        $('[name=gallo_id]').val(id);
        $('#modal-venta').modal('show');
    });

    // ── Guardar nuevo gallo ──
    $(document).on('submit', '#form-gallo', function(e){
        e.preventDefault();
        const form = new FormData(this);
        Swal.fire({ title:'Guardando...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });
        fetch('api/gallos', { method:'POST', body:form, headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')} })
        .then(r => r.json()).then(data => {
            Swal.fire({ icon:'success', title:data.msj, timer:1600, showConfirmButton:false });
            getGallos(); loadParentSelectors();
            $('#form-gallo')[0].reset();
            if (padreSelect) padreSelect.clear();
            if (madreSelect) madreSelect.clear();
            sidebar.hide();
        });
    });

    // ── Guardar edición gallo ──
    $(document).on('submit', '#form-gallo-edit', function(e){
        e.preventDefault();
        const form = new FormData(this);
        form.append('_method','PUT');
        Swal.fire({ title:'Actualizando...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });
        fetch(`api/gallos/${currentId}`, { method:'POST', body:form })
        .then(r => r.json()).then(data => {
            Swal.fire({ icon:'success', title:'Gallo actualizado', timer:1600, showConfirmButton:false });
            getGallos(); loadParentSelectors();
            $('#form-gallo-edit')[0].reset();
            $('#form-gallo-edit').attr('id','form-gallo');
            currentId = null;
            sidebar.hide();
        }).catch(() => Swal.fire({ icon:'error', title:'Error al actualizar' }));
    });

    // ── Vender submit ──
    $(document).on('submit', '#form-venta', function(e){
        e.preventDefault();
        const form = new FormData(this);
        Swal.fire({ title:'Procesando venta...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });
        fetch('/api/ventas', { method:'POST', body:form, credentials:'same-origin', headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content'),'Accept':'application/json'} })
        .then(r => r.json()).then(data => {
            Swal.fire({ icon:'success', title:data.msj, timer:1600, showConfirmButton:false });
            gallosTab = 'vendidos';
            $('#gallos-tabs .nav-link').removeClass('active');
            $('#tab-gallos-vendidos').addClass('active');
            getGallos();
            $('#form-venta')[0].reset();
            $('#modal-venta').modal('hide');
        })
        .catch(() => Swal.fire({ icon:'error', title:'Error al registrar la venta' }));
    });

    // ── Editar ──
    $(document).on('click', '.edit', function(e){
        e.preventDefault();
        currentId = $(this).data('id');
        Swal.fire({ title:'Cargando datos...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });
        fetch('api/gallos/'+currentId).then(r => r.json()).then(async function(res){
            Swal.close();
            document.getElementById('sidebar-gallo-title').textContent = '✏️ Editar Gallo';

            const gh = window.ghRow(res.data.gallos_hijos);
            const padreId = (gh && gh.padre) ? String(gh.padre.id) : '';
            const madreId = (gh && gh.madre) ? String(gh.madre.id) : '';

            await loadParentSelectors(currentId);
            if (padreSelect) padreSelect.setValue(padreId, true);
            if (madreSelect) madreSelect.setValue(madreId, true);

            ['color','cresta','luna','estatus'].forEach(field => {
                $(`#${field} option`).each(function(){ $(this).prop('selected', $(this).val() == res.data[field]); });
            });
            if (res.data.marca_federacion) $('input[name=marca_federacion]').val(res.data.marca_federacion);
            $('input[name=placa]').val(res.data.placa);
            $('input[name=nombre]').val(res.data.nombre);
            $('input[name=marca_nacimiento]').val(res.data.marca_nacimiento);
            $('input[name=color_alternativo]').val(res.data.color_alternativo);
            $('input[name=fecha_nacimiento]').val(res.data.fecha_nacimiento);
            $('input[name=peleas]').val(res.data.peleas);
            $('[name=observaciones]').val(res.data.observaciones);
            $('#form-gallo').attr('id','form-gallo-edit');
            sidebar.show();
        }).catch(() => { currentId=null; Swal.fire({ icon:'error', title:'Error al cargar datos' }); });
    });

    // ── Eliminar ──
    $(document).on('click', '.delete', function(){
        const id = $(this).data('id');
        Swal.fire({ title:'¿Eliminar este gallo?', text:'No podrás revertir esta acción.', icon:'warning', showCancelButton:true, confirmButtonColor:'#dc2626', cancelButtonColor:'#6b7280', confirmButtonText:'Sí, eliminar', cancelButtonText:'Cancelar' }).then(r => {
            if (r.isConfirmed) {
                fetch('api/gallos/'+id, { method:'DELETE' }).then(r => r.json()).then(res => {
                    Swal.fire({ icon:'success', title:'Eliminado', text:res.msj, timer:1500, showConfirmButton:false });
                    getGallos(); loadParentSelectors();
                });
            }
        });
    });

    // ── Pedigree ──
    $(document).on('click', '.pedigree', async function(e){
        e.preventDefault();
        const id = $(this).data('id');
        const $section = $('#pedigree-section');
        $section.empty();
        $('#modal-pedigree').modal('show');
        await ModuleLoader.run('#pedigree-section', async () => {
            const res = await fetch(`api/gallos/${id}/pedigree`);
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

    function renderGallosList(items) {
        moment.locale('es');
        if (!items.length) {
            const emptyActivos = gallosTab !== 'vendidos';
            $('#gallos-section').html(`
            <div class="col-12">
                <div style="text-align:center;padding:4rem 1rem;background:#f8faff;border:2px dashed #d4dded;border-radius:1.4rem;">
                    <div style="font-size:4rem;margin-bottom:.75rem;">${emptyActivos ? '🐓' : '💰'}</div>
                    <h4 style="color:#1a2648;font-weight:800;margin-bottom:.4rem;">${emptyActivos ? 'Sin gallos en plantel' : 'Sin gallos vendidos'}</h4>
                    <p style="color:#60708d;font-size:.9rem;max-width:360px;margin:0 auto 1.5rem;">${emptyActivos ? 'Registra tu primer gallo o regístralo desde una compra.' : 'Los gallos vendidos aparecerán aquí.'}</p>
                    ${emptyActivos ? `<button class="btn btn-primary-soft" data-bs-toggle="offcanvas" data-bs-target="#sidebar-gallo">
                        <span class="material-symbols-outlined float-start me-2">add_circle</span> Registrar primer gallo
                    </button>` : ''}
                </div>
            </div>`);
            initTooltips();
            return;
        }
        $('#gallos-section').html(items.map(buildCardHTML).join(''));
        initTooltips();
    }

    // ── Búsqueda en tiempo real ──
    window.searchGallos = async function(q) {
        if (!q || q.length < 2) { getGallos(); return; }
        const fd = new FormData();
        fd.append('dato', q);
        fd.append('_token', $('meta[name="csrf-token"]').attr('content'));
        await ModuleLoader.run('#gallos-section', async () => {
            const data = await fetch('api/gallos/search', { method:'POST', body:fd, headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')} }).then(r => r.json());
            allGallos = data.data || [];
            renderGallosList(filterGallosByTab(allGallos));
        }, { message: 'Buscando gallos...', minHeight: '200px' });
    };

    // ── Cargar gallos ──
    async function getGallos(){
        await ModuleLoader.run('#gallos-section', async () => {
            const data = await fetch('api/gallos').then(r => r.json());
            allGallos = data.data || [];
            renderGallosList(filterGallosByTab(allGallos));
        }, { message: 'Cargando gallos...', minHeight: '280px' });
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

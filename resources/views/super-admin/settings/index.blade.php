@extends('layouts.app')

@section('styles')
<style>
    .settings-tabs { display:flex; gap:.4rem; border-bottom:2px solid #e8eef8; margin-bottom:1.5rem; }
    .stab { padding:.6rem 1.1rem; font-size:.85rem; font-weight:600; color:#60708d; cursor:pointer; border-bottom:3px solid transparent; text-decoration:none; display:flex; align-items:center; gap:.35rem; margin-bottom:-2px; transition:all .15s; }
    .stab .material-symbols-outlined { font-size:1rem; }
    .stab:hover { color:#1d4ed8; }
    .stab.active { color:#3b82f6; border-bottom-color:#3b82f6; }
    .settings-panel { display:none; }
    .settings-panel.show { display:block; }
    .set-section { background:#fff; border:1px solid #e8eef8; border-radius:1rem; padding:1.5rem; margin-bottom:1.25rem; box-shadow:0 4px 14px rgba(16,39,77,.05); }
    .set-section h5 { font-weight:800; font-size:.95rem; color:#1a2648; display:flex; align-items:center; gap:.5rem; margin-bottom:1.25rem; }
    .set-section h5 .material-symbols-outlined { font-size:1.1rem; color:#3b82f6; }
    .form-label-s { font-size:.82rem; font-weight:600; color:#374151; margin-bottom:.35rem; display:block; }
    .method-toggle { display:flex; align-items:center; gap:.65rem; padding:.85rem 1rem; border:2px solid #e8eef8; border-radius:.85rem; margin-bottom:1rem; cursor:pointer; transition:all .15s; background:#fafbff; }
    .method-toggle:has(input:checked) { border-color:#3b82f6; background:#eff6ff; }
    .method-toggle-icon { font-size:1.5rem; }
    .method-toggle-label { font-weight:700; font-size:.9rem; color:#1a2648; flex-grow:1; }
    .method-fields { padding:0 1rem 1rem; border:1.5px solid #e8eef8; border-top:none; border-radius:0 0 .85rem .85rem; margin-top:-1rem; display:none; }
    .method-fields.show { display:block; padding-top:1rem; }
    .preview-price { background:linear-gradient(135deg,#1a2648,#2d4278); color:#fff; border-radius:1rem; padding:1.5rem; text-align:center; }
    .preview-price .price-main { font-size:2.5rem; font-weight:900; }
    .preview-price .price-period { font-size:.85rem; opacity:.7; }
    .preview-price .price-yearly { font-size:1rem; margin-top:.4rem; opacity:.8; }
    .save-btn { background:linear-gradient(135deg,#3b82f6,#6d5efc); color:#fff; border:none; border-radius:.85rem; padding:.8rem 2rem; font-weight:700; font-size:.95rem; cursor:pointer; transition:all .2s; box-shadow:0 6px 18px rgba(59,130,246,.3); }
    .save-btn:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(59,130,246,.4); }
    .input-group-text-s { background:#f3f6fd; border:1.5px solid #d7dfed; border-right:none; border-radius:.65rem 0 0 .65rem; padding:.45rem .75rem; font-weight:600; font-size:.85rem; color:#374151; }
    .form-control-s { border:1.5px solid #d7dfed; border-radius:0 .65rem .65rem 0; padding:.45rem .75rem; font-size:.88rem; width:100%; outline:none; }
    .form-control-s:focus { border-color:#8eb6ff; box-shadow:0 0 0 .18rem rgba(59,130,246,.16); }
    .form-control-alone { border:1.5px solid #d7dfed; border-radius:.65rem; padding:.45rem .75rem; font-size:.88rem; width:100%; outline:none; }
    .form-control-alone:focus { border-color:#8eb6ff; box-shadow:0 0 0 .18rem rgba(59,130,246,.16); }
    .toggle-switch { position:relative; width:46px; height:24px; flex-shrink:0; }
    .toggle-switch input { opacity:0; width:0; height:0; position:absolute; }
    .toggle-slider { position:absolute; inset:0; background:#d1d5db; border-radius:34px; cursor:pointer; transition:.3s; }
    .toggle-slider::before { content:''; position:absolute; height:18px; width:18px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.3s; }
    .toggle-switch input:checked + .toggle-slider { background:#3b82f6; }
    .toggle-switch input:checked + .toggle-slider::before { transform:translateX(22px); }
    @keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
</style>
@endsection

@section('content')
<section class="section-card p-3 p-md-4">
    <div class="mb-4">
        <h2 class="section-title">Configuración del sistema</h2>
        <p class="section-subtitle">Precios de planes, métodos de pago y datos de recepción.</p>
    </div>

    @if(session('ok'))
    <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:.75rem;padding:.85rem 1rem;margin-bottom:1.25rem;font-size:.85rem;color:#166534;font-weight:600;">
        ✅ {{ session('ok') }}
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.settings.update') }}" id="form-settings">
        @csrf

        {{-- Tabs --}}
        <div class="settings-tabs">
            <a class="stab active" data-panel="tasa" href="#"><span class="material-symbols-outlined">currency_exchange</span> Tasa del Dólar</a>
            <a class="stab" data-panel="planes" href="#"><span class="material-symbols-outlined">workspace_premium</span> Planes & Precios</a>
            <a class="stab" data-panel="metodos" href="#"><span class="material-symbols-outlined">credit_card</span> Métodos de pago</a>
            <a class="stab" data-panel="limites" href="#"><span class="material-symbols-outlined">tune</span> Límites plan Free</a>
        </div>

        {{-- Panel: Tasa del Dólar --}}
        <div class="settings-panel show" id="panel-tasa">
            {{-- Estado actual --}}
            @php
                $rateOk  = $currentRate > 0;
                $isFresh = $rateCache && ($rateCache['fecha'] ?? '') === now()->toDateString();
            @endphp
            <div class="set-section" style="background:linear-gradient(135deg,#0f1937,#1e3a6e);border-color:rgba(251,191,36,.25);">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                    <div>
                        <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#fde68a;margin-bottom:.4rem;">Tasa vigente del dólar</div>
                        <div id="rate-big-display" style="font-size:2.8rem;font-weight:900;color:#fff;line-height:1;">
                            Bs. {{ $rateOk ? number_format($currentRate, 2) : '—' }}
                        </div>
                        <div style="font-size:.78rem;color:rgba(255,255,255,.6);margin-top:.3rem;">
                            @if($rateCache)
                                <span id="rate-fecha-display">Última actualización: {{ $rateCache['fecha'] ?? '—' }}</span>
                                @if($isFresh)
                                    <span style="background:rgba(74,222,128,.2);color:#86efac;border-radius:20px;padding:.1rem .5rem;font-size:.68rem;font-weight:700;margin-left:.4rem;">✓ Hoy</span>
                                @else
                                    <span style="background:rgba(251,191,36,.2);color:#fde68a;border-radius:20px;padding:.1rem .5rem;font-size:.68rem;font-weight:700;margin-left:.4rem;">⚠ Desactualizada</span>
                                @endif
                            @else
                                <span id="rate-fecha-display" style="color:#fca5a5;">Sin datos. Configura la URL de la API abajo y presiona "Actualizar tasa ahora".</span>
                            @endif
                        </div>
                    </div>
                    <div style="text-align:right;">
                        @if($rateOk)
                        <div style="font-size:.75rem;color:rgba(255,255,255,.55);margin-bottom:.5rem;">Equivalencias del plan Pro</div>
                        <div style="font-size:.92rem;font-weight:700;color:#fde68a;">
                            Mensual: Bs. {{ number_format($currentRate * $settings['plans']['pro_monthly_price'], 2) }}
                        </div>
                        <div style="font-size:.82rem;color:rgba(255,255,255,.65);">
                            Anual: Bs. {{ number_format($currentRate * $settings['plans']['pro_yearly_price'], 2) }}
                        </div>
                        @endif
                    </div>
                </div>
                {{-- Botón force-refresh via AJAX --}}
                <div style="margin-top:1.25rem;display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                    <button type="button" id="btn-refresh-rate" onclick="doRefreshRate()"
                        style="background:rgba(251,191,36,.15);border:1px solid rgba(251,191,36,.4);color:#fde68a;border-radius:.7rem;padding:.55rem 1.2rem;font-size:.83rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;transition:all .2s;">
                        <span class="material-symbols-outlined" style="font-size:.95rem;">refresh</span>
                        Actualizar tasa ahora
                    </button>
                    <span id="refresh-rate-msg" style="font-size:.78rem;color:rgba(255,255,255,.5);">Se actualiza automáticamente al iniciar sesión si no es del día.</span>
                </div>
            </div>

            {{-- Configuración API --}}
            <div class="set-section">
                <h5><span class="material-symbols-outlined">api</span> Configuración de la API</h5>
                <p style="font-size:.82rem;color:#60708d;margin-bottom:1.25rem;">
                    Configura la URL y API Key del servicio de tasa del dólar. El sistema consultará esta API automáticamente al iniciar sesión si no tiene la tasa del día. La respuesta debe tener el formato <code>{"tasa": "36.25", "fecha": "2024-01-15"}</code>.
                </p>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-s">URL de la API <span style="color:#ef4444">*</span></label>
                        <input type="url" name="dollar_rate_api_url" class="form-control-alone"
                            value="{{ $settings['dollar_rate']['api_url'] ?? '' }}"
                            placeholder="https://api.ejemplo.com/tasa">
                        <small style="font-size:.73rem;color:#94a3b8;margin-top:.25rem;display:block;">El sistema enviará la API Key como header <code>Authorization: Bearer ...</code> y también como parámetro <code>?token=...</code></small>
                    </div>
                    <div class="col-12">
                        <label class="form-label-s">API Key</label>
                        <input type="text" name="dollar_rate_api_key" class="form-control-alone"
                            value="{{ $settings['dollar_rate']['api_key'] ?? '' }}"
                            placeholder="tu-api-key-aqui">
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel: Planes & Precios --}}
        <div class="settings-panel" id="panel-planes">
            <div class="row g-3">
                <div class="col-lg-7">
                    <div class="set-section">
                        <h5><span class="material-symbols-outlined">payments</span> Precios Plan Pro</h5>
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <label class="form-label-s">Precio mensual</label>
                                <div style="display:flex;">
                                    <span class="input-group-text-s">$</span>
                                    <input type="number" name="pro_monthly_price" step="0.01" min="0"
                                        value="{{ $settings['plans']['pro_monthly_price'] }}"
                                        class="form-control-s" id="monthly-price" oninput="updatePreview()">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label-s">Precio anual</label>
                                <div style="display:flex;">
                                    <span class="input-group-text-s">$</span>
                                    <input type="number" name="pro_yearly_price" step="0.01" min="0"
                                        value="{{ $settings['plans']['pro_yearly_price'] }}"
                                        class="form-control-s" id="yearly-price" oninput="updatePreview()">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label-s">Moneda</label>
                                <select name="pro_currency" class="form-control-alone" id="price-currency" onchange="updatePreview()">
                                    <option value="USD" {{ $settings['plans']['pro_currency'] === 'USD' ? 'selected' : '' }}>USD $</option>
                                    <option value="VES" {{ $settings['plans']['pro_currency'] === 'VES' ? 'selected' : '' }}>VES Bs.</option>
                                    <option value="EUR" {{ $settings['plans']['pro_currency'] === 'EUR' ? 'selected' : '' }}>EUR €</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label-s">Descripción del plan Pro</label>
                                <textarea name="pro_description" rows="2" class="form-control-alone">{{ $settings['plans']['pro_description'] }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="preview-price" id="price-preview">
                        <div style="font-size:.75rem;opacity:.6;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.5rem;">Vista previa Plan Pro</div>
                        <div class="price-main" id="prev-monthly">${{ $settings['plans']['pro_monthly_price'] }} <span class="price-period">/ mes</span></div>
                        <div class="price-yearly" id="prev-yearly">${{ $settings['plans']['pro_yearly_price'] }} / año</div>
                        @if($currentRate > 0)
                        <div style="margin-top:.75rem;background:rgba(251,191,36,.15);border-radius:.6rem;padding:.5rem .75rem;">
                            <div style="font-size:.68rem;opacity:.7;margin-bottom:.2rem;">Equivalente en Bs. (tasa: {{ number_format($currentRate,2) }})</div>
                            <div id="prev-monthly-bs" style="font-size:.95rem;font-weight:800;color:#fde68a;">Bs. {{ number_format($currentRate * $settings['plans']['pro_monthly_price'],2) }} / mes</div>
                            <div id="prev-yearly-bs" style="font-size:.8rem;color:rgba(253,230,138,.75);">Bs. {{ number_format($currentRate * $settings['plans']['pro_yearly_price'],2) }} / año</div>
                        </div>
                        @endif
                        <div style="margin-top:.75rem;font-size:.78rem;opacity:.75;">{{ $settings['plans']['pro_description'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel: Métodos de pago --}}
        <div class="settings-panel" id="panel-metodos">
            @php $pm = $settings['payment_methods']; @endphp

            {{-- Zelle --}}
            <div class="set-section">
                <h5><span class="material-symbols-outlined">credit_card</span> 💳 Zelle</h5>
                <label class="method-toggle">
                    <span class="method-toggle-icon">💳</span>
                    <span class="method-toggle-label">Zelle habilitado</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="zelle_enabled" {{ ($pm['zelle']['enabled'] ?? true) ? 'checked' : '' }} onchange="toggleFields('zelle-fields', this)">
                        <span class="toggle-slider"></span>
                    </label>
                </label>
                <div class="method-fields {{ ($pm['zelle']['enabled'] ?? true) ? 'show' : '' }}" id="zelle-fields">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label-s">Email Zelle</label>
                            <input type="email" name="zelle_email" class="form-control-alone" value="{{ $pm['zelle']['email'] ?? '' }}" placeholder="pagos@tudominio.com">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label-s">Nombre del titular</label>
                            <input type="text" name="zelle_name" class="form-control-alone" value="{{ $pm['zelle']['name'] ?? '' }}" placeholder="Tu nombre o empresa">
                        </div>
                        <div class="col-12">
                            <label class="form-label-s">Nota para el usuario</label>
                            <input type="text" name="zelle_notes" class="form-control-alone" value="{{ $pm['zelle']['notes'] ?? '' }}" placeholder="Instrucciones adicionales...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pago Móvil --}}
            <div class="set-section">
                <h5><span class="material-symbols-outlined">phone_android</span> 📱 Pago Móvil</h5>
                <label class="method-toggle">
                    <span class="method-toggle-icon">📱</span>
                    <span class="method-toggle-label">Pago Móvil habilitado</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="pagomovil_enabled" {{ ($pm['pagomovil']['enabled'] ?? true) ? 'checked' : '' }} onchange="toggleFields('pm-fields', this)">
                        <span class="toggle-slider"></span>
                    </label>
                </label>
                <div class="method-fields {{ ($pm['pagomovil']['enabled'] ?? true) ? 'show' : '' }}" id="pm-fields">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label-s">Banco</label>
                            <input type="text" name="pagomovil_banco" class="form-control-alone" value="{{ $pm['pagomovil']['banco'] ?? '' }}" placeholder="Ej: Banco de Venezuela (0102)">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label-s">Teléfono</label>
                            <input type="text" name="pagomovil_telefono" class="form-control-alone" value="{{ $pm['pagomovil']['telefono'] ?? '' }}" placeholder="0414-0000000">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label-s">Cédula / RIF</label>
                            <input type="text" name="pagomovil_cedula" class="form-control-alone" value="{{ $pm['pagomovil']['cedula'] ?? '' }}" placeholder="V-00000000">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label-s">Nota para el usuario</label>
                            <input type="text" name="pagomovil_notes" class="form-control-alone" value="{{ $pm['pagomovil']['notes'] ?? '' }}" placeholder="Instrucciones adicionales...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- USDT --}}
            <div class="set-section">
                <h5><span class="material-symbols-outlined">currency_bitcoin</span> 🪙 USDT Binance</h5>
                <label class="method-toggle">
                    <span class="method-toggle-icon">🪙</span>
                    <span class="method-toggle-label">USDT Binance habilitado</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="usdt_enabled" {{ ($pm['usdt_binance']['enabled'] ?? true) ? 'checked' : '' }} onchange="toggleFields('usdt-fields', this)">
                        <span class="toggle-slider"></span>
                    </label>
                </label>
                <div class="method-fields {{ ($pm['usdt_binance']['enabled'] ?? true) ? 'show' : '' }}" id="usdt-fields">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="form-label-s">Red</label>
                            <input type="text" name="usdt_red" class="form-control-alone" value="{{ $pm['usdt_binance']['red'] ?? 'TRC20 (Tron)' }}" placeholder="TRC20 (Tron)">
                        </div>
                        <div class="col-sm-8">
                            <label class="form-label-s">Dirección wallet</label>
                            <input type="text" name="usdt_wallet" class="form-control-alone" value="{{ $pm['usdt_binance']['wallet'] ?? '' }}" placeholder="TXxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label-s">Nota para el usuario</label>
                            <input type="text" name="usdt_notes" class="form-control-alone" value="{{ $pm['usdt_binance']['notes'] ?? '' }}" placeholder="Instrucciones adicionales...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel: Límites plan Free --}}
        <div class="settings-panel" id="panel-limites">
            <div class="set-section">
                <h5><span class="material-symbols-outlined">tune</span> Límites del Plan Gratuito</h5>
                <p style="font-size:.83rem;color:#60708d;margin-bottom:1.25rem;">Define cuántos registros puede tener un usuario del plan Free antes de requerir upgrade.</p>
                <div class="row g-3">
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label-s">🐓 Límite de gallos</label>
                        <input type="number" name="free_gallos_limit" min="0" class="form-control-alone"
                            value="{{ $settings['plans']['free_gallos_limit'] }}">
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label-s">🥚 Límite de gallinas</label>
                        <input type="number" name="free_gallinas_limit" min="0" class="form-control-alone"
                            value="{{ $settings['plans']['free_gallinas_limit'] }}">
                    </div>
                </div>
                <div class="mt-3 p-3 rounded-3" style="background:#fffbeb;border:1px solid #fde68a;font-size:.82rem;color:#92400e;">
                    ⚠️ Los cambios en límites solo afectan a nuevos registros. Los usuarios que ya superan el límite no pierden sus datos existentes.
                </div>
            </div>
        </div>

        {{-- Guardar --}}
        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="save-btn">
                <span class="material-symbols-outlined float-start me-2">save</span>
                Guardar configuración
            </button>
        </div>
    </form>
</section>
@endsection

@section('scripts')
<script>
// ── Actualizar tasa del dólar (AJAX) ──
async function doRefreshRate() {
    const btn = document.getElementById('btn-refresh-rate');
    const msg = document.getElementById('refresh-rate-msg');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:.95rem;animation:spin 1s linear infinite">refresh</span> Consultando API...';
    msg.style.color = 'rgba(253,230,138,.6)';
    msg.textContent = 'Conectando con la API…';

    try {
        const res = await fetch('{{ route("superadmin.settings.refresh_rate") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });
        const data = await res.json();

        if (data.ok) {
            const tasa = parseFloat(data.tasa).toFixed(2);
            // Actualizar display en tarjeta sin recargar
            document.getElementById('rate-big-display').textContent = 'Bs. ' + parseFloat(data.tasa).toLocaleString('es-VE', {minimumFractionDigits:2, maximumFractionDigits:2});
            document.getElementById('rate-fecha-display').textContent = 'Última actualización: ' + data.fecha;
            msg.style.color = '#86efac';
            msg.textContent = '✓ Bs. ' + tasa + ' — actualizado el ' + data.fecha;
            if (window.Swal) {
                Swal.fire({ icon: 'success', title: '¡Tasa actualizada!', html: '<span style="font-size:2rem;font-weight:900;">Bs. ' + tasa + '</span><br><small>por dólar</small>', timer: 3000, showConfirmButton: false });
            }
        } else {
            msg.style.color = '#fca5a5';
            msg.textContent = '✗ ' + (data.error || 'Error desconocido');
            if (window.Swal) {
                Swal.fire({ icon: 'error', title: 'No se pudo actualizar', text: data.error || 'Verifica que la URL de la API esté configurada correctamente en el formulario de abajo.' });
            }
        }
    } catch (e) {
        msg.style.color = '#fca5a5';
        msg.textContent = '✗ Error de red: ' + e.message;
        if (window.Swal) {
            Swal.fire({ icon: 'error', title: 'Error de conexión', text: e.message });
        }
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:.95rem;">refresh</span> Actualizar tasa ahora';
    }
}

// ── Tabs ──
document.querySelectorAll('.stab').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.stab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('show'));
        this.classList.add('active');
        document.getElementById('panel-' + this.dataset.panel).classList.add('show');
    });
});

// ── Toggle campos métodos ──
function toggleFields(id, checkbox) {
    const el = document.getElementById(id);
    el.classList.toggle('show', checkbox.checked);
}

// ── Preview precio ──
const currentRate = {{ $currentRate ?? 1 }};
function updatePreview() {
    const monthly  = parseFloat(document.getElementById('monthly-price').value) || 0;
    const yearly   = parseFloat(document.getElementById('yearly-price').value) || 0;
    const currency = document.getElementById('price-currency').value;
    const symbol   = currency === 'USD' ? '$' : currency === 'EUR' ? '€' : 'Bs.';
    document.getElementById('prev-monthly').innerHTML = `${symbol}${monthly.toFixed(2)} <span class="price-period">/ mes</span>`;
    document.getElementById('prev-yearly').textContent = `${symbol}${yearly.toFixed(2)} / año`;
    // Equivalencias Bs.
    const mBs = document.getElementById('prev-monthly-bs');
    const yBs = document.getElementById('prev-yearly-bs');
    if (mBs && currentRate > 0) {
        mBs.textContent = `Bs. ${(monthly * currentRate).toLocaleString('es-VE', {minimumFractionDigits:2, maximumFractionDigits:2})} / mes`;
        yBs.textContent = `Bs. ${(yearly  * currentRate).toLocaleString('es-VE', {minimumFractionDigits:2, maximumFractionDigits:2})} / año`;
    }
}
</script>
@endsection

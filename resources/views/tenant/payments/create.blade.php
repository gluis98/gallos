@extends('layouts.app')

@section('styles')
<style>
    /* ── Hero / cabecera ──────────────────────────────── */
    .payment-hero {
        background: linear-gradient(135deg, #1a2648 0%, #2d4278 60%, #1a3a2a 100%);
        border-radius: 1.4rem; padding: 2rem 2rem 0; margin-bottom: 0;
        position: relative; overflow: hidden;
    }
    .payment-hero::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(circle at 85% 15%, rgba(34,197,94,.25) 0%, transparent 50%),
                    radial-gradient(circle at 10% 80%, rgba(59,130,246,.18) 0%, transparent 45%);
    }
    .payment-hero-content { position: relative; z-index: 1; padding-bottom: 2rem; }
    .payment-hero h1 { font-size: clamp(1.4rem,3vw,2rem); font-weight: 900; color: #fff; margin-bottom: .35rem; }
    .payment-hero p  { color: rgba(255,255,255,.7); font-size: .9rem; margin: 0; }

    /* ── Steps indicator ─────────────────────────────── */
    .steps-bar {
        display: flex; gap: 0; position: relative; z-index: 1;
        border-top: 1px solid rgba(255,255,255,.12); padding-top: 1.25rem;
        margin-top: 1.25rem;
    }
    .step {
        flex: 1; text-align: center; position: relative;
        padding-bottom: 1.25rem;
    }
    .step::after {
        content: ''; position: absolute; top: 18px; left: 50%; right: -50%;
        height: 2px; background: rgba(255,255,255,.18);
    }
    .step:last-child::after { display: none; }
    .step.done::after, .step.active::after { background: rgba(34,197,94,.5); }
    .step-dot {
        width: 36px; height: 36px; border-radius: 50%; margin: 0 auto .5rem;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: .85rem; border: 2px solid rgba(255,255,255,.25);
        background: rgba(255,255,255,.1); color: rgba(255,255,255,.5);
        position: relative; z-index: 2;
    }
    .step.done .step-dot  { background: #22c55e; border-color: #22c55e; color: #fff; }
    .step.active .step-dot{ background: #fff; border-color: #fff; color: #1a2648; }
    .step-label { font-size: .72rem; color: rgba(255,255,255,.5); }
    .step.done .step-label,
    .step.active .step-label { color: rgba(255,255,255,.9); }

    /* ── Cuerpo del formulario ───────────────────────── */
    .payment-body { background: #fff; border: 1px solid #e8eef8; border-radius: 0 0 1.4rem 1.4rem; border-top: none; padding: 2rem; box-shadow: 0 12px 40px rgba(16,39,77,.1); }

    /* ── Secciones ───────────────────────────────────── */
    .pay-section { margin-bottom: 1.75rem; }
    .pay-section-title {
        display: flex; align-items: center; gap: .5rem;
        font-size: .75rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: .08em; color: #60708d; margin-bottom: 1rem;
        padding-bottom: .5rem; border-bottom: 1px solid #f0f4fc;
    }
    .pay-section-title .material-symbols-outlined { font-size: 1rem; color: #3b82f6; }

    /* ── Método de pago cards ────────────────────────── */
    .method-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: .75rem; }
    @media (max-width: 480px) { .method-grid { grid-template-columns: 1fr; } }
    .method-card {
        border: 2px solid #e8eef8; border-radius: 1rem;
        padding: 1rem .85rem; text-align: center; cursor: pointer;
        transition: all .18s ease; background: #fafbff; position: relative;
    }
    .method-card:hover { border-color: #93c5fd; background: #f0f7ff; }
    .method-card.selected { border-color: #3b82f6; background: #eff6ff; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
    .method-card input[type=radio] { position: absolute; opacity: 0; pointer-events: none; }
    .method-icon { font-size: 1.85rem; margin-bottom: .4rem; display: block; }
    .method-icon-img { width: 2.25rem; height: 2.25rem; margin: 0 auto .4rem; display: block; object-fit: contain; }
    .pib-title-icon { width: 1.1rem; height: 1.1rem; flex-shrink: 0; }
    .method-name { font-weight: 700; font-size: .85rem; color: #1a2648; }
    .method-desc { font-size: .7rem; color: #60708d; margin-top: .15rem; }
    .method-card.selected .method-name { color: #1d4ed8; }
    .method-check { display: none; position: absolute; top: 8px; right: 8px; width: 18px; height: 18px; border-radius: 50%; background: #3b82f6; align-items: center; justify-content: center; }
    .method-card.selected .method-check { display: flex; }
    .method-check .material-symbols-outlined { font-size: .75rem; color: #fff; }

    /* ── Info de pago por método ─────────────────────── */
    .payment-info-box {
        border-radius: .85rem; padding: 1rem 1.2rem; margin-top: .75rem;
        display: none; border: 1.5px solid;
    }
    .payment-info-box.show { display: block; }
    .pib-zelle     { background: #eff6ff; border-color: #93c5fd; }
    .pib-pagomovil { background: #fdf4ff; border-color: #e9d5ff; }
    .pib-usdt      { background: #fefce8; border-color: #fde68a; }
    .pib-title { font-weight: 700; font-size: .82rem; margin-bottom: .5rem; display: flex; align-items: center; gap: .35rem; }
    .pib-row { display: flex; justify-content: space-between; align-items: center; padding: .3rem 0; font-size: .82rem; border-bottom: 1px solid rgba(0,0,0,.05); }
    .pib-row:last-child { border-bottom: none; }
    .pib-label { color: #60708d; }
    .pib-val { font-weight: 700; color: #1a2648; display: flex; align-items: center; gap: .35rem; }
    .copy-btn { cursor: pointer; background: none; border: none; padding: 0; color: #3b82f6; display: flex; align-items: center; }
    .copy-btn .material-symbols-outlined { font-size: .85rem; }
    .copy-btn:hover { color: #1d4ed8; }

    /* ── Inputs ──────────────────────────────────────── */
    .form-label-pay { font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: .35rem; display: block; }
    .required-star { color: #ef4444; }
    .input-icon-wrap { position: relative; }
    .input-icon-wrap .material-symbols-outlined {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        font-size: 1rem; color: #9baac7; pointer-events: none;
    }
    .input-icon-wrap .form-control,
    .input-icon-wrap .form-select { padding-left: 38px; }
    .amount-currency-group { display: flex; gap: .5rem; }
    .amount-currency-group .form-control { flex: 1; }
    .amount-currency-group .form-select { width: 100px; flex-shrink: 0; }

    /* ── Dropzone comprobante ────────────────────────── */
    .proof-dropzone {
        border: 2px dashed #b8c8e8; border-radius: 1rem;
        background: #f8fbff; text-align: center;
        padding: 1.75rem 1rem; cursor: pointer;
        transition: all .2s ease; position: relative;
    }
    .proof-dropzone:hover, .proof-dropzone.is-dragover { border-color: #3b82f6; background: #eff6ff; }
    .proof-dropzone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
    .proof-dropzone-icon { font-size: 2.5rem; color: #9baac7; margin-bottom: .5rem; display: block; transition: color .2s; }
    .proof-dropzone:hover .proof-dropzone-icon, .proof-dropzone.is-dragover .proof-dropzone-icon { color: #3b82f6; }
    .proof-dropzone-text { font-weight: 700; color: #374151; font-size: .9rem; }
    .proof-dropzone-sub  { font-size: .75rem; color: #9baac7; margin-top: .2rem; }
    #proof-preview-wrap { display: none; margin-top: 1rem; position: relative; }
    #proof-preview-wrap img { width: 100%; max-height: 260px; object-fit: contain; border-radius: .85rem; border: 1.5px solid #e8eef8; }
    .proof-remove-btn {
        position: absolute; top: 8px; right: 8px;
        width: 30px; height: 30px; border-radius: 50%; border: none;
        background: rgba(220,38,38,.85); color: #fff; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
    }
    .proof-remove-btn .material-symbols-outlined { font-size: .9rem; }
    .proof-filename { font-size: .78rem; color: #60708d; margin-top: .4rem; text-align: center; }

    /* ── Botón submit ─────────────────────────────────── */
    .btn-submit-pay {
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: #fff; border: none; border-radius: .85rem;
        padding: .85rem 2rem; font-weight: 700; font-size: 1rem;
        width: 100%; cursor: pointer; transition: all .22s;
        display: flex; align-items: center; justify-content: center; gap: .5rem;
        box-shadow: 0 6px 20px rgba(22,163,74,.3);
    }
    .btn-submit-pay:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(22,163,74,.4); }
    .btn-submit-pay .material-symbols-outlined { font-size: 1.15rem; }

    /* ── Alert éxito ──────────────────────────────────── */
    .success-banner {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border: 1.5px solid #86efac; border-radius: 1rem;
        padding: 1.25rem 1.5rem; display: flex; gap: 1rem; align-items: flex-start;
        margin-bottom: 1.5rem;
    }
    .success-banner .banner-icon { font-size: 1.75rem; flex-shrink: 0; }
    .success-banner .banner-title { font-weight: 800; color: #14532d; font-size: .95rem; }
    .success-banner .banner-text  { font-size: .82rem; color: #166534; margin-top: .15rem; }

    /* ── Error messages ───────────────────────────────── */
    .err-list { background: #fff1f2; border: 1.5px solid #fca5a5; border-radius: .85rem; padding: 1rem 1.25rem; margin-bottom: 1.5rem; }
    .err-list ul { margin: 0; padding-left: 1.25rem; }
    .err-list li { font-size: .82rem; color: #dc2626; }
</style>
@endsection

@section('content')
<div style="max-width: 680px; margin: 0 auto;">

    {{-- Hero + steps --}}
    <div class="payment-hero">
        <div class="payment-hero-content">
            <div class="d-flex align-items-center gap-3">
                <div style="width:52px;height:52px;border-radius:1rem;background:rgba(34,197,94,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(34,197,94,.3);">
                    <span class="material-symbols-outlined" style="font-size:1.6rem;color:#4ade80;">upgrade</span>
                </div>
                <div>
                    <h1>Activar Plan Pro</h1>
                    <p>Envía tu comprobante de pago y un administrador activará tu plan en minutos.</p>
                </div>
            </div>

            <div class="steps-bar mt-4">
                <div class="step done">
                    <div class="step-dot"><span class="material-symbols-outlined" style="font-size:.95rem;">check</span></div>
                    <div class="step-label">Cuenta creada</div>
                </div>
                <div class="step active">
                    <div class="step-dot">2</div>
                    <div class="step-label">Enviar pago</div>
                </div>
                <div class="step">
                    <div class="step-dot">3</div>
                    <div class="step-label">Verificación</div>
                </div>
                <div class="step">
                    <div class="step-dot">4</div>
                    <div class="step-label">Pro activo 🚀</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Body --}}
    <div class="payment-body">

        @if(session('ok'))
        <div class="success-banner">
            <span class="banner-icon">🎉</span>
            <div>
                <div class="banner-title">¡Comprobante enviado correctamente!</div>
                <div class="banner-text">{{ session('ok') }}</div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="err-list">
            <div style="font-weight:700;color:#dc2626;font-size:.85rem;margin-bottom:.5rem;">Por favor corrige los siguientes errores:</div>
            <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="post" action="{{ route('tenant.payments.store') }}" enctype="multipart/form-data" id="form-payment">
            @csrf

            {{-- Sección 1: Método de pago --}}
            <div class="pay-section">
                <div class="pay-section-title">
                    <span class="material-symbols-outlined">credit_card</span>
                    Método de pago <span class="required-star ms-1">*</span>
                </div>

                <div class="method-grid">
                    <label class="method-card {{ old('method') === 'zelle' || !old('method') ? 'selected' : '' }}" id="card-zelle">
                        <input type="radio" name="method" value="zelle" {{ old('method', 'zelle') === 'zelle' ? 'checked' : '' }}>
                        <div class="method-check"><span class="material-symbols-outlined">check</span></div>
                        <span class="method-icon">💳</span>
                        <div class="method-name">Zelle</div>
                        <div class="method-desc">Transferencia bancaria USA</div>
                    </label>
                    <label class="method-card {{ old('method') === 'pagomovil' ? 'selected' : '' }}" id="card-pagomovil">
                        <input type="radio" name="method" value="pagomovil" {{ old('method') === 'pagomovil' ? 'checked' : '' }}>
                        <div class="method-check"><span class="material-symbols-outlined">check</span></div>
                        <span class="method-icon">📱</span>
                        <div class="method-name">Pago Móvil</div>
                        <div class="method-desc">Transferencia bancaria VEN</div>
                    </label>
                    <label class="method-card {{ old('method') === 'usdt_binance' ? 'selected' : '' }}" id="card-usdt">
                        <input type="radio" name="method" value="usdt_binance" {{ old('method') === 'usdt_binance' ? 'checked' : '' }}>
                        <div class="method-check"><span class="material-symbols-outlined">check</span></div>
                        <img src="{{ asset('img/usdt.svg') }}" alt="" class="method-icon-img" width="36" height="36">
                        <div class="method-name">USDT Binance</div>
                        <div class="method-desc">Criptomoneda — Red TRC20</div>
                    </label>
                </div>

                {{-- Info de pago por método --}}
                @php $pm = $settings['payment_methods'] ?? []; @endphp

                <div class="payment-info-box pib-zelle {{ old('method', 'zelle') === 'zelle' ? 'show' : '' }}" id="info-zelle">
                    <div class="pib-title" style="color:#1d4ed8;">💳 Datos para pago por Zelle</div>
                    @if(!empty($pm['zelle']['email']))
                    <div class="pib-row">
                        <span class="pib-label">Email Zelle</span>
                        <span class="pib-val">{{ $pm['zelle']['email'] }} <button type="button" class="copy-btn" onclick="copyText('{{ $pm['zelle']['email'] }}',this)" title="Copiar"><span class="material-symbols-outlined">content_copy</span></button></span>
                    </div>
                    @endif
                    @if(!empty($pm['zelle']['name']))
                    <div class="pib-row"><span class="pib-label">Nombre</span><span class="pib-val">{{ $pm['zelle']['name'] }}</span></div>
                    @endif
                    @if(!empty($pm['zelle']['notes']))
                    <div class="pib-row" style="border:none;padding-bottom:0;"><span class="pib-label" style="font-size:.72rem;color:#93c5fd;">⚠️ {{ $pm['zelle']['notes'] }}</span></div>
                    @endif
                </div>

                <div class="payment-info-box pib-pagomovil {{ old('method') === 'pagomovil' ? 'show' : '' }}" id="info-pagomovil">
                    <div class="pib-title" style="color:#7c3aed;">📱 Datos para Pago Móvil</div>
                    @if(!empty($pm['pagomovil']['banco']))
                    <div class="pib-row"><span class="pib-label">Banco</span><span class="pib-val">{{ $pm['pagomovil']['banco'] }}</span></div>
                    @endif
                    @if(!empty($pm['pagomovil']['telefono']))
                    <div class="pib-row"><span class="pib-label">Teléfono</span><span class="pib-val">{{ $pm['pagomovil']['telefono'] }} <button type="button" class="copy-btn" onclick="copyText('{{ $pm['pagomovil']['telefono'] }}',this)" title="Copiar"><span class="material-symbols-outlined">content_copy</span></button></span></div>
                    @endif
                    @if(!empty($pm['pagomovil']['cedula']))
                    <div class="pib-row"><span class="pib-label">Cédula / RIF</span><span class="pib-val">{{ $pm['pagomovil']['cedula'] }} <button type="button" class="copy-btn" onclick="copyText('{{ $pm['pagomovil']['cedula'] }}',this)" title="Copiar"><span class="material-symbols-outlined">content_copy</span></button></span></div>
                    @endif
                    @if(!empty($pm['pagomovil']['notes']))
                    <div class="pib-row" style="border:none;padding-bottom:0;"><span class="pib-label" style="font-size:.72rem;color:#a78bfa;">⚠️ {{ $pm['pagomovil']['notes'] }}</span></div>
                    @endif
                </div>

                <div class="payment-info-box pib-usdt {{ old('method') === 'usdt_binance' ? 'show' : '' }}" id="info-usdt">
                    <div class="pib-title" style="color:#b45309;"><img src="{{ asset('img/usdt.svg') }}" alt="" class="pib-title-icon" width="18" height="18"> Datos para USDT Binance</div>
                    @if(!empty($pm['usdt_binance']['red']))
                    <div class="pib-row"><span class="pib-label">Red</span><span class="pib-val">{{ $pm['usdt_binance']['red'] }}</span></div>
                    @endif
                    @if(!empty($pm['usdt_binance']['wallet']))
                    <div class="pib-row"><span class="pib-label">Wallet</span><span class="pib-val" style="font-size:.75rem;word-break:break-all;">{{ $pm['usdt_binance']['wallet'] }} <button type="button" class="copy-btn" onclick="copyText('{{ $pm['usdt_binance']['wallet'] }}',this)" title="Copiar"><span class="material-symbols-outlined">content_copy</span></button></span></div>
                    @endif
                    @if(!empty($pm['usdt_binance']['notes']))
                    <div class="pib-row" style="border:none;padding-bottom:0;"><span class="pib-label" style="font-size:.72rem;color:#d97706;">⚠️ {{ $pm['usdt_binance']['notes'] }}</span></div>
                    @endif
                </div>
            </div>

            {{-- Sección 2: Monto --}}
            <div class="pay-section">
                <div class="pay-section-title">
                    <span class="material-symbols-outlined">payments</span>
                    Detalle del monto
                </div>
                <div class="row g-3">
                    <div class="col-sm-8">
                        <label class="form-label-pay">Monto pagado <span class="required-star">*</span></label>
                        <div class="amount-currency-group">
                            <div class="input-icon-wrap flex-grow-1">
                                <span class="material-symbols-outlined">attach_money</span>
                                <input type="number" step="0.01" name="amount" class="form-control" required
                                    value="{{ old('amount') }}" placeholder="0.00">
                            </div>
                            <select name="currency" class="form-select" required>
                                <option value="USD" @selected(old('currency','USD')==='USD')>USD $</option>
                                <option value="VES" @selected(old('currency')==='VES')>VES Bs.</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label-pay">Referencia / últimos dígitos</label>
                        <div class="input-icon-wrap">
                            <span class="material-symbols-outlined">tag</span>
                            <input type="text" name="reference" class="form-control"
                                value="{{ old('reference') }}" placeholder="Ej: 1234">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label-pay">Notas adicionales</label>
                        <textarea name="notes" class="form-control" rows="2"
                            placeholder="Cualquier información extra que desees indicar...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Sección 3: Comprobante --}}
            <div class="pay-section">
                <div class="pay-section-title">
                    <span class="material-symbols-outlined">receipt_long</span>
                    Comprobante de pago
                </div>

                <div class="proof-dropzone" id="proof-dropzone">
                    <input type="file" name="proof" id="proof-input" accept="image/*">
                    <span class="material-symbols-outlined proof-dropzone-icon">cloud_upload</span>
                    <div class="proof-dropzone-text">Arrastra tu comprobante aquí o haz clic para seleccionar</div>
                    <div class="proof-dropzone-sub">JPG, PNG, WEBP — máximo 5 MB</div>
                </div>

                <div id="proof-preview-wrap">
                    <img id="proof-preview-img" src="" alt="Comprobante">
                    <button type="button" class="proof-remove-btn" id="proof-remove" title="Quitar imagen">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                    <div class="proof-filename" id="proof-filename"></div>
                </div>
            </div>

            {{-- Resumen antes de enviar --}}
            <div class="pay-section" id="payment-summary" style="display:none;">
                <div class="pay-section-title">
                    <span class="material-symbols-outlined">summarize</span>
                    Resumen de tu solicitud
                </div>
                <div style="background:#f8faff;border:1px solid #e8eef8;border-radius:.85rem;padding:1rem 1.25rem;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;font-size:.85rem;">
                        <div style="color:#60708d;">Método</div>     <div id="sum-method" style="font-weight:700;color:#1a2648;"></div>
                        <div style="color:#60708d;">Monto</div>      <div id="sum-amount" style="font-weight:700;color:#1a2648;"></div>
                        <div style="color:#60708d;">Referencia</div> <div id="sum-ref"    style="font-weight:700;color:#1a2648;"></div>
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="d-flex flex-column gap-2 mt-1">
                <button type="submit" class="btn-submit-pay">
                    <span class="material-symbols-outlined">send</span>
                    Enviar comprobante de pago
                </button>
                <a href="{{ route('plans') }}" style="text-align:center;font-size:.85rem;color:#60708d;text-decoration:none;padding:.4rem;">
                    <span class="material-symbols-outlined float-start me-1" style="font-size:.95rem;vertical-align:-3px;">arrow_back</span>
                    Volver a planes
                </a>
            </div>
        </form>

        {{-- Garantía / tranquilidad --}}
        <div style="display:flex;gap:.75rem;align-items:flex-start;margin-top:1.75rem;padding-top:1.5rem;border-top:1px solid #f0f4fc;">
            <span class="material-symbols-outlined" style="color:#22c55e;font-size:1.4rem;flex-shrink:0;margin-top:.05rem;">verified_user</span>
            <div>
                <div style="font-size:.82rem;font-weight:700;color:#1a2648;">Tu pago está seguro</div>
                <div style="font-size:.75rem;color:#60708d;line-height:1.5;">Verificamos cada comprobante manualmente. El acceso Pro se activa en un plazo de 1–24 horas hábiles tras la confirmación.</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ── Selección de método con animación ──
    const methodCards = document.querySelectorAll('.method-card');
    const infoBoxes   = { zelle: document.getElementById('info-zelle'), pagomovil: document.getElementById('info-pagomovil'), usdt_binance: document.getElementById('info-usdt') };

    methodCards.forEach(card => {
        card.addEventListener('click', function() {
            methodCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            const val = this.querySelector('input[type=radio]').value;
            Object.entries(infoBoxes).forEach(([k, box]) => {
                box.classList.remove('show');
                if (k === val) box.classList.add('show');
            });
            updateSummary();
        });
    });

    // ── Dropzone comprobante ──
    const dropzone   = document.getElementById('proof-dropzone');
    const proofInput = document.getElementById('proof-input');
    const prevWrap   = document.getElementById('proof-preview-wrap');
    const prevImg    = document.getElementById('proof-preview-img');
    const prevName   = document.getElementById('proof-filename');
    const removeBtn  = document.getElementById('proof-remove');

    const showPreview = (file) => {
        if (!file || !file.type.startsWith('image/')) return;
        const url = URL.createObjectURL(file);
        prevImg.src = url;
        prevName.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
        prevWrap.style.display = 'block';
        dropzone.style.display = 'none';
    };

    proofInput.addEventListener('change', () => { if (proofInput.files[0]) showPreview(proofInput.files[0]); });

    ['dragenter','dragover'].forEach(ev => dropzone.addEventListener(ev, e => { e.preventDefault(); dropzone.classList.add('is-dragover'); }));
    ['dragleave','drop'].forEach(ev => dropzone.addEventListener(ev, e => { e.preventDefault(); dropzone.classList.remove('is-dragover'); }));
    dropzone.addEventListener('drop', e => {
        const file = e.dataTransfer?.files?.[0];
        if (file) { const dt = new DataTransfer(); dt.items.add(file); proofInput.files = dt.files; showPreview(file); }
    });

    removeBtn.addEventListener('click', () => {
        proofInput.value = '';
        prevImg.src = ''; prevWrap.style.display = 'none';
        dropzone.style.display = 'block';
    });

    // ── Resumen dinámico ──
    const usdtIconHtml = '<img src="{{ asset("img/usdt.svg") }}" alt="" style="width:1rem;height:1rem;vertical-align:-2px;margin-right:2px;">';
    const methodLabels = { zelle: '💳 Zelle', pagomovil: '📱 Pago Móvil', usdt_binance: usdtIconHtml + ' USDT Binance' };
    const amountInput  = document.querySelector('[name=amount]');
    const currencyEl   = document.querySelector('[name=currency]');
    const refInput     = document.querySelector('[name=reference]');
    const summaryBox   = document.getElementById('payment-summary');

    function updateSummary() {
        const method  = document.querySelector('[name=method]:checked')?.value;
        const amount  = amountInput.value;
        const currency= currencyEl.value;
        const ref     = refInput.value;
        if (method && amount) {
            const sumMethod = document.getElementById('sum-method');
            if (method === 'usdt_binance') {
                sumMethod.innerHTML = methodLabels[method] || method;
            } else {
                sumMethod.textContent = methodLabels[method] || method;
            }
            document.getElementById('sum-amount').textContent = amount + ' ' + currency;
            document.getElementById('sum-ref').textContent    = ref || '—';
            summaryBox.style.display = 'block';
        } else {
            summaryBox.style.display = 'none';
        }
    }

    [amountInput, currencyEl, refInput].forEach(el => el.addEventListener('input', updateSummary));
    updateSummary();

    // ── Copiar al portapapeles ──
    window.copyText = function(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const icon = btn.querySelector('.material-symbols-outlined');
            icon.textContent = 'check_circle';
            icon.style.color = '#22c55e';
            setTimeout(() => { icon.textContent = 'content_copy'; icon.style.color = ''; }, 1800);
        });
    };
});
</script>
@endsection

@extends('layouts.app')

@section('title', 'Activar Plan Pro')

@section('styles')
<style>
.billing-wrap { max-width: 720px; margin: 0 auto; }
.billing-hero {
    background: linear-gradient(135deg, #1a2648, #2d4278);
    border-radius: 1.2rem 1.2rem 0 0; padding: 1.75rem 2rem; color: #fff;
}
.billing-body {
    background: #fff; border: 1px solid #e8eef8; border-top: none;
    border-radius: 0 0 1.2rem 1.2rem; padding: 1.75rem 2rem;
    box-shadow: 0 12px 40px rgba(16,39,77,.08);
}
.plan-pill {
    display: inline-flex; align-items: center; gap: .35rem;
    background: rgba(255,255,255,.15); color: #fff; border-radius: 999px;
    padding: .25rem .75rem; font-size: .78rem; font-weight: 700;
}
.price-big { font-size: 2.2rem; font-weight: 900; line-height: 1.1; margin: .5rem 0 .25rem; }
.pay-section { margin-bottom: 1.5rem; }
.pay-section-title {
    display: flex; align-items: center; gap: .45rem;
    font-size: .72rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: .08em; color: #60708d; margin-bottom: .85rem;
    padding-bottom: .5rem; border-bottom: 1px solid #f0f4fc;
}
.pay-section-title .material-symbols-outlined { font-size: 1rem; color: #3b82f6; }
.method-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: .75rem; }
@media (max-width: 520px) { .method-grid { grid-template-columns: 1fr; } }
.method-card {
    border: 2px solid #e8eef8; border-radius: 1rem;
    padding: 1rem .85rem; text-align: center; cursor: pointer;
    transition: all .18s ease; background: #fafbff; position: relative;
}
.method-card:hover { border-color: #93c5fd; background: #f0f7ff; }
.method-card.selected { border-color: #3b82f6; background: #eff6ff; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
.method-card input[type=radio] { position: absolute; opacity: 0; pointer-events: none; }
.method-icon { font-size: 1.75rem; margin-bottom: .35rem; display: block; }
.method-icon-img { width: 2rem; height: 2rem; margin: 0 auto .35rem; display: block; object-fit: contain; }
.method-name { font-weight: 700; font-size: .88rem; color: #1a2648; }
.method-desc { font-size: .72rem; color: #60708d; margin-top: .15rem; }
.method-check {
    display: none; position: absolute; top: 8px; right: 8px;
    width: 18px; height: 18px; border-radius: 50%; background: #3b82f6;
    align-items: center; justify-content: center; color: #fff;
}
.method-card.selected .method-check { display: flex; }
.method-check .material-symbols-outlined { font-size: .85rem; }
.payment-info-box {
    display: none; margin-top: 1rem; border-radius: .9rem; padding: 1rem 1.15rem;
    border: 1px solid #dbeafe; background: #f8fbff;
}
.payment-info-box.show { display: block; }
.pib-title { font-weight: 800; font-size: .9rem; margin-bottom: .65rem; }
.pib-row {
    display: flex; justify-content: space-between; gap: .75rem; align-items: center;
    padding: .35rem 0; border-bottom: 1px solid rgba(59,130,246,.08); font-size: .85rem;
}
.pib-row:last-child { border-bottom: none; }
.pib-label { color: #60708d; flex-shrink: 0; }
.pib-val { font-weight: 700; color: #1a2648; text-align: right; word-break: break-all; }
.copy-btn {
    border: none; background: transparent; color: #3b82f6; cursor: pointer;
    padding: 0; vertical-align: middle; margin-left: .25rem;
}
.copy-btn .material-symbols-outlined { font-size: 1rem; }
.form-label-pay { font-size: .8rem; font-weight: 700; color: #374151; margin-bottom: .35rem; display: block; }
.btn-submit-pay {
    background: linear-gradient(135deg, #16a34a, #15803d);
    color: #fff; border: none; border-radius: .85rem;
    padding: .85rem 2rem; font-weight: 700; font-size: 1rem;
    width: 100%; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: .5rem;
    box-shadow: 0 6px 20px rgba(22,163,74,.3);
}
.btn-paypal {
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    width: 100%; padding: .85rem; border: none; border-radius: .85rem;
    background: #0070ba; color: #fff; font-weight: 700; cursor: pointer;
    font-family: inherit; box-shadow: 0 6px 20px rgba(0,112,186,.3);
}
.btn-paypal:hover { background: #005ea6; }
.btn-paypal:disabled { opacity: .7; cursor: wait; }
.required-star { color: #ef4444; }
#panel-manual, #panel-paypal { display: none; }
#panel-manual.show, #panel-paypal.show { display: block; }
</style>
@endsection

@section('content')
@php
    $pm = $settings['payment_methods'] ?? [];
    $enabledMethods = [];
    if (!empty($pm['zelle']['enabled'])) $enabledMethods['zelle'] = true;
    if (!empty($pm['pagomovil']['enabled'])) $enabledMethods['pagomovil'] = true;
    if (!empty($pm['usdt_binance']['enabled'])) $enabledMethods['usdt_binance'] = true;
    $defaultMethod = old('method', $paypalEnabled ? 'paypal' : (array_key_first($enabledMethods) ?: 'zelle'));
@endphp

<div class="billing-wrap">
    <div class="billing-hero">
        <div class="plan-pill mb-2">
            <span class="material-symbols-outlined" style="font-size:1rem;">workspace_premium</span>
            {{ $config['name'] }}
        </div>
        <div class="price-big">${{ number_format($config['amount'], 2) }} <small style="font-size:1rem;font-weight:500;opacity:.75;">{{ $config['currency'] }}</small></div>
        @if($rate > 0)
            <div style="opacity:.75;font-size:.88rem;">≈ Bs. {{ number_format($config['amount'] * $rate, 2) }} (tasa BCV)</div>
        @endif
        <p style="margin:.75rem 0 0;opacity:.8;font-size:.9rem;">Elige cómo pagar. PayPal activa al instante; transferencias se verifican en menos de 24 h.</p>
    </div>

    <div class="billing-body">
        @if(session('ok'))
            <div class="alert alert-success" style="border-radius:.85rem;">{{ session('ok') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" style="border-radius:.85rem;">{{ $errors->first() }}</div>
        @endif

        @if($summary['is_pro'] ?? false)
            <div class="alert alert-success" style="border-radius:.85rem;">
                Ya tienes Plan Pro activo
                @if($summary['ends_at'] ?? null)
                    hasta {{ \Carbon\Carbon::parse($summary['ends_at'])->format('d/m/Y') }}.
                @endif
                Puedes renovar o extender con un nuevo pago.
            </div>
        @endif

        <div class="pay-section">
            <div class="pay-section-title">
                <span class="material-symbols-outlined">credit_card</span>
                Método de pago <span class="required-star">*</span>
            </div>

            <div class="method-grid" id="method-grid">
                <label class="method-card {{ $defaultMethod === 'paypal' ? 'selected' : '' }}" data-method="paypal">
                    <input type="radio" name="ui_method" value="paypal" {{ $defaultMethod === 'paypal' ? 'checked' : '' }}>
                    <div class="method-check"><span class="material-symbols-outlined">check</span></div>
                    <span class="method-icon">🅿️</span>
                    <div class="method-name">PayPal</div>
                    <div class="method-desc">Activación instantánea</div>
                </label>

                @if(!empty($enabledMethods['zelle']))
                <label class="method-card {{ $defaultMethod === 'zelle' ? 'selected' : '' }}" data-method="zelle">
                    <input type="radio" name="ui_method" value="zelle" {{ $defaultMethod === 'zelle' ? 'checked' : '' }}>
                    <div class="method-check"><span class="material-symbols-outlined">check</span></div>
                    <span class="method-icon">💳</span>
                    <div class="method-name">Zelle</div>
                    <div class="method-desc">Transferencia USA</div>
                </label>
                @endif

                @if(!empty($enabledMethods['pagomovil']))
                <label class="method-card {{ $defaultMethod === 'pagomovil' ? 'selected' : '' }}" data-method="pagomovil">
                    <input type="radio" name="ui_method" value="pagomovil" {{ $defaultMethod === 'pagomovil' ? 'checked' : '' }}>
                    <div class="method-check"><span class="material-symbols-outlined">check</span></div>
                    <span class="method-icon">📱</span>
                    <div class="method-name">Pago Móvil</div>
                    <div class="method-desc">Venezuela (Bs.)</div>
                </label>
                @endif

                @if(!empty($enabledMethods['usdt_binance']))
                <label class="method-card {{ $defaultMethod === 'usdt_binance' ? 'selected' : '' }}" data-method="usdt_binance">
                    <input type="radio" name="ui_method" value="usdt_binance" {{ $defaultMethod === 'usdt_binance' ? 'checked' : '' }}>
                    <div class="method-check"><span class="material-symbols-outlined">check</span></div>
                    <img src="{{ asset('img/usdt.svg') }}" alt="" class="method-icon-img" width="32" height="32">
                    <div class="method-name">USDT</div>
                    <div class="method-desc">Binance TRC20</div>
                </label>
                @endif
            </div>
        </div>

        {{-- Panel PayPal --}}
        <div id="panel-paypal" class="{{ $defaultMethod === 'paypal' ? 'show' : '' }}">
            <div class="payment-info-box show" style="background:#f0f7ff;border-color:#bfdbfe;">
                <div class="pib-title" style="color:#0070ba;">Pago seguro con PayPal</div>
                <p style="font-size:.85rem;color:#445;margin:0 0 1rem;">Serás redirigido a PayPal para completar el pago. Al confirmar, tu Plan Pro se activa automáticamente.</p>
                @if($paypalEnabled)
                    <button type="button" id="btn-paypal-pay" class="btn-paypal">
                        Pagar ${{ number_format($config['amount'], 2) }} con PayPal
                    </button>
                @else
                    <div class="alert alert-warning mb-0" style="font-size:.85rem;border-radius:.75rem;">
                        PayPal aún no está configurado en el servidor. Usa Zelle, Pago Móvil o USDT, o pide al administrador que agregue <code>PAYPAL_CLIENT_ID</code> y <code>PAYPAL_CLIENT_SECRET</code> en el <code>.env</code>.
                    </div>
                @endif
            </div>
        </div>

        {{-- Panel transferencia --}}
        <div id="panel-manual" class="{{ $defaultMethod !== 'paypal' ? 'show' : '' }}">
            <form method="post" action="{{ route('tenant.payments.store') }}" enctype="multipart/form-data" id="form-payment">
                @csrf
                <input type="hidden" name="plan_sku" value="{{ $planSku }}">
                <input type="hidden" name="method" id="manual-method" value="{{ $defaultMethod === 'paypal' ? (array_key_first($enabledMethods) ?: 'zelle') : $defaultMethod }}">

                <div class="payment-info-box {{ $defaultMethod === 'zelle' ? 'show' : '' }}" id="info-zelle">
                    <div class="pib-title" style="color:#1d4ed8;">Datos Zelle</div>
                    @if(!empty($pm['zelle']['email']))
                    <div class="pib-row">
                        <span class="pib-label">Email</span>
                        <span class="pib-val">{{ $pm['zelle']['email'] }}
                            <button type="button" class="copy-btn" onclick="copyText('{{ $pm['zelle']['email'] }}',this)"><span class="material-symbols-outlined">content_copy</span></button>
                        </span>
                    </div>
                    @endif
                    @if(!empty($pm['zelle']['name']))
                    <div class="pib-row"><span class="pib-label">Nombre</span><span class="pib-val">{{ $pm['zelle']['name'] }}</span></div>
                    @endif
                    @if(!empty($pm['zelle']['notes']))
                    <div class="pib-row" style="border:none;"><span class="pib-label" style="font-size:.78rem;color:#1d4ed8;">{{ $pm['zelle']['notes'] }}</span></div>
                    @endif
                </div>

                <div class="payment-info-box {{ $defaultMethod === 'pagomovil' ? 'show' : '' }}" id="info-pagomovil">
                    <div class="pib-title" style="color:#7c3aed;">Datos Pago Móvil</div>
                    @if(!empty($pm['pagomovil']['banco']))
                    <div class="pib-row"><span class="pib-label">Banco</span><span class="pib-val">{{ $pm['pagomovil']['banco'] }}</span></div>
                    @endif
                    @if(!empty($pm['pagomovil']['telefono']))
                    <div class="pib-row">
                        <span class="pib-label">Teléfono</span>
                        <span class="pib-val">{{ $pm['pagomovil']['telefono'] }}
                            <button type="button" class="copy-btn" onclick="copyText('{{ $pm['pagomovil']['telefono'] }}',this)"><span class="material-symbols-outlined">content_copy</span></button>
                        </span>
                    </div>
                    @endif
                    @if(!empty($pm['pagomovil']['cedula']))
                    <div class="pib-row">
                        <span class="pib-label">Cédula / RIF</span>
                        <span class="pib-val">{{ $pm['pagomovil']['cedula'] }}
                            <button type="button" class="copy-btn" onclick="copyText('{{ $pm['pagomovil']['cedula'] }}',this)"><span class="material-symbols-outlined">content_copy</span></button>
                        </span>
                    </div>
                    @endif
                    @if($rate > 0)
                    <div class="pib-row">
                        <span class="pib-label">Monto en Bs.</span>
                        <span class="pib-val">Bs. {{ number_format($config['amount'] * $rate, 2) }}</span>
                    </div>
                    @endif
                    @if(!empty($pm['pagomovil']['notes']))
                    <div class="pib-row" style="border:none;"><span class="pib-label" style="font-size:.78rem;color:#7c3aed;">{{ $pm['pagomovil']['notes'] }}</span></div>
                    @endif
                </div>

                <div class="payment-info-box {{ $defaultMethod === 'usdt_binance' ? 'show' : '' }}" id="info-usdt">
                    <div class="pib-title" style="color:#b45309;">Datos USDT (TRC20)</div>
                    @if(!empty($pm['usdt_binance']['red']))
                    <div class="pib-row"><span class="pib-label">Red</span><span class="pib-val">{{ $pm['usdt_binance']['red'] }}</span></div>
                    @endif
                    @if(!empty($pm['usdt_binance']['wallet']))
                    <div class="pib-row">
                        <span class="pib-label">Wallet</span>
                        <span class="pib-val" style="font-size:.75rem;">{{ $pm['usdt_binance']['wallet'] }}
                            <button type="button" class="copy-btn" onclick="copyText('{{ $pm['usdt_binance']['wallet'] }}',this)"><span class="material-symbols-outlined">content_copy</span></button>
                        </span>
                    </div>
                    @endif
                    @if(!empty($pm['usdt_binance']['notes']))
                    <div class="pib-row" style="border:none;"><span class="pib-label" style="font-size:.78rem;color:#b45309;">{{ $pm['usdt_binance']['notes'] }}</span></div>
                    @endif
                </div>

                <div class="pay-section" style="margin-top:1.25rem;">
                    <div class="pay-section-title">
                        <span class="material-symbols-outlined">receipt_long</span>
                        Confirma tu pago
                    </div>
                    <div class="mb-3">
                        <label class="form-label-pay">Número de referencia <span class="required-star">*</span></label>
                        <input type="text" name="reference" class="form-control" required
                            value="{{ old('reference') }}"
                            placeholder="Ej: últimos 6 dígitos o hash de la transferencia"
                            style="border-radius:.75rem;padding:.7rem .9rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-pay">Notas (opcional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Nombre del titular, banco de origen, etc."
                            style="border-radius:.75rem;padding:.7rem .9rem;">{{ old('notes') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-pay">Comprobante / captura (opcional)</label>
                        <input type="file" name="proof" class="form-control" accept="image/*"
                            style="border-radius:.75rem;padding:.55rem .9rem;">
                        <small class="text-muted">JPG o PNG, máx. 5 MB</small>
                    </div>
                    <button type="submit" class="btn-submit-pay">
                        <span class="material-symbols-outlined">send</span>
                        Enviar comprobante
                    </button>
                    <p style="font-size:.78rem;color:#889;text-align:center;margin:.75rem 0 0;">
                        Revisamos tu pago y activamos Pro normalmente en menos de 24 horas.
                    </p>
                </div>
            </form>
        </div>

        <div class="text-center mt-3" style="font-size:.82rem;color:#889;">
            <a href="{{ route('plans') }}">← Ver todos los planes</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function() {
    const cards = document.querySelectorAll('.method-card');
    const panelPaypal = document.getElementById('panel-paypal');
    const panelManual = document.getElementById('panel-manual');
    const manualMethod = document.getElementById('manual-method');
    const infos = {
        zelle: document.getElementById('info-zelle'),
        pagomovil: document.getElementById('info-pagomovil'),
        usdt_binance: document.getElementById('info-usdt'),
    };

    function selectMethod(method) {
        cards.forEach(c => {
            const on = c.dataset.method === method;
            c.classList.toggle('selected', on);
            const input = c.querySelector('input');
            if (input) input.checked = on;
        });

        if (method === 'paypal') {
            panelPaypal.classList.add('show');
            panelManual.classList.remove('show');
            return;
        }

        panelPaypal.classList.remove('show');
        panelManual.classList.add('show');
        if (manualMethod) manualMethod.value = method;
        Object.keys(infos).forEach(key => {
            if (!infos[key]) return;
            infos[key].classList.toggle('show', key === method);
        });
    }

    cards.forEach(card => {
        card.addEventListener('click', () => selectMethod(card.dataset.method));
    });

    window.copyText = function(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const icon = btn.querySelector('.material-symbols-outlined');
            if (!icon) return;
            icon.textContent = 'check_circle';
            setTimeout(() => { icon.textContent = 'content_copy'; }, 1500);
        });
    };

    document.getElementById('btn-paypal-pay')?.addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        const original = btn.textContent;
        btn.textContent = 'Redirigiendo a PayPal...';
        try {
            const res = await fetch('{{ route('billing.paypal.subscribe') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ plan: '{{ $planSku }}' }),
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.error || 'Error al iniciar pago');
            if (!data.approval_url) throw new Error('No se recibió URL de PayPal');
            window.location.href = data.approval_url;
        } catch (e) {
            alert(e.message);
            btn.disabled = false;
            btn.textContent = original;
        }
    });
})();
</script>
@endsection

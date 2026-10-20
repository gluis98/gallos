@extends('layouts.app')

@section('title', 'Activar Plan Pro')

@section('styles')
<style>
.billing-wrap { max-width: 900px; margin: 0 auto; }
.billing-hero {
    background: linear-gradient(135deg, #1a2648, #2d4278);
    border-radius: 1.2rem; padding: 1.75rem 2rem; color: #fff; margin-bottom: 1.5rem;
}
.billing-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
@media(max-width:768px){ .billing-grid{ grid-template-columns:1fr; } }
.billing-card {
    background: #fff; border: 1px solid #e8eef8; border-radius: 1rem;
    padding: 1.5rem; box-shadow: 0 8px 24px rgba(16,39,77,.06);
}
.billing-card h3 { font-size: 1rem; font-weight: 800; margin-bottom: .75rem; }
.plan-pill {
    display: inline-flex; align-items: center; gap: .35rem;
    background: #eff6ff; color: #1d4ed8; border-radius: 999px;
    padding: .25rem .75rem; font-size: .78rem; font-weight: 700;
}
.price-big { font-size: 2rem; font-weight: 900; color: #1a2648; }
.btn-paypal {
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    width: 100%; padding: .85rem; border: none; border-radius: .75rem;
    background: #0070ba; color: #fff; font-weight: 700; cursor: pointer;
    text-decoration: none; font-family: inherit;
}
.btn-paypal:hover { background: #005ea6; color: #fff; }
.btn-trial {
    display: block; width: 100%; text-align: center; padding: .75rem;
    border: 1.5px dashed #93c5fd; border-radius: .75rem; background: #f8fbff;
    color: #1d4ed8; font-weight: 700; text-decoration: none;
}
.manual-link { font-size: .85rem; color: #60708d; text-align: center; margin-top: 1rem; }
</style>
@endsection

@section('content')
<div class="billing-wrap">
    <div class="billing-hero">
        <div class="plan-pill mb-2">
            <span class="material-symbols-outlined" style="font-size:1rem;">workspace_premium</span>
            {{ $config['name'] }}
        </div>
        <h1 style="font-size:1.6rem;font-weight:900;margin:0 0 .35rem;">Activa tu Plan Pro</h1>
        <p style="margin:0;opacity:.8;font-size:.9rem;">Pago seguro con PayPal o métodos locales (Zelle, Pago Móvil, USDT).</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    @if($summary['is_pro'] && !($summary['is_trial'] ?? false))
        <div class="alert alert-success">
            Ya tienes Plan Pro activo.
            @if($summary['ends_at'])
                Vence: {{ \Carbon\Carbon::parse($summary['ends_at'])->format('d/m/Y') }}
            @endif
        </div>
    @endif

    <div class="billing-grid">
        <div class="billing-card">
            <h3>Resumen del plan</h3>
            <div class="price-big">${{ number_format($config['amount'], 2) }} <small style="font-size:.9rem;font-weight:500;color:#60708d;">{{ $config['currency'] }}</small></div>
            @if($rate > 0)
                <div style="font-size:.85rem;color:#60708d;margin-bottom:1rem;">≈ Bs. {{ number_format($config['amount'] * $rate, 2) }}</div>
            @endif
            <ul style="font-size:.86rem;color:#445;line-height:1.8;padding-left:1.1rem;margin:0;">
                <li>Gallos y gallinas ilimitados</li>
                <li>Pedigree completo + PDF</li>
                <li>Publicar en Marketplace</li>
                <li>Reportes avanzados</li>
                <li>Módulo de compras</li>
            </ul>
        </div>

        <div class="billing-card">
            <h3>Pagar con PayPal</h3>
            @if($paypalEnabled)
                <button type="button" id="btn-paypal-pay" class="btn-paypal">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#fff"><path d="M7.076 18.337H2.47a.641.641 0 0 1-.633-.74L4.944 5.901a.77.77 0 0 1 .758-.658h6.35c2.114 0 3.56.432 4.298 1.284.738.853.87 2.147.393 3.848-.49 1.73-1.38 2.98-2.646 3.72-1.24.72-2.88 1.08-4.89 1.08H8.12l-.47 2.89a.641.641 0 0 1-.633.553h-.94z"/></svg>
                    Pagar con PayPal
                </button>
                <p style="font-size:.75rem;color:#889;text-align:center;margin:.75rem 0 0;">Activación automática al confirmar el pago.</p>
            @else
                <p style="font-size:.85rem;color:#889;">PayPal no está configurado. Usa un método de pago local.</p>
            @endif

            @if(!($summary['is_pro'] ?? false) && !($summary['is_trial'] ?? false))
                <form method="POST" action="{{ route('billing.trial') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="btn-trial" style="border:none;width:100%;cursor:pointer;">
                        Probar Pro gratis — {{ config('paypal.trial_days', 14) }} días
                    </button>
                </form>
            @endif

            <div class="manual-link">
                ¿Prefieres Zelle, Pago Móvil o USDT?
                <a href="{{ route('tenant.payments.create', ['plan' => $planSku]) }}">Subir comprobante manual</a>
            </div>
        </div>
    </div>

    <div class="text-center mt-3" style="font-size:.82rem;color:#889;">
        <a href="{{ route('plans') }}">← Ver todos los planes</a>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('btn-paypal-pay')?.addEventListener('click', async function() {
    const btn = this;
    btn.disabled = true;
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
        if (data.approval_url) {
            window.location.href = data.approval_url;
        } else {
            throw new Error('No se recibió URL de PayPal');
        }
    } catch (e) {
        alert(e.message);
        btn.disabled = false;
        btn.innerHTML = 'Pagar con PayPal';
    }
});
</script>
@endsection

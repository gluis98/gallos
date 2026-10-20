@props(['feature' => 'general', 'compact' => false])

@php
    $messages = [
        'gallos' => 'Has alcanzado el límite de gallos del plan gratuito.',
        'gallinas' => 'Has alcanzado el límite de gallinas del plan gratuito.',
        'pedigree' => 'El pedigree completo y árbol genealógico son funciones Pro.',
        'reports' => 'Los reportes avanzados en PDF requieren Plan Pro.',
        'marketplace' => 'Publicar en el Marketplace requiere Plan Pro.',
        'compras' => 'El módulo de compras requiere Plan Pro.',
        'vacunaciones' => 'El módulo de vacunación requiere Plan Pro.',
        'inventario' => 'El módulo de inventario requiere Plan Pro.',
        'ventas' => 'El módulo de ventas requiere Plan Pro.',
        'general' => 'Desbloquea todas las funciones con Plan Pro.',
    ];
    $msg = $messages[$feature] ?? $messages['general'];
@endphp

<div class="upgrade-cta {{ $compact ? 'upgrade-cta--compact' : '' }}" style="
    background: linear-gradient(135deg, #eff6ff, #f5f3ff);
    border: 1.5px solid #bfdbfe;
    border-radius: 1rem;
    padding: {{ $compact ? '.85rem 1rem' : '1.25rem 1.5rem' }};
    margin-bottom: 1rem;
    display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
">
    <div style="flex:1;min-width:200px;">
        <div style="font-weight:800;font-size:{{ $compact ? '.88rem' : '1rem' }};color:#1a2648;margin-bottom:.2rem;">
            <span class="material-symbols-outlined" style="font-size:1.1rem;vertical-align:-3px;color:#3b82f6;">workspace_premium</span>
            Actualiza a Pro
        </div>
        <div style="font-size:.84rem;color:#60708d;">{{ $msg }}</div>
    </div>
    <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
        <a href="{{ route('billing.checkout', ['plan' => 'pro_monthly']) }}" class="btn btn-primary btn-sm" style="font-weight:700;">
            Activar Pro
        </a>
        <a href="{{ route('plans') }}" class="btn btn-outline-secondary btn-sm">Ver planes</a>
    </div>
</div>

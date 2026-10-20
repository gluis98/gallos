@props([
    'module' => '',
    'route' => '',
    'routeIs' => null,
    'label' => '',
    'icon' => 'extension',
    'variant' => 'more', // more | bar
])

@php
    $modules = $planModules ?? (\App\Services\SettingsService::get()['modules'] ?? []);
    $requiresPro = !empty($modules[$module]['pro'] ?? false);

    $isPro = $isProUser ?? null;
    if ($isPro === null) {
        $isPro = true;
        if (auth()->check() && ! auth()->user()->is_superadmin) {
            try {
                $isPro = app(\App\Services\SubscriptionService::class)->isPro();
            } catch (\Throwable) {
                $isPro = false;
            }
        }
    }

    $locked = $requiresPro && ! $isPro;
    $href = $locked
        ? route('billing.checkout', ['plan' => 'pro_monthly', 'from' => $module])
        : route($route);
    $activePattern = $routeIs ?? $route;
    $active = request()->routeIs($activePattern);
@endphp

@if($variant === 'bar')
    <a href="{{ $href }}"
       {{ $attributes->class(['mob-nav-item', 'active' => $active]) }}
       style="position:relative;"
       @if($locked) title="Activa Plan Pro para usar este módulo" @endif>
        <span class="material-symbols-outlined mni-icon">{{ $icon }}</span>
        @if($requiresPro)
            <span class="nav-pro-crown" title="Plan Pro">
                <span class="material-symbols-outlined">crown</span>
            </span>
        @endif
        <span class="mni-label">{{ $label }}</span>
    </a>
@else
    <a href="{{ $href }}"
       {{ $attributes->class(['mob-more-btn', 'active' => $active]) }}
       @if($locked) title="Activa Plan Pro para usar este módulo" @endif>
        <span class="material-symbols-outlined">{{ $icon }}</span>
        @if($requiresPro)
            <span class="nav-pro-crown" title="Plan Pro">
                <span class="material-symbols-outlined">crown</span>
            </span>
        @endif
        <span class="lbl">{{ $label }}</span>
    </a>
@endif

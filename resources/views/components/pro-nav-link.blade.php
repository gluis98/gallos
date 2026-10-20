@props([
    'module' => '',
    'route' => '',
    'routeIs' => null,
    'label' => '',
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

<a href="{{ $href }}"
   {{ $attributes->class(['nav-link', 'nav-pro-item', 'active' => $active, 'nav-pro-locked' => $locked]) }}
   @if($locked) title="Activa Plan Pro para usar este módulo" @endif>
    @if($requiresPro)
        <span class="nav-pro-crown" title="Plan Pro">
            <span class="material-symbols-outlined">crown</span>
        </span>
    @endif
    <span>{{ $label }}</span>
</a>

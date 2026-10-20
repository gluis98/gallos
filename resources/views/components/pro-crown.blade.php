@props(['module' => '', 'size' => '.85rem', 'float' => false])

@php
    $modules = $planModules ?? (\App\Services\SettingsService::get()['modules'] ?? []);
    $requiresPro = !empty($modules[$module]['pro'] ?? false);
@endphp

@if($requiresPro)
    <span {{ $attributes->merge([
        'class' => $float ? 'nav-pro-crown' : 'pro-crown',
        'title' => 'Módulo Plan Pro',
        'aria-label' => 'Requiere Plan Pro',
    ]) }}>
        <span class="material-symbols-outlined" style="font-size:{{ $size }};font-variation-settings:'FILL' 1,'wght' 600;">crown</span>
    </span>
@endif

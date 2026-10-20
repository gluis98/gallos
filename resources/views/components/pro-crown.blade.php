@props(['module' => '', 'size' => '1rem'])

@php
    $modules = $planModules ?? [];
    $requiresPro = !empty($modules[$module]['pro']);
@endphp

@if($requiresPro)
    <span {{ $attributes->merge(['class' => 'pro-crown', 'title' => 'Requiere Plan Pro']) }}
          style="display:inline-flex;align-items:center;color:#f59e0b;line-height:1;">
        <span class="material-symbols-outlined" style="font-size:{{ $size }};font-variation-settings:'FILL' 1;">crown</span>
    </span>
@endif

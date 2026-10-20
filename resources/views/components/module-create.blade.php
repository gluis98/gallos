@props(['module'])

@php
    $requiresPro = !empty(($planModules ?? [])[$module]['pro'] ?? false);
    $canCreate = ($isProUser ?? false) || ! $requiresPro;
@endphp

@if($canCreate)
    {{ $slot }}
@endif

@extends('layouts.app')

@section('title', '¡Plan Pro activado!')

@section('content')
<div style="max-width:560px;margin:3rem auto;text-align:center;">
    <div style="font-size:4rem;margin-bottom:1rem;">🎉</div>
    <h1 style="font-weight:900;font-size:1.8rem;margin-bottom:.5rem;">¡Plan Pro activado!</h1>
    <p style="color:#60708d;margin-bottom:2rem;">
        Tu suscripción está activa. Ya puedes registrar aves ilimitadas, usar el pedigree completo y publicar en el Marketplace.
    </p>
    @if($summary['ends_at'] ?? null)
        <p style="font-size:.9rem;color:#445;">Válido hasta: <strong>{{ \Carbon\Carbon::parse($summary['ends_at'])->format('d/m/Y') }}</strong></p>
    @endif
    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg mt-3">Ir a mi panel</a>
</div>
@endsection

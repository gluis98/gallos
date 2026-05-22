@extends('layouts.auth')

@section('auth_title', 'Crear cuenta de prueba')
@section('auth_subtitle', 'Activa tu acceso free y empieza en minutos')

@section('content')
<style>
    .auth-field-label {
        display: block;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.45rem;
        color: rgba(233, 238, 255, 0.75);
    }

    .auth-field {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.14);
        color: #f4f7ff;
        border-radius: 0.8rem;
        height: 48px;
        font-weight: 500;
        padding-inline: 0.9rem;
        transition: all 0.2s ease;
    }

    .auth-field:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(79, 140, 255, 0.75);
        box-shadow: 0 0 0 0.22rem rgba(79, 140, 255, 0.2);
        color: #fff;
    }

    .auth-field::placeholder {
        color: rgba(231, 237, 255, 0.45);
    }

    .trial-alert {
        border: 1px solid rgba(255, 193, 7, 0.45);
        border-radius: 0.8rem;
        background: rgba(255, 193, 7, 0.12);
        color: #ffe8a3;
        font-size: 0.9rem;
        padding: 0.7rem 0.8rem;
        margin-bottom: 1rem;
    }

    .trial-alert a {
        color: #fff3cd;
        font-weight: 600;
    }

    .auth-register-btn {
        width: 100%;
        height: 48px;
        border: 0;
        border-radius: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        background: linear-gradient(95deg, #4f8cff 0%, #6e5bff 55%, #8b4dff 100%);
        color: #fff;
        box-shadow: 0 10px 28px rgba(79, 140, 255, 0.35);
        transition: transform 0.15s ease, box-shadow 0.2s ease;
    }

    .auth-register-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 14px 30px rgba(110, 91, 255, 0.42);
    }

    .auth-links {
        color: #a9beff;
        text-decoration: none;
        font-size: 0.88rem;
    }

    .auth-links:hover {
        color: #d4dfff;
        text-decoration: underline;
    }

    .auth-premium-note {
        margin-top: 0.75rem;
        font-size: 0.86rem;
        color: rgba(231, 237, 255, 0.78);
        text-align: center;
    }
</style>

@php
    $whatsappUrl = env('WHATSAPP_SUPPORT_URL', 'https://wa.me/584120000000');
@endphp

<div class="trial-alert">
    Modo prueba activo (plan free con limite de registros).
    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer">Subir a premium por WhatsApp</a>
</div>

<form method="POST" action="{{ route('register') }}" novalidate>
    @csrf

    <div class="mb-3">
        <label for="name" class="auth-field-label">{{ __('Nombre') }}</label>
        <input
            id="name"
            type="text"
            class="form-control auth-field @error('name') is-invalid @enderror"
            name="name"
            value="{{ old('name') }}"
            placeholder="Tu nombre completo"
            required
            autocomplete="name"
            autofocus
        >
        @error('name')
            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="auth-field-label">{{ __('Correo electrónico') }}</label>
        <input
            id="email"
            type="email"
            class="form-control auth-field @error('email') is-invalid @enderror"
            name="email"
            value="{{ old('email') }}"
            placeholder="tu-correo@dominio.com"
            required
            autocomplete="email"
        >
        @error('email')
            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="auth-field-label">{{ __('Contraseña') }}</label>
        <input
            id="password"
            type="password"
            class="form-control auth-field @error('password') is-invalid @enderror"
            name="password"
            placeholder="Minimo 8 caracteres"
            required
            autocomplete="new-password"
        >
        @error('password')
            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password-confirm" class="auth-field-label">{{ __('Confirmar contraseña') }}</label>
        <input
            id="password-confirm"
            type="password"
            class="form-control auth-field"
            name="password_confirmation"
            placeholder="Repite tu contraseña"
            required
            autocomplete="new-password"
        >
    </div>

    <button type="submit" class="auth-register-btn">
        {{ __('Crear cuenta de prueba') }}
    </button>

    <p class="auth-premium-note mb-1">
        O adquiere tu cuenta premium por $5 al mes, contactanos al
        <a class="auth-links" href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>.
    </p>
    <p class="text-center mb-0 mt-2">
        <a class="auth-links" href="{{ route('login') }}">Ya tengo cuenta, iniciar sesion</a>
    </p>
</form>
@endsection

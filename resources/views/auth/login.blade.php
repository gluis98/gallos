@extends('layouts.auth')

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

    .auth-check-label {
        font-size: 0.9rem;
        color: rgba(236, 240, 255, 0.86);
    }

    .auth-login-btn {
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

    .auth-login-btn:hover {
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
        height: 46px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 0.8rem;
        background: rgba(255, 255, 255, 0.06);
        color: #f2f5ff;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    .auth-register-btn:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
    }

    .auth-premium-note {
        margin-top: 0.75rem;
        font-size: 0.86rem;
        color: rgba(231, 237, 255, 0.78);
        text-align: center;
    }
</style>

<form method="POST" action="{{ route('login') }}" novalidate>
    @csrf
    @php
        $whatsappUrl = env('WHATSAPP_SUPPORT_URL', 'https://wa.me/584120000000');
        $loginSettings = \App\Services\SettingsService::get();
        $proMonthly    = $loginSettings['plans']['pro_monthly_price'] ?? 15;
        $proCurrency   = $loginSettings['plans']['pro_currency']      ?? 'USD';
    @endphp

    <div class="trial-alert">
        Estas en modo de prueba, adquiere tu cuenta premium.
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer">Ir a WhatsApp</a>
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
            autofocus
        >
        @error('email')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="auth-field-label">{{ __('Contraseña') }}</label>
        <input
            id="password"
            type="password"
            class="form-control auth-field @error('password') is-invalid @enderror"
            name="password"
            placeholder="••••••••"
            required
            autocomplete="current-password"
        >
        @error('password')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label auth-check-label" for="remember">
                {{ __('Recordarme') }}
            </label>
        </div>
        @if (Route::has('password.request'))
            <a class="auth-links" href="{{ route('password.request') }}">
                {{ __('¿Olvidaste tu contraseña?') }}
            </a>
        @endif
    </div>

    <button type="submit" class="auth-login-btn">
        {{ __('Entrar al sistema') }}
    </button>

    <div class="mt-3">
        <a href="{{ route('register') }}" class="auth-register-btn">
            Registrate y prueba el software
        </a>
        <p class="auth-premium-note mb-0">
            O adquiere tu cuenta premium por ${{ $proMonthly }} al mes, contáctanos al
            <a class="auth-links" href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>.
        </p>
    </div>
</form>
@endsection

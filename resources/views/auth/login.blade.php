@extends('layouts.auth')

@section('content')

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="row mb-3">
                
                <div class="col-md-12">
                    <label for="email" class="text-md-end">{{ __('Correo electrónico') }}</label>
                    <input id="email" type="email" class="form-control bg-transparent border-dark text-white @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="password" class="text-md-end">{{ __('Contraseña') }}</label>
                    <input id="password" type="password" class="form-control bg-transparent border-dark text-white @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-danger">
                        {{ __('Iniciar sesión') }}
                    </button>

                </div>
            </div>
        </form>
    
@endsection

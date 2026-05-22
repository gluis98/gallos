<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galpon — @yield('auth_title', 'Acceso al sistema')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">
    <meta name="theme-color" content="#06080f">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --auth-bg: #06080f;
            --auth-surface: rgba(14, 18, 31, 0.75);
            --auth-surface-border: rgba(255, 255, 255, 0.14);
            --auth-text-soft: rgba(233, 238, 255, 0.78);
            --auth-accent: #4f8cff;
            --auth-accent-2: #7a4dff;
        }

        body {
            background: radial-gradient(circle at 5% 10%, #213a84 0%, transparent 40%),
                radial-gradient(circle at 95% 85%, #5d2d9c 0%, transparent 42%),
                linear-gradient(155deg, #05070d 0%, #0a1020 55%, #070b15 100%);
            min-height: 100vh;
            color: #f8f9ff;
        }

        .auth-shell {
            min-height: 100vh;
            padding: 1.25rem;
        }

        .auth-grid {
            max-width: 1120px;
            margin: 0 auto;
            min-height: calc(100vh - 2.5rem);
        }

        .auth-hero {
            position: relative;
            overflow: hidden;
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: linear-gradient(165deg, rgba(26, 38, 72, 0.8), rgba(22, 28, 52, 0.66));
            padding: clamp(1.5rem, 3vw, 2.5rem);
            height: 100%;
        }

        .auth-hero-badge {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #d2ddff;
            background: rgba(79, 140, 255, 0.15);
            border: 1px solid rgba(79, 140, 255, 0.55);
            border-radius: 999px;
            padding: 0.35rem 0.75rem;
            display: inline-flex;
        }

        .auth-hero h1 {
            font-size: clamp(1.7rem, 3.6vw, 2.8rem);
            font-weight: 700;
            line-height: 1.15;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        .auth-hero p {
            color: var(--auth-text-soft);
            max-width: 35ch;
            margin-bottom: 0;
        }

        .auth-highlight {
            color: #a9beff;
        }

        .auth-list {
            margin-top: 1.25rem;
            margin-bottom: 0;
            padding-left: 0;
            list-style: none;
        }

        .auth-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            color: var(--auth-text-soft);
            margin-bottom: 0.7rem;
            font-size: 0.95rem;
        }

        .auth-list-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            margin-top: 0.42rem;
            background: linear-gradient(150deg, var(--auth-accent), var(--auth-accent-2));
            box-shadow: 0 0 0 0.2rem rgba(79, 140, 255, 0.2);
            flex-shrink: 0;
        }

        .auth-card {
            border-radius: 1.25rem;
            border: 1px solid var(--auth-surface-border);
            background: var(--auth-surface);
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 65px rgba(2, 4, 12, 0.5);
            overflow: hidden;
        }

        .auth-card-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1.15rem 1.35rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .auth-logo {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.22);
        }

        .auth-card-body {
            padding: 1.35rem;
        }

        .auth-subtitle {
            color: var(--auth-text-soft);
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        @media (max-width: 991.98px) {
            .auth-grid {
                min-height: auto;
            }

            .auth-hero {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-shell">
        <div class="row auth-grid align-items-stretch g-3 g-lg-4">
            <div class="col-lg-6">
                <section class="auth-hero d-flex flex-column justify-content-between h-100">
                    <div>
                        <span class="auth-hero-badge">Galpon — Plataforma avícola</span>
                        <h1>Gestiona tu criadero con un flujo <span class="auth-highlight">rápido, elegante y potente</span>.</h1>
                        <p>Controla inventario, pedigree, ventas y suscripciones en una experiencia moderna adaptada a cualquier pantalla.</p>
                        <ul class="auth-list">
                            <li>
                                <span class="auth-list-dot"></span>
                                <span>Registra gallos, gallinas y clientes con validaciones automaticas para mantener tu informacion limpia.</span>
                            </li>
                            <li>
                                <span class="auth-list-dot"></span>
                                <span>Traza genealogia y rendimiento con reportes listos para exportar y compartir con tu equipo o compradores.</span>
                            </li>
                            <li>
                                <span class="auth-list-dot"></span>
                                <span>Gestiona ventas, publicaciones y suscripciones desde un solo panel, con enfoque SaaS multi-tenant.</span>
                            </li>
                        </ul>
                    </div>
                    <div class="pt-4 d-none d-lg-block">
                        <small class="text-light-emphasis">Acceso seguro para administradores y operadores.</small>
                    </div>
                </section>
            </div>
            <div class="col-lg-6 d-flex align-items-center">
                <section class="auth-card w-100">
                    <header class="auth-card-header">
                        <div>
                            <h2 class="h5 mb-1">@yield('auth_title', 'Iniciar sesion')</h2>
                            <p class="auth-subtitle">@yield('auth_subtitle', 'Ingresa tus credenciales para continuar')</p>
                        </div>
                        <img src="{{ asset('img/logo.png') }}" alt="Logo Galpon" class="auth-logo">
                    </header>
                    <div class="auth-card-body">
                        @yield('content')
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
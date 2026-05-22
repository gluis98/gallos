<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Galpon — @yield('title', 'Gestión de Criadero')</title>

        {{-- PWA --}}
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#1a2648">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Galpon">
        <meta name="application-name" content="Galpon">
        <meta name="msapplication-TileColor" content="#1a2648">
        <meta name="msapplication-TileImage" content="/img/logo.png">
        <link rel="apple-touch-icon" href="/img/logo.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/img/logo.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/img/logo.png">
        <link rel="apple-touch-icon" sizes="167x167" href="/img/logo.png">
        <link rel="icon" type="image/png" href="/img/logo.png">
        <link href="{{asset("css/bootstrap.min.css")}}" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Material+Icons"
        rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
        rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/tom-select.css') }}">
        <link rel="stylesheet" href="{{ asset('css/pedigree-tree.css') }}">
        <link rel="stylesheet" href="{{ asset('css/module-loader.css') }}">
        @yield('styles')
        <style>
            :root {
                --app-bg: #f4f7fb;
                --app-surface: #ffffff;
                --app-border: #e5e9f2;
                --app-text: #13203a;
                --app-muted: #60708d;
                --app-primary: #3b82f6;
                --app-primary-2: #6d5efc;
            }

            * { font-family: 'Poppins', sans-serif; }

            body {
                background: radial-gradient(circle at 100% 0, #dcebff 0, transparent 32%),
                    linear-gradient(180deg, #f9fbff 0%, var(--app-bg) 100%);
                color: var(--app-text);
                min-height: 100vh;
            }

            .app-topbar {
                background: linear-gradient(165deg, rgba(26, 38, 72, 0.96), rgba(22, 28, 52, 0.96));
                border-bottom: 1px solid rgba(255, 255, 255, 0.14);
                backdrop-filter: blur(8px);
            }

            .app-logo {
                width: 56px;
                height: 56px;
                border-radius: 14px;
                object-fit: cover;
                border: 1px solid #dde5f4;
            }

            .app-brand {
                font-weight: 700;
                letter-spacing: .01em;
                color: #f5f8ff;
                text-decoration: none;
            }

            .app-menu {
                border-bottom: 1px solid rgba(255, 255, 255, 0.14);
                background: linear-gradient(165deg, rgba(40, 58, 102, 0.96), rgba(34, 43, 78, 0.96));
            }

            .app-menu .nav-link {
                color: #dfe8ff;
                font-weight: 500;
                border-radius: .7rem;
                margin: .35rem .2rem;
                padding: .55rem .85rem;
            }

            .app-menu .nav-link:hover,
            .app-menu .nav-link.active {
                color: #fff;
                background: linear-gradient(95deg, #4f8cff, #7a4dff);
            }

            .app-shell {
                max-width: 1380px;
                margin: 0 auto;
                padding: 1rem;
            }

            .section-card {
                background: var(--app-surface);
                border: 1px solid var(--app-border);
                border-radius: 1rem;
                box-shadow: 0 14px 38px rgba(16, 39, 77, 0.08);
            }

            .section-title {
                font-size: clamp(1.3rem, 2.5vw, 1.7rem);
                font-weight: 700;
                margin-bottom: .35rem;
            }

            .section-subtitle {
                color: var(--app-muted);
                margin-bottom: 0;
            }

            .btn-primary-soft {
                border: 0;
                color: #fff;
                background: linear-gradient(95deg, var(--app-primary), var(--app-primary-2));
                border-radius: .7rem;
            }

            .btn-outline-soft {
                border: 1px solid #d4dded;
                color: #2f4772;
                background: #fff;
                border-radius: .7rem;
            }

            .form-control, .form-select {
                border-radius: .7rem;
                border-color: #d7dfed;
                min-height: 42px;
            }

            .form-control:focus, .form-select:focus {
                border-color: #8eb6ff;
                box-shadow: 0 0 0 .18rem rgba(59,130,246,.16);
            }

            .dropzone-lite {
                border: 2px dashed #b8c8e8;
                border-radius: .85rem;
                padding: 1rem;
                text-align: center;
                background: #f8fbff;
                cursor: pointer;
                transition: all .2s ease;
            }

            .dropzone-lite:hover { border-color: #6ea4ff; background: #f2f8ff; }
            .dropzone-lite.is-dragover { border-color: #3b82f6; background: #eaf3ff; }

            .preview-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
                gap: .6rem;
                margin-top: .75rem;
            }

            .preview-grid img {
                width: 100%;
                height: 90px;
                object-fit: cover;
                border-radius: .6rem;
                border: 1px solid #dce6f7;
            }

            .cursor-pointer { cursor: pointer; }

            .pedigree-tree {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                align-items: center;
            }

            .pedigree-node {
                background: #fff;
                border: 1px solid #d8e3f5;
                border-radius: .75rem;
                box-shadow: 0 8px 20px rgba(27, 60, 114, 0.08);
                padding: .75rem;
                min-width: 180px;
                text-align: center;
            }

            .pedigree-parents {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
                justify-content: center;
            }

            .pedigree-kids {
                margin-top: .35rem;
                font-size: .84rem;
                color: #5f7397;
            }

            .mobile-bottom-nav {
                border-top: 1px solid var(--app-border);
                background: rgba(255, 255, 255, .97);
                backdrop-filter: blur(8px);
            }
        </style>
    </head>
    <body>
        <header>
            {{-- ══ Top bar ══ --}}
            <nav class="navbar app-topbar py-2">
                <div class="container-fluid app-shell d-flex justify-content-between align-items-center">
                    @auth
                    @if(auth()->user()->is_superadmin)
                        {{-- Datos para super-admin --}}
                        @php
                            $pendingSA   = \App\Models\PaymentOrder::where('status','pendiente')->count();
                            $currentRate = \App\Services\DollarRateService::getCachedRate();
                            $rateCache   = \App\Services\DollarRateService::getCached();
                            $recentPagos = \App\Models\PaymentOrder::where('status','pendiente')
                                ->with('user')->latest()->limit(8)->get();
                        @endphp
                        <a class="d-flex align-items-center gap-2 app-brand" href="{{ route('superadmin.dashboard') }}">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo Galpon" class="app-logo">
                            <div>
                                <div style="font-size:.9rem;font-weight:800;color:#fff;line-height:1.1;">Galpon</div>
                                <div style="font-size:.65rem;font-weight:600;color:#fbbf24;letter-spacing:.05em;text-transform:uppercase;">Super Administrador</div>
                            </div>
                        </a>
                        <div class="d-flex align-items-center gap-2">
                            {{-- Tasa del dólar --}}
                            @if($currentRate > 0)
                            <div style="background:rgba(251,191,36,.12);border:1px solid rgba(251,191,36,.3);color:#fde68a;border-radius:.65rem;padding:.28rem .7rem;font-size:.72rem;font-weight:700;display:flex;align-items:center;gap:.3rem;" title="Tasa BCV/Paralelo vigente — se actualiza al iniciar sesión">
                                <span class="material-symbols-outlined" style="font-size:.85rem;">currency_exchange</span>
                                Bs. {{ number_format($currentRate, 2) }}
                                @if($rateCache)
                                <span style="opacity:.6;font-weight:400;font-size:.65rem;">/ {{ \Carbon\Carbon::parse($rateCache['fecha'])->format('d/m') }}</span>
                                @endif
                            </div>
                            @endif
                            {{-- Campana de notificaciones --}}
                            <div class="dropdown">
                                <button class="btn btn-sm p-0 position-relative" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);border-radius:.65rem;padding:.3rem .55rem !important;color:#fff;width:36px;height:36px;display:flex;align-items:center;justify-content:center;" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="material-symbols-outlined" style="font-size:1.1rem;">notifications</span>
                                    @if($pendingSA > 0)
                                    <span style="position:absolute;top:-4px;right:-4px;background:#ef4444;color:#fff;font-size:.55rem;font-weight:900;border-radius:50%;width:16px;height:16px;display:flex;align-items:center;justify-content:center;border:2px solid #0f1937;">{{ $pendingSA > 9 ? '9+' : $pendingSA }}</span>
                                    @endif
                                </button>
                                <div class="dropdown-menu dropdown-menu-end p-0" style="width:320px;border-radius:.9rem;border:1px solid #e5e9f2;box-shadow:0 16px 48px rgba(16,39,77,.16);overflow:hidden;">
                                    <div style="background:linear-gradient(135deg,#1a2648,#2d4278);padding:.85rem 1rem;display:flex;justify-content:space-between;align-items:center;">
                                        <span style="color:#fff;font-weight:700;font-size:.88rem;">Notificaciones</span>
                                        @if($pendingSA > 0)
                                        <span style="background:#ef4444;color:#fff;font-size:.65rem;font-weight:800;border-radius:20px;padding:.1rem .5rem;">{{ $pendingSA }} pendiente{{ $pendingSA > 1 ? 's' : '' }}</span>
                                        @endif
                                    </div>
                                    @forelse($recentPagos as $pago)
                                    <a href="{{ route('superadmin.payments.index') }}" style="display:flex;gap:.75rem;padding:.75rem 1rem;border-bottom:1px solid #f1f5fb;text-decoration:none;transition:background .15s;" onmouseenter="this.style.background='#f8faff'" onmouseleave="this.style.background=''">
                                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#dbeafe,#eff6ff);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <span class="material-symbols-outlined" style="font-size:1rem;color:#3b82f6;">payments</span>
                                        </div>
                                        <div style="overflow:hidden;">
                                            <div style="font-size:.8rem;font-weight:700;color:#1a2648;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                {{ $pago->user->name ?? 'Usuario' }}
                                            </div>
                                            <div style="font-size:.73rem;color:#60708d;">Comprobante de pago enviado</div>
                                            <div style="font-size:.68rem;color:#94a3b8;margin-top:.1rem;">{{ $pago->created_at->diffForHumans() }}</div>
                                        </div>
                                    </a>
                                    @empty
                                    <div style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:.82rem;">
                                        <span class="material-symbols-outlined" style="display:block;font-size:2rem;margin-bottom:.4rem;">check_circle</span>
                                        Sin notificaciones pendientes
                                    </div>
                                    @endforelse
                                    @if($pendingSA > 0)
                                    <a href="{{ route('superadmin.payments.index') }}" style="display:block;text-align:center;padding:.65rem;font-size:.78rem;font-weight:700;color:#3b82f6;text-decoration:none;background:#f8faff;">
                                        Ver todos los pagos →
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <span class="small d-none d-md-inline" style="color:rgba(255,255,255,.55);font-size:.75rem;">{{ auth()->user()->name }}</span>
                        </div>
                    @else
                        <a class="d-flex align-items-center gap-2 app-brand" href="{{ route('home') }}">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo Galpon" class="app-logo">
                            <span>Galpon</span>
                        </a>
                        @php $userRate = \App\Services\DollarRateService::getCachedRate(); @endphp
                        <div class="d-flex align-items-center gap-2">
                            @include('partials.galpon-switcher')
                            @if($userRate > 0)
                            <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);color:#e0e7ff;border-radius:.6rem;padding:.25rem .65rem;font-size:.72rem;font-weight:600;display:flex;align-items:center;gap:.25rem;" title="Tasa BCV vigente">
                                <span class="material-symbols-outlined" style="font-size:.8rem;">currency_exchange</span>
                                Bs. {{ number_format($userRate, 2) }}
                            </div>
                            @endif
                            {{-- Campana notificaciones usuario --}}
                            <div class="dropdown" id="user-notif-dropdown">
                                <button class="btn btn-sm p-0 position-relative" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);border-radius:.65rem;width:34px;height:34px;display:flex;align-items:center;justify-content:center;color:#fff;" data-bs-toggle="dropdown" aria-expanded="false" id="btn-user-bell">
                                    <span class="material-symbols-outlined" style="font-size:1rem;">notifications</span>
                                    <span id="user-notif-badge" style="display:none;position:absolute;top:-4px;right:-4px;background:#ef4444;color:#fff;font-size:.55rem;font-weight:900;border-radius:50%;width:16px;height:16px;align-items:center;justify-content:center;border:2px solid #1a2648;"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end p-0" style="width:310px;border-radius:.9rem;border:1px solid #e5e9f2;box-shadow:0 16px 48px rgba(16,39,77,.16);overflow:hidden;">
                                    <div style="background:linear-gradient(135deg,#1a2648,#2d4278);padding:.8rem 1rem;display:flex;justify-content:space-between;align-items:center;">
                                        <span style="color:#fff;font-weight:700;font-size:.86rem;">Notificaciones</span>
                                        <button onclick="markAllReadUser()" style="background:rgba(255,255,255,.15);border:none;color:#fff;border-radius:.5rem;padding:.2rem .55rem;font-size:.7rem;cursor:pointer;">Marcar leídas</button>
                                    </div>
                                    <div id="user-notif-list" style="max-height:300px;overflow-y:auto;">
                                        <div style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:.82rem;">
                                            <span class="material-symbols-outlined" style="display:block;font-size:2rem;margin-bottom:.4rem;">notifications_none</span>
                                            Cargando...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @else
                        <a class="d-flex align-items-center gap-2 app-brand" href="{{ url('/') }}">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo Galpon" class="app-logo">
                            <span>Galpon</span>
                        </a>
                    @endauth
                </div>
            </nav>

            {{-- ══ Menú secundario ══ --}}
            @auth
            @if(auth()->user()->is_superadmin)
            {{-- ── Navbar Super Admin ── --}}
            <style>
                .sa-menu .nav-link { color: #dfe8ff !important; font-weight: 500; border-radius: .7rem; margin: .35rem .2rem; padding: .55rem .85rem; display:inline-flex; align-items:center; gap:.35rem; text-decoration:none; }
                .sa-menu .nav-link:hover, .sa-menu .nav-link.active { color: #fff !important; background: linear-gradient(95deg,#4f8cff,#7a4dff); }
                .sa-menu .btn-logout { color: rgba(255,180,180,.8) !important; }
                .sa-menu .btn-logout:hover { color: #fff !important; background: rgba(239,68,68,.2); }
            </style>
            <div class="sa-menu container-fluid d-none d-md-block p-0" style="background:linear-gradient(165deg,rgba(15,25,55,.97),rgba(20,30,60,.97));border-bottom:1px solid rgba(251,191,36,.18);">
                <ul class="nav app-shell py-1" style="gap:.1rem;">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}">
                            <span class="material-symbols-outlined" style="font-size:.95rem;">dashboard</span> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.users*') ? 'active' : '' }}" href="{{ route('superadmin.users.index') }}">
                            <span class="material-symbols-outlined" style="font-size:.95rem;">group</span> Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.subscriptions*') ? 'active' : '' }}" href="{{ route('superadmin.subscriptions.index') }}">
                            <span class="material-symbols-outlined" style="font-size:.95rem;">workspace_premium</span> Suscripciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-inline-flex align-items-center gap-1 {{ request()->routeIs('superadmin.payments*') ? 'active' : '' }}" href="{{ route('superadmin.payments.index') }}">
                            <span class="material-symbols-outlined" style="font-size:.95rem;">payments</span> Pagos
                            @if($pendingSA > 0)
                            <span style="background:#ef4444;color:#fff;font-size:.6rem;font-weight:800;border-radius:20px;padding:.06rem .42rem;line-height:1.4;">{{ $pendingSA }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.tenants*') ? 'active' : '' }}" href="{{ route('superadmin.tenants.index') }}">
                            <span class="material-symbols-outlined" style="font-size:.95rem;">business</span> Cuentas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.audit*') ? 'active' : '' }}" href="{{ route('superadmin.audit.index') }}">
                            <span class="material-symbols-outlined" style="font-size:.95rem;">history</span> Auditoría
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('superadmin.settings*') ? 'active' : '' }}" href="{{ route('superadmin.settings.index') }}">
                            <span class="material-symbols-outlined" style="font-size:.95rem;">settings</span> Configuración
                        </a>
                    </li>
                    <li class="nav-item ms-auto">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="nav-link border-0 bg-transparent btn-logout d-inline-flex align-items-center gap-1">
                                <span class="material-symbols-outlined" style="font-size:.95rem;">logout</span> Salir
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @else
            {{-- ── Navbar Usuario normal ── --}}
            <div class="container-fluid app-menu d-none d-md-block p-0">
                <ul class="nav app-shell py-1">
                    <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('home')}}">Panel</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('gallos') ? 'active' : '' }}" href="{{ route('gallos')}}">Gallos</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('gallinas') ? 'active' : '' }}" href="{{ route('gallinas')}}">Gallinas</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('inventario') ? 'active' : '' }}" href="{{ route('inventario')}}">Inventario</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('compras') ? 'active' : '' }}" href="{{ route('compras')}}">Compras</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('ventas') ? 'active' : '' }}" href="{{ route('ventas')}}">Ventas</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('plans') ? 'active' : '' }}" href="{{ route('plans')}}">Planes</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ request()->routeIs('tenant.payments.create') ? 'active' : '' }}" href="{{ route('tenant.payments.create')}}">Pago Pro</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{route('logout')}}" method="POST">
                            @csrf
                            <button class="nav-link border-0 bg-transparent">
                                <span class="material-symbols-outlined float-end">logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endif
            @endauth
        </header>
        
        <main class="app-shell my-3 my-md-4 pb-5 pb-md-3">
            @yield('content')
        </main>

        @auth @if(!auth()->user()->is_superadmin) <!-- Botón flotante de sugerencias -->
        <button id="btn-sugerencia" title="Enviar sugerencia" onclick="document.getElementById('modal-sugerencia').classList.add('show-modal')" style="
            position: fixed; bottom: 74px; right: 16px; z-index: 1050;
            width: 52px; height: 52px; border-radius: 50%; border: none; cursor: pointer;
            background: linear-gradient(135deg, #f59e0b, #f97316);
            color: #fff; box-shadow: 0 4px 18px rgba(249,115,22,.45);
            display: flex; align-items: center; justify-content: center;
            transition: transform .2s, box-shadow .2s;
        " onmouseenter="this.style.transform='scale(1.1)';this.style.boxShadow='0 8px 28px rgba(249,115,22,.55)'"
           onmouseleave="this.style.transform='scale(1)';this.style.boxShadow='0 4px 18px rgba(249,115,22,.45)'">
            <span class="material-symbols-outlined" style="font-size:1.5rem;">feedback</span>
        </button>

        <!-- Modal sugerencias -->
        <div id="modal-sugerencia" style="display:none;position:fixed;inset:0;z-index:2000;background:rgba(10,20,50,.55);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
            <div style="background:#fff;border-radius:1.2rem;width:min(480px,92vw);box-shadow:0 24px 64px rgba(10,20,50,.22);overflow:hidden;animation:slideUp .25s ease;">
                <div style="background:linear-gradient(135deg,#f59e0b,#f97316);padding:1.25rem 1.5rem;display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <h5 style="margin:0;color:#fff;font-weight:700;font-size:1.1rem;">💡 Enviar sugerencia</h5>
                        <small style="color:rgba(255,255,255,.8);">Tu opinión nos ayuda a mejorar</small>
                    </div>
                    <button onclick="document.getElementById('modal-sugerencia').classList.remove('show-modal')" style="background:rgba(255,255,255,.2);border:none;color:#fff;border-radius:50%;width:32px;height:32px;cursor:pointer;font-size:1.1rem;display:flex;align-items:center;justify-content:center;">✕</button>
                </div>
                <form id="form-sugerencia" style="padding:1.5rem;">
                    @csrf
                    <div style="margin-bottom:1rem;">
                        <label style="font-size:.85rem;font-weight:600;color:#374151;margin-bottom:.4rem;display:block;">Tipo de sugerencia</label>
                        <select name="tipo" style="width:100%;border:1.5px solid #e5e7eb;border-radius:.7rem;padding:.6rem .85rem;font-family:inherit;font-size:.9rem;outline:none;">
                            <option value="mejora">🚀 Mejora de funcionalidad</option>
                            <option value="bug">🐞 Reportar un error</option>
                            <option value="nuevo">✨ Nueva función</option>
                            <option value="otro">💬 Otro</option>
                        </select>
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="font-size:.85rem;font-weight:600;color:#374151;margin-bottom:.4rem;display:block;">Tu sugerencia <span style="color:#ef4444">*</span></label>
                        <textarea name="mensaje" rows="4" required placeholder="Describe tu idea o el problema que encontraste..." style="width:100%;border:1.5px solid #e5e7eb;border-radius:.7rem;padding:.7rem .85rem;font-family:inherit;font-size:.9rem;resize:vertical;outline:none;box-sizing:border-box;"></textarea>
                    </div>
                    <div style="display:flex;gap:.75rem;justify-content:flex-end;">
                        <button type="button" onclick="document.getElementById('modal-sugerencia').classList.remove('show-modal')" style="background:#f3f4f6;border:none;border-radius:.7rem;padding:.6rem 1.2rem;font-family:inherit;font-size:.9rem;cursor:pointer;color:#374151;">Cancelar</button>
                        <button type="submit" style="background:linear-gradient(135deg,#f59e0b,#f97316);border:none;border-radius:.7rem;padding:.6rem 1.4rem;font-family:inherit;font-size:.9rem;font-weight:600;color:#fff;cursor:pointer;">Enviar sugerencia</button>
                    </div>
                </form>
            </div>
        </div>
        <style>
            #modal-sugerencia { display: none !important; }
            #modal-sugerencia.show-modal { display: flex !important; }
            @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        </style>
        @endif @endauth
        <script>
            document.getElementById('form-sugerencia') && document.getElementById('form-sugerencia').addEventListener('submit', function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                fetch('/sugerencias', { method: 'POST', body: fd, headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content} })
                .then(r => r.json()).then(d => {
                    document.getElementById('modal-sugerencia').classList.remove('show-modal');
                    this.reset();
                    if (window.Swal) {
                        Swal.fire({ icon: 'success', title: '¡Gracias!', text: 'Tu sugerencia fue enviada correctamente.', timer: 2500, showConfirmButton: false });
                    } else { alert('¡Gracias por tu sugerencia!'); }
                }).catch(() => alert('Error al enviar. Intenta de nuevo.'));
            });
        </script>

        @auth
        @php
            $isSA      = auth()->user()->is_superadmin;
            $curRoute  = request()->route()?->getName() ?? '';
        @endphp

        {{-- ═══ BARRA DE NAVEGACIÓN INFERIOR MÓVIL ═══ --}}
        <style>
        .mob-nav {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 1040;
            background: rgba(255,255,255,.97);
            backdrop-filter: blur(12px);
            border-top: 1px solid #e5e9f2;
            display: flex; align-items: stretch;
            height: 62px;
            padding: 0 4px;
            box-shadow: 0 -4px 20px rgba(16,39,77,.08);
        }
        @if($isSA)
        .mob-nav { background: rgba(20,30,58,.97); border-top: 1px solid rgba(251,191,36,.2); }
        @endif
        .mob-nav-item {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 2px; text-decoration: none; cursor: pointer;
            border: none; background: transparent;
            padding: 6px 2px; border-radius: .7rem;
            transition: background .15s;
            position: relative; min-width: 0;
        }
        .mob-nav-item:active { background: rgba(59,130,246,.1); }
        .mob-nav-item .mni-icon {
            font-size: 1.3rem;
            @if($isSA) color: rgba(255,255,255,.6); @else color: #60708d; @endif
            line-height: 1; flex-shrink: 0;
        }
        .mob-nav-item .mni-label {
            font-size: .58rem; font-weight: 600; letter-spacing: .01em;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            max-width: 100%;
            @if($isSA) color: rgba(255,255,255,.55); @else color: #60708d; @endif
        }
        .mob-nav-item.active .mni-icon  { @if($isSA) color: #fbbf24; @else color: #3b82f6; @endif }
        .mob-nav-item.active .mni-label { @if($isSA) color: #fbbf24; font-weight:800; @else color: #3b82f6; font-weight:800; @endif }
        .mob-nav-item.active::after {
            content: '';
            position: absolute; bottom: 2px;
            width: 20px; height: 3px; border-radius: 3px;
            @if($isSA) background: #fbbf24; @else background: #3b82f6; @endif
        }
        /* Panel "Más" */
        .mob-more-backdrop {
            display: none; position: fixed; inset: 0; z-index: 1038;
            background: rgba(10,20,50,.4); backdrop-filter: blur(4px);
        }
        .mob-more-backdrop.open { display: block; }
        .mob-more-panel {
            position: fixed; bottom: 62px; left: 0; right: 0; z-index: 1039;
            background: #fff; border-radius: 1.2rem 1.2rem 0 0;
            padding: 1.1rem 1rem .5rem;
            box-shadow: 0 -8px 32px rgba(16,39,77,.15);
            transform: translateY(110%);
            transition: transform .28s cubic-bezier(.4,0,.2,1);
        }
        @if($isSA)
        .mob-more-panel { background: #1a2648; }
        @endif
        .mob-more-panel.open { transform: translateY(0); }
        .mob-more-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: .6rem;
            padding-bottom: .75rem;
        }
        .mob-more-btn {
            display: flex; flex-direction: column; align-items: center;
            gap: .35rem; padding: .75rem .25rem; border-radius: .85rem;
            text-decoration: none; border: none; cursor: pointer;
            background: @if($isSA) rgba(255,255,255,.07) @else #f8faff @endif;
            transition: background .15s;
        }
        .mob-more-btn:active { background: @if($isSA) rgba(255,255,255,.14) @else #e8f0fe @endif; }
        .mob-more-btn .material-symbols-outlined {
            font-size: 1.4rem;
            @if($isSA) color: rgba(255,255,255,.75); @else color: #3b82f6; @endif
        }
        .mob-more-btn span.lbl {
            font-size: .65rem; font-weight: 700;
            @if($isSA) color: rgba(255,255,255,.65); @else color: #374151; @endif
            text-align: center; line-height: 1.2;
        }
        .mob-more-btn.logout-btn .material-symbols-outlined { color: #ef4444; }
        .mob-more-btn.logout-btn span.lbl { color: #ef4444; }
        .mob-more-title {
            font-size: .7rem; font-weight: 800; letter-spacing: .06em; text-transform: uppercase;
            @if($isSA) color: rgba(255,255,255,.35); @else color: #94a3b8; @endif
            margin-bottom: .65rem; padding-bottom: .45rem;
            border-bottom: 1px solid @if($isSA) rgba(255,255,255,.1) @else #f1f5fb @endif;
            display: flex; justify-content: space-between; align-items: center;
        }
        .mob-more-close {
            background: @if($isSA) rgba(255,255,255,.1) @else #f1f5fb @endif;
            border: none; border-radius: 50%; width: 26px; height: 26px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            @if($isSA) color: rgba(255,255,255,.6); @else color: #60708d; @endif
            font-size: .8rem; font-weight: 700;
        }
        /* Agregar padding inferior al main para que el contenido no quede tapado */
        @media (max-width: 767px) {
            main.app-shell { padding-bottom: 80px !important; }
        }
        </style>

        {{-- Backdrop y panel "Más" --}}
        <div class="mob-more-backdrop d-md-none" id="mob-backdrop" onclick="closeMobMore()"></div>
        <div class="mob-more-panel d-md-none" id="mob-more-panel">
            <div class="mob-more-title">
                <span>Más opciones</span>
                <button class="mob-more-close" onclick="closeMobMore()">✕</button>
            </div>
            <div class="mob-more-grid">
            @if($isSA)
                <a href="{{ route('superadmin.subscriptions.index') }}" class="mob-more-btn {{ str_contains($curRoute,'subscriptions') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">workspace_premium</span><span class="lbl">Suscript.</span>
                </a>
                <a href="{{ route('superadmin.tenants.index') }}" class="mob-more-btn {{ str_contains($curRoute,'tenants') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">business</span><span class="lbl">Cuentas</span>
                </a>
                <a href="{{ route('superadmin.audit.index') }}" class="mob-more-btn {{ str_contains($curRoute,'audit') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">history</span><span class="lbl">Auditoría</span>
                </a>
                <button class="mob-more-btn logout-btn" onclick="document.getElementById('form-logout-mob').submit()">
                    <span class="material-symbols-outlined">logout</span><span class="lbl">Salir</span>
                </button>
            @else
                <a href="{{ route('inventario') }}" class="mob-more-btn {{ str_contains($curRoute,'inventario') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">inventory_2</span><span class="lbl">Inventario</span>
                </a>
                <a href="{{ route('compras') }}" class="mob-more-btn {{ str_contains($curRoute,'compras') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">shopping_cart</span><span class="lbl">Compras</span>
                </a>
                <a href="{{ route('plans') }}" class="mob-more-btn {{ str_contains($curRoute,'plans') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">workspace_premium</span><span class="lbl">Planes</span>
                </a>
                <a href="{{ route('tenant.payments.create') }}" class="mob-more-btn {{ str_contains($curRoute,'payments') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">receipt_long</span><span class="lbl">Pago Pro</span>
                </a>
                <button class="mob-more-btn logout-btn" onclick="document.getElementById('form-logout-mob').submit()">
                    <span class="material-symbols-outlined">logout</span><span class="lbl">Salir</span>
                </button>
            @endif
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" id="form-logout-mob" class="d-none">@csrf</form>

        {{-- Barra inferior --}}
        <nav class="mob-nav d-flex d-md-none">
        @if($isSA)
            {{-- SUPER ADMIN: Dashboard, Usuarios, Pagos, Config, Más --}}
            <a href="{{ route('superadmin.dashboard') }}" class="mob-nav-item {{ $curRoute === 'superadmin.dashboard' ? 'active' : '' }}">
                <span class="material-symbols-outlined mni-icon">dashboard</span>
                <span class="mni-label">Panel</span>
            </a>
            <a href="{{ route('superadmin.users.index') }}" class="mob-nav-item {{ str_contains($curRoute,'users') ? 'active' : '' }}">
                <span class="material-symbols-outlined mni-icon">group</span>
                <span class="mni-label">Usuarios</span>
            </a>
            <a href="{{ route('superadmin.payments.index') }}" class="mob-nav-item {{ str_contains($curRoute,'payments') ? 'active' : '' }}" style="position:relative;">
                <span class="material-symbols-outlined mni-icon">payments</span>
                @if(isset($pendingSA) && $pendingSA > 0)
                <span style="position:absolute;top:6px;right:calc(50% - 18px);background:#ef4444;color:#fff;font-size:.5rem;font-weight:900;border-radius:50%;width:13px;height:13px;display:flex;align-items:center;justify-content:center;border:1.5px solid rgba(20,30,58,.97);">{{ $pendingSA > 9 ? '9+' : $pendingSA }}</span>
                @endif
                <span class="mni-label">Pagos</span>
            </a>
            <a href="{{ route('superadmin.settings.index') }}" class="mob-nav-item {{ str_contains($curRoute,'settings') ? 'active' : '' }}">
                <span class="material-symbols-outlined mni-icon">settings</span>
                <span class="mni-label">Config</span>
            </a>
        @else
            {{-- USUARIO: Panel, Gallos, Gallinas, Ventas, Más --}}
            <a href="{{ route('home') }}" class="mob-nav-item {{ $curRoute === 'dashboard' ? 'active' : '' }}">
                <span class="material-symbols-outlined mni-icon">home</span>
                <span class="mni-label">Panel</span>
            </a>
            <a href="{{ route('gallos') }}" class="mob-nav-item {{ $curRoute === 'gallos' ? 'active' : '' }}">
                <span class="material-symbols-outlined mni-icon">agriculture</span>
                <span class="mni-label">Gallos</span>
            </a>
            <a href="{{ route('gallinas') }}" class="mob-nav-item {{ $curRoute === 'gallinas' ? 'active' : '' }}">
                <span class="material-symbols-outlined mni-icon">egg</span>
                <span class="mni-label">Gallinas</span>
            </a>
            <a href="{{ route('ventas') }}" class="mob-nav-item {{ $curRoute === 'ventas' ? 'active' : '' }}">
                <span class="material-symbols-outlined mni-icon">point_of_sale</span>
                <span class="mni-label">Ventas</span>
            </a>
        @endif
            {{-- Botón "Más" --}}
            <button type="button" class="mob-nav-item {{ in_array($curRoute, ['inventario','compras','plans','tenant.payments.create','superadmin.subscriptions.index','superadmin.tenants.index','superadmin.audit.index']) ? 'active' : '' }}" onclick="toggleMobMore()" id="btn-mob-more">
                <span class="material-symbols-outlined mni-icon" id="mob-more-icon">grid_view</span>
                <span class="mni-label">Más</span>
            </button>
        </nav>

        <script>
        function toggleMobMore() {
            const panel    = document.getElementById('mob-more-panel');
            const backdrop = document.getElementById('mob-backdrop');
            const icon     = document.getElementById('mob-more-icon');
            const isOpen   = panel.classList.contains('open');
            if (isOpen) {
                panel.classList.remove('open');
                backdrop.classList.remove('open');
                icon.textContent = 'grid_view';
            } else {
                panel.classList.add('open');
                backdrop.classList.add('open');
                icon.textContent = 'close';
            }
        }
        function closeMobMore() {
            document.getElementById('mob-more-panel').classList.remove('open');
            document.getElementById('mob-backdrop').classList.remove('open');
            document.getElementById('mob-more-icon').textContent = 'grid_view';
        }
        </script>
        @endauth
        
    </body>
    <script src="{{asset("js/jquery-3.7.1.min.js")}}" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{asset("js/bootstrap.bundle.min.js")}}" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/tom-select.complete.min.js')}}"></script>
    <script src="{{asset('js/module-loader.js')}}"></script>
    <script src="{{asset('js/pedigree-tree.js')}}"></script>
    <script src="{{asset('js/sweetalert2@11.js')}}"></script>
    <script>window.ghRow=function(g){if(!g)return null;return Array.isArray(g)?(g[0]||null):g;};</script>
    @yield('scripts')
    {{-- ═══ PWA: Service Worker + Banner de instalación ═══ --}}
    <style>
    #pwa-install-banner {
        position: fixed; bottom: 70px; left: 50%; transform: translateX(-50%) translateY(120%);
        z-index: 1060; width: min(380px, calc(100vw - 2rem));
        background: #fff; border-radius: 1.2rem;
        box-shadow: 0 16px 48px rgba(16,39,77,.2);
        border: 1px solid #e8eef8; overflow: hidden;
        transition: transform .35s cubic-bezier(.4,0,.2,1), opacity .35s;
        opacity: 0;
    }
    #pwa-install-banner.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
    #pwa-update-toast {
        position: fixed; top: 1rem; left: 50%; transform: translateX(-50%) translateY(-120%);
        z-index: 1070; background: #1a2648; color: #fff;
        border-radius: .85rem; padding: .7rem 1.2rem;
        font-size: .82rem; font-weight: 600;
        box-shadow: 0 8px 24px rgba(16,39,77,.3);
        display: flex; align-items: center; gap: .6rem;
        transition: transform .3s cubic-bezier(.4,0,.2,1);
        white-space: nowrap;
    }
    #pwa-update-toast.show { transform: translateX(-50%) translateY(0); }
    </style>

    {{-- Banner de instalación --}}
    <div id="pwa-install-banner" role="dialog" aria-label="Instalar aplicación">
        <div style="background:linear-gradient(135deg,#1a2648,#2d4278);padding:.9rem 1.1rem;display:flex;align-items:center;gap:.85rem;">
            <img src="/img/logo.png" alt="Logo" style="width:44px;height:44px;border-radius:.7rem;object-fit:cover;border:2px solid rgba(255,255,255,.2);" onerror="this.src='/img/logo.jpeg'">
            <div style="flex:1;min-width:0;">
                <div style="font-weight:800;color:#fff;font-size:.9rem;">Galpon</div>
                <div style="font-size:.72rem;color:rgba(255,255,255,.65);">Instala la app para acceso rápido</div>
            </div>
            <button id="pwa-banner-close" aria-label="Cerrar" style="background:rgba(255,255,255,.15);border:none;color:#fff;border-radius:50%;width:28px;height:28px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.85rem;flex-shrink:0;">✕</button>
        </div>
        <div style="padding:.9rem 1.1rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.5rem;margin-bottom:.9rem;">
                <div style="text-align:center;font-size:.72rem;color:#60708d;">
                    <span style="display:block;font-size:1.3rem;margin-bottom:.2rem;">⚡</span>Acceso rápido
                </div>
                <div style="text-align:center;font-size:.72rem;color:#60708d;">
                    <span style="display:block;font-size:1.3rem;margin-bottom:.2rem;">📶</span>Sin internet
                </div>
                <div style="text-align:center;font-size:.72rem;color:#60708d;">
                    <span style="display:block;font-size:1.3rem;margin-bottom:.2rem;">🔔</span>Notificaciones
                </div>
            </div>
            <div style="display:flex;gap:.6rem;">
                <button id="pwa-banner-dismiss" style="flex:1;background:#f3f6fd;border:1.5px solid #d7dfed;border-radius:.7rem;padding:.55rem;font-family:inherit;font-size:.82rem;font-weight:600;color:#374151;cursor:pointer;">Ahora no</button>
                <button id="pwa-banner-install" style="flex:2;background:linear-gradient(135deg,#1a2648,#3b82f6);border:none;border-radius:.7rem;padding:.55rem;font-family:inherit;font-size:.85rem;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.4rem;">
                    <span class="material-symbols-outlined" style="font-size:1rem;">download</span> Instalar app
                </button>
            </div>
        </div>
    </div>

    {{-- Toast de actualización disponible --}}
    <div id="pwa-update-toast">
        <span class="material-symbols-outlined" style="font-size:1rem;color:#fbbf24;">system_update</span>
        Nueva versión disponible
        <button onclick="applyPwaUpdate()" style="background:#3b82f6;border:none;color:#fff;border-radius:.5rem;padding:.25rem .65rem;font-size:.75rem;font-weight:700;cursor:pointer;font-family:inherit;margin-left:.3rem;">Actualizar</button>
        <button onclick="document.getElementById('pwa-update-toast').classList.remove('show')" style="background:rgba(255,255,255,.1);border:none;color:rgba(255,255,255,.6);border-radius:.4rem;padding:.25rem .5rem;font-size:.75rem;cursor:pointer;font-family:inherit;">✕</button>
    </div>

    <script>
    // ══ PWA ══════════════════════════════════════════════
    let deferredPrompt = null;
    let newWorker      = null;

    // Registrar Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js').then(reg => {
                // Detectar actualización disponible
                reg.addEventListener('updatefound', () => {
                    newWorker = reg.installing;
                    newWorker?.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            document.getElementById('pwa-update-toast').classList.add('show');
                        }
                    });
                });

                // Revisar actualizaciones cada 30 min
                setInterval(() => reg.update(), 30 * 60 * 1000);
            }).catch(() => { /* SW no disponible */ });

            // Recargar cuando el SW nuevo tome control
            navigator.serviceWorker.addEventListener('controllerchange', () => {
                if (window._pwaReloading) return;
                window._pwaReloading = true;
                window.location.reload();
            });
        });
    }

    function applyPwaUpdate() {
        if (newWorker) newWorker.postMessage({ type: 'SKIP_WAITING' });
        document.getElementById('pwa-update-toast').classList.remove('show');
    }

    // Capturar evento de instalación
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;

        // Mostrar banner solo si no fue descartado recientemente (7 días)
        const dismissed = localStorage.getItem('pwa_dismissed');
        const now = Date.now();
        if (!dismissed || (now - parseInt(dismissed)) > 7 * 24 * 60 * 60 * 1000) {
            setTimeout(() => {
                document.getElementById('pwa-install-banner').classList.add('show');
            }, 3000); // Mostrar después de 3 segundos
        }
    });

    // Botón instalar
    document.getElementById('pwa-banner-install').addEventListener('click', async () => {
        document.getElementById('pwa-install-banner').classList.remove('show');
        if (!deferredPrompt) return;
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        deferredPrompt = null;
        if (outcome === 'accepted' && window.Swal) {
            Swal.fire({ icon: 'success', title: '¡App instalada!', text: 'Ya puedes acceder desde tu pantalla de inicio.', timer: 2500, showConfirmButton: false });
        }
    });

    // Cerrar / posponer
    document.getElementById('pwa-banner-close').addEventListener('click', dismissBanner);
    document.getElementById('pwa-banner-dismiss').addEventListener('click', dismissBanner);
    function dismissBanner() {
        document.getElementById('pwa-install-banner').classList.remove('show');
        localStorage.setItem('pwa_dismissed', Date.now().toString());
    }

    // Ya está instalada
    window.addEventListener('appinstalled', () => {
        document.getElementById('pwa-install-banner').classList.remove('show');
        deferredPrompt = null;
    });
    </script>

    @auth @if(!auth()->user()->is_superadmin)
    <script>
    // ── Notificaciones usuario ──
    async function loadUserNotifications() {
        try {
            const res = await fetch('/api/notifications', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
            const data = await res.json();
            const notifs = Array.isArray(data) ? data : (data.data || []);
            const unread = notifs.filter(n => !n.read_at).length;
            const badge = document.getElementById('user-notif-badge');
            if (badge) {
                badge.textContent = unread > 9 ? '9+' : unread;
                badge.style.display = unread > 0 ? 'flex' : 'none';
            }
            const list = document.getElementById('user-notif-list');
            if (!list) return;
            if (!notifs.length) {
                list.innerHTML = '<div style="padding:1.5rem;text-align:center;color:#94a3b8;font-size:.82rem;"><span class="material-symbols-outlined" style="display:block;font-size:2rem;margin-bottom:.4rem;">check_circle</span>Sin notificaciones</div>';
                return;
            }
            list.innerHTML = notifs.slice(0, 10).map(n => {
                const d = n.data || {};
                const isRead = !!n.read_at;
                return `<div onclick="markReadUser('${n.id}')" style="display:flex;gap:.65rem;padding:.7rem 1rem;border-bottom:1px solid #f1f5fb;cursor:pointer;background:${isRead ? '#fff' : '#f0f6ff'};transition:background .15s;" onmouseenter="this.style.background='#f8faff'" onmouseleave="this.style.background='${isRead ? '#fff' : '#f0f6ff'}'">
                    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#dbeafe,#eff6ff);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <span class="material-symbols-outlined" style="font-size:.9rem;color:#3b82f6;">${d.icon || 'notifications'}</span>
                    </div>
                    <div style="overflow:hidden;">
                        <div style="font-size:.78rem;font-weight:${isRead ? '500' : '700'};color:#1a2648;">${d.title || d.message || 'Notificación'}</div>
                        <div style="font-size:.7rem;color:#94a3b8;">${new Date(n.created_at).toLocaleDateString('es-VE')}</div>
                    </div>
                </div>`;
            }).join('');
        } catch(e) { /* silencioso */ }
    }
    async function markReadUser(id) {
        await fetch(`/api/notifications/${id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
        loadUserNotifications();
    }
    async function markAllReadUser() {
        await fetch('/api/notifications/read-all', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
        loadUserNotifications();
    }
    document.addEventListener('DOMContentLoaded', () => {
        loadUserNotifications();
        // Recargar al abrir el dropdown
        const btn = document.getElementById('btn-user-bell');
        if (btn) btn.addEventListener('click', loadUserNotifications);
    });
    </script>
    @endif @endauth
</html>
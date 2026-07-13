@php
    use App\Services\SettingsService;
    use App\Services\DollarRateService;
    $settings      = SettingsService::get();
    $proPrice      = $settings['plans']['pro_monthly_price']  ?? 15;
    $proYearly     = $settings['plans']['pro_yearly_price']   ?? 150;
    $proCurrency   = $settings['plans']['pro_currency']       ?? 'USD';
    $freeGallos    = $settings['plans']['free_gallos_limit']  ?? 20;
    $freeGallinas  = $settings['plans']['free_gallinas_limit']?? 20;
    $extraGalpon   = $settings['plans']['extra_galpon_price'] ?? 5;
    $rateDay       = $settings['plans']['marketplace_rating_days'] ?? 12;
    $rate          = $rate ?? DollarRateService::getCachedRate();
    $whatsapp      = env('WHATSAPP_SUPPORT_URL', 'https://wa.me/584120000000');

    $toBS = fn(float $usd) => $rate > 0 ? number_format($usd * $rate, 2) : null;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planes y Precios — Galpon</title>
    <meta name="description" content="Conoce los planes de Galpon para gestionar tu criadero de gallos finos: plan gratuito, plan Pro con aves ilimitadas y pedigree completo. Precios en dólares y bolívares.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('plans') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">
    <meta name="theme-color" content="#1a2648">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        *, ::before, ::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Poppins', sans-serif; background: #07090f; color: #f0f4ff; overflow-x: hidden; }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 1.25rem; }
        .text-gradient { background: linear-gradient(135deg,#60a5fa,#a78bfa,#f472b6); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .text-gold { background: linear-gradient(135deg,#fbbf24,#f59e0b); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

        nav.topnav { position:fixed;top:0;left:0;right:0;z-index:100;background:rgba(7,9,15,.88);border-bottom:1px solid rgba(255,255,255,.07);backdrop-filter:blur(14px);padding:.75rem 0; }
        nav.topnav .nav-inner { display:flex;align-items:center;justify-content:space-between; }
        .nav-logo { display:flex;align-items:center;gap:.65rem;text-decoration:none; }
        .nav-logo img { width:38px;height:38px;border-radius:10px;object-fit:cover; }
        .nav-logo span { font-weight:800;font-size:1.2rem;color:#fff; }
        .nav-links { display:flex;align-items:center;gap:.25rem; }
        .nav-links a { color:rgba(255,255,255,.65);font-weight:500;font-size:.88rem;text-decoration:none;padding:.4rem .75rem;border-radius:.6rem;transition:color .2s,background .2s; }
        .nav-links a:hover,.nav-links a.active { color:#fff;background:rgba(255,255,255,.08); }
        .nav-actions { display:flex;align-items:center;gap:.75rem; }
        .btn-primary { display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#3b82f6,#7c3aed);color:#fff;border:none;border-radius:.85rem;padding:.7rem 1.5rem;font-family:'Poppins',sans-serif;font-size:.9rem;font-weight:700;cursor:pointer;text-decoration:none;transition:transform .2s,box-shadow .2s;box-shadow:0 8px 28px rgba(59,130,246,.35); }
        .btn-primary:hover { transform:translateY(-2px);box-shadow:0 14px 36px rgba(59,130,246,.45);color:#fff; }
        .btn-ghost { display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.2);color:#e0e7ff;border-radius:.85rem;padding:.7rem 1.5rem;font-family:'Poppins',sans-serif;font-size:.9rem;font-weight:600;cursor:pointer;text-decoration:none;transition:all .2s; }
        .btn-ghost:hover { background:rgba(255,255,255,.14);color:#fff; }

        /* ── Hero ── */
        .hero { padding:8rem 1.25rem 5rem;text-align:center;position:relative;overflow:hidden; }
        .hero::before { content:'';position:absolute;inset:0;background:radial-gradient(ellipse 80% 50% at 50% 0,rgba(59,130,246,.18) 0%,transparent 70%);pointer-events:none; }
        .hero-badge { display:inline-flex;align-items:center;gap:.4rem;background:rgba(59,130,246,.12);border:1px solid rgba(59,130,246,.35);border-radius:999px;padding:.35rem .9rem;font-size:.78rem;font-weight:700;color:#93c5fd;letter-spacing:.05em;text-transform:uppercase;margin-bottom:1.25rem; }
        .hero h1 { font-size:clamp(2rem,5vw,3.5rem);font-weight:900;line-height:1.08;letter-spacing:-.03em;margin-bottom:.9rem; }
        .hero p { color:rgba(224,231,255,.65);font-size:1.05rem;max-width:520px;margin:0 auto 2rem;line-height:1.7; }

        /* ── Rate badge ── */
        .rate-badge { display:inline-flex;align-items:center;gap:.5rem;background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.3);border-radius:.65rem;padding:.4rem .9rem;font-size:.8rem;color:#fde68a;font-weight:600;margin-bottom:2rem; }

        /* ── Plans grid ── */
        .plans-grid { display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.5rem;margin-top:3rem; }
        @media(max-width:900px){ .plans-grid{grid-template-columns:1fr;max-width:480px;margin-left:auto;margin-right:auto;} }

        .plan-card { background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:1.4rem;padding:2rem 1.75rem;display:flex;flex-direction:column;transition:transform .2s,border-color .2s; }
        .plan-card:hover { transform:translateY(-4px);border-color:rgba(96,165,250,.3); }
        .plan-card.featured { background:linear-gradient(160deg,rgba(26,38,72,.9),rgba(45,66,120,.9));border-color:rgba(96,165,250,.4);position:relative; }
        .plan-popular { position:absolute;top:-14px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg,#3b82f6,#7c3aed);color:#fff;font-size:.7rem;font-weight:800;border-radius:999px;padding:.28rem .85rem;letter-spacing:.06em;text-transform:uppercase;white-space:nowrap; }
        .plan-type { font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#60a5fa;margin-bottom:.6rem; }
        .plan-name { font-size:1.5rem;font-weight:900;margin-bottom:.2rem; }
        .plan-price-usd { font-size:2.6rem;font-weight:900;line-height:1;margin-bottom:.25rem; }
        .plan-price-usd small { font-size:.88rem;font-weight:400;color:rgba(255,255,255,.5); }
        .plan-price-bs { font-size:.82rem;color:rgba(224,231,255,.55);margin-bottom:1rem; }
        .plan-desc { font-size:.85rem;color:rgba(224,231,255,.55);margin-bottom:1.4rem;line-height:1.6; }
        .plan-feats { list-style:none;padding:0;margin:0 0 1.75rem;flex:1; }
        .plan-feats li { display:flex;align-items:flex-start;gap:.55rem;padding:.38rem 0;font-size:.86rem;color:rgba(224,231,255,.8);border-bottom:1px solid rgba(255,255,255,.05); }
        .plan-feats li:last-child { border-bottom:none; }
        .plan-feats li.off { color:rgba(255,255,255,.3); }
        .feat-check { color:#60a5fa;font-weight:900;flex-shrink:0;font-size:.9rem; }
        .feat-cross { color:rgba(255,255,255,.2);flex-shrink:0;font-size:.9rem; }
        .plan-cta { display:block;text-align:center;padding:.85rem;border-radius:.85rem;font-weight:700;font-size:.95rem;text-decoration:none;transition:all .2s;border:none;cursor:pointer;font-family:'Poppins',sans-serif; }
        .cta-free { background:rgba(59,130,246,.15);border:1px solid rgba(59,130,246,.3);color:#93c5fd; }
        .cta-free:hover { background:rgba(59,130,246,.25);color:#fff; }
        .cta-pro { background:linear-gradient(135deg,#3b82f6,#7c3aed);color:#fff;box-shadow:0 8px 24px rgba(59,130,246,.35); }
        .cta-pro:hover { box-shadow:0 12px 32px rgba(59,130,246,.5);color:#fff; }
        .cta-wa { background:rgba(37,211,102,.15);border:1px solid rgba(37,211,102,.3);color:#4ade80; }
        .cta-wa:hover { background:rgba(37,211,102,.25);color:#fff; }

        /* ── Add-on banner ── */
        .addon-banner { background:rgba(251,191,36,.06);border:1px solid rgba(251,191,36,.2);border-radius:1.2rem;padding:1.75rem 2rem;margin-top:1.5rem;display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap; }
        .addon-icon { font-size:2.5rem;flex-shrink:0; }
        .addon-info { flex:1;min-width:200px; }
        .addon-title { font-size:1.05rem;font-weight:800;margin-bottom:.3rem; }
        .addon-desc { font-size:.86rem;color:rgba(224,231,255,.65);line-height:1.6; }

        /* ── Comparison table ── */
        .comp-table { width:100%;border-collapse:separate;border-spacing:0;margin-top:3rem; }
        .comp-table th { padding:.85rem 1rem;font-size:.78rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:rgba(224,231,255,.55);text-align:left; }
        .comp-table th:not(:first-child) { text-align:center; }
        .comp-table td { padding:.75rem 1rem;font-size:.88rem;border-bottom:1px solid rgba(255,255,255,.05); }
        .comp-table td:not(:first-child) { text-align:center; }
        .comp-table tr:last-child td { border-bottom:none; }
        .comp-table .check { color:#4ade80;font-size:1rem; }
        .comp-table .cross { color:rgba(255,255,255,.2);font-size:1rem; }
        .comp-table .col-head { color:#fff;font-weight:700;background:rgba(255,255,255,.03); }
        .comp-table .col-pro { background:rgba(59,130,246,.05);font-weight:700;color:#93c5fd; }
        .comp-section { font-size:.75rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(224,231,255,.35);padding:.5rem 1rem; }

        /* ── FAQ ── */
        .faq-list { margin-top:2rem;display:flex;flex-direction:column;gap:.75rem; }
        .faq-item { background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:1rem;overflow:hidden; }
        .faq-q { width:100%;background:none;border:none;color:#f0f4ff;font-family:'Poppins',sans-serif;font-size:.95rem;font-weight:600;text-align:left;padding:1.1rem 1.3rem;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:.75rem; }
        .faq-icon { font-size:1.1rem;color:#60a5fa;flex-shrink:0;transition:transform .25s; }
        .faq-a { max-height:0;overflow:hidden;transition:max-height .35s ease,padding .25s;padding:0 1.3rem;font-size:.88rem;color:rgba(224,231,255,.7);line-height:1.7; }
        .faq-item.open .faq-a { max-height:300px;padding:0 1.3rem 1.1rem; }
        .faq-item.open .faq-icon { transform:rotate(45deg); }

        /* ── Section ── */
        section { padding:5rem 0; }
        .section-label { display:inline-flex;align-items:center;gap:.35rem;font-size:.73rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#60a5fa;margin-bottom:.75rem; }
        .section-title { font-size:clamp(1.6rem,3vw,2.4rem);font-weight:800;line-height:1.15;letter-spacing:-.02em;margin-bottom:.75rem; }

        /* ── WA float ── */
        .wa-float { position:fixed;bottom:2rem;right:1.5rem;z-index:9999;width:58px;height:58px;border-radius:50%;border:none;cursor:pointer;background:#25d366;box-shadow:0 6px 20px rgba(37,211,102,.5);display:flex;align-items:center;justify-content:center;transition:transform .2s,box-shadow .2s;text-decoration:none; }
        .wa-float:hover { transform:scale(1.1);box-shadow:0 10px 28px rgba(37,211,102,.6); }
        .wa-float svg { width:30px;height:30px;fill:#fff; }

        @media(max-width:640px){ .nav-links{display:none;} .addon-banner{flex-direction:column;} }
    </style>
</head>
<body>

    {{-- NAV --}}
    <nav class="topnav">
        <div class="container nav-inner">
            <a href="{{ url('/') }}" class="nav-logo">
                <img src="{{ asset('img/logo.png') }}" alt="Galpon" width="38" height="38">
                <span>Galpon</span>
            </a>
            <div class="nav-links">
                <a href="{{ route('marketplace.index') }}">Marketplace</a>
                <a href="{{ route('plans') }}" class="active">Planes y Precios</a>
            </div>
            <div class="nav-actions">
                @auth
                    <a href="{{ route('panel') }}" class="btn-primary" style="padding:.6rem 1.25rem;font-size:.88rem;">Mi Panel</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost" style="padding:.6rem 1.1rem;font-size:.88rem;">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="btn-primary" style="padding:.6rem 1.1rem;font-size:.88rem;">Registrarse</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <header class="hero">
        <div class="hero-badge">
            <span class="material-symbols-outlined" style="font-size:.9rem;">workspace_premium</span>
            Planes y Precios
        </div>
        <h1>Un plan para cada <span class="text-gradient">criadero</span></h1>
        <p>Empieza gratis y crece a tu ritmo. Sin contratos, sin sorpresas. Paga solo lo que necesitas.</p>
        @if($rate > 0)
        <div class="rate-badge">
            <span class="material-symbols-outlined" style="font-size:.85rem;">currency_exchange</span>
            Tasa BCV vigente: <strong>Bs. {{ number_format($rate, 2) }}</strong> / $1 USD
        </div>
        @endif
    </header>

    {{-- PLANES --}}
    <section>
        <div class="container">
            <div class="plans-grid">

                {{-- Plan Básico --}}
                <div class="plan-card">
                    <div class="plan-type">Plan Básico</div>
                    <div class="plan-name">Gratis</div>
                    <div class="plan-price-usd text-gradient">$0 <small>/ mes</small></div>
                    <div class="plan-price-bs">Bs. 0,00 / mes</div>
                    <div class="plan-desc">Para criadores que empiezan y quieren probar la plataforma sin costo.</div>
                    <ul class="plan-feats">
                        <li><span class="feat-check">✓</span> Hasta <strong>{{ $freeGallos }} gallos</strong> registrados</li>
                        <li><span class="feat-check">✓</span> Hasta <strong>{{ $freeGallinas }} gallinas</strong> registradas</li>
                        <li><span class="feat-check">✓</span> Pedigree básico (padre y madre)</li>
                        <li><span class="feat-check">✓</span> Ficha PDF individual por ave</li>
                        <li><span class="feat-check">✓</span> Control de inventario básico</li>
                        <li><span class="feat-check">✓</span> Registro de ventas y compras</li>
                        <li class="off"><span class="feat-cross">✕</span> Pedigree completo con árbol genealógico</li>
                        <li class="off"><span class="feat-cross">✕</span> Reportes PDF avanzados</li>
                        <li class="off"><span class="feat-cross">✕</span> Publicar en Marketplace</li>
                        <li class="off"><span class="feat-cross">✕</span> Tasa BCV automática en ventas</li>
                    </ul>
                    @guest
                        <a href="{{ route('register') }}" class="plan-cta cta-free">Comenzar gratis</a>
                    @else
                        <a href="{{ route('home') }}" class="plan-cta cta-free">Ir a mi panel</a>
                    @endguest
                </div>

                {{-- Plan Pro --}}
                <div class="plan-card featured" style="position:relative;">
                    <div class="plan-popular">⭐ Más popular</div>
                    <div class="plan-type" style="color:#93c5fd;">Plan Pro</div>
                    <div class="plan-name text-gradient">Profesional</div>
                    <div class="plan-price-usd text-gradient">
                        ${{ $proPrice }} <small>/ mes</small>
                    </div>
                    <div class="plan-price-bs">
                        @if($rate > 0)
                            ≈ Bs. {{ number_format($proPrice * $rate, 2) }} / mes
                        @else
                            Consultar equivalente en Bs.
                        @endif
                    </div>
                    <div class="plan-desc" style="color:rgba(224,231,255,.7);">
                        Para criadores serios. Aves ilimitadas, pedigree completo y acceso al marketplace.
                    </div>
                    <ul class="plan-feats">
                        <li><span class="feat-check">✓</span> <strong>Gallos y gallinas ilimitados</strong></li>
                        <li><span class="feat-check">✓</span> Pedigree completo + árbol genealógico PDF</li>
                        <li><span class="feat-check">✓</span> Reportes avanzados (gallos, gallinas, ventas)</li>
                        <li><span class="feat-check">✓</span> <strong>Publicar en el Marketplace</strong></li>
                        <li><span class="feat-check">✓</span> Tasa BCV actualizada automáticamente</li>
                        <li><span class="feat-check">✓</span> Módulo de compras con proveedores</li>
                        <li><span class="feat-check">✓</span> Coeficiente de consanguinidad automático</li>
                        <li><span class="feat-check">✓</span> Dashboard con estadísticas en tiempo real</li>
                        <li><span class="feat-check">✓</span> Galería de imágenes por ave</li>
                        <li><span class="feat-check">✓</span> Soporte prioritario por WhatsApp</li>
                    </ul>
                    @auth
                        <a href="{{ route('tenant.payments.create') }}" class="plan-cta cta-pro">Activar Plan Pro</a>
                    @else
                        <a href="{{ route('register') }}" class="plan-cta cta-pro">Empezar ahora</a>
                    @endauth
                </div>

                {{-- Anual --}}
                <div class="plan-card">
                    <div class="plan-type">Plan Pro — Anual</div>
                    <div class="plan-name text-gold">Ahorro Anual</div>
                    <div class="plan-price-usd text-gold">
                        ${{ $proYearly }} <small>/ año</small>
                    </div>
                    <div class="plan-price-bs">
                        @if($rate > 0)
                            ≈ Bs. {{ number_format($proYearly * $rate, 2) }} / año
                        @else
                            Consultar equivalente en Bs.
                        @endif
                        <span style="background:rgba(74,222,128,.15);color:#4ade80;border-radius:999px;padding:.1rem .5rem;font-size:.7rem;font-weight:700;margin-left:.35rem;">
                            Ahorras ${{ ($proPrice * 12) - $proYearly }}
                        </span>
                    </div>
                    <div class="plan-desc">Paga todo el año y ahorra. Exactamente las mismas funciones Pro sin interrupciones.</div>
                    <ul class="plan-feats">
                        <li><span class="feat-check">✓</span> Todas las funciones del Plan Pro</li>
                        <li><span class="feat-check">✓</span> 12 meses de acceso ininterrumpido</li>
                        <li><span class="feat-check">✓</span> Precio fijo garantizado todo el año</li>
                        <li><span class="feat-check">✓</span> Soporte prioritario incluido</li>
                        <li style="color:rgba(74,222,128,.9);"><span style="color:#4ade80;font-weight:800;">✓</span> <strong style="color:#4ade80;">Ahorra ${{ ($proPrice * 12) - $proYearly }} vs pago mensual</strong></li>
                    </ul>
                    <a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="plan-cta cta-wa">
                        Activar plan anual
                    </a>
                </div>
            </div>

            {{-- Add-on: Galpón Extra --}}
            <div class="addon-banner">
                <div class="addon-icon">➕</div>
                <div class="addon-info">
                    <div class="addon-title">Add-on: Galpón Adicional</div>
                    <div class="addon-desc">
                        ¿Necesitas gestionar un segundo criadero? Con el plan Pro activo puedes añadir galpones extra por
                        <strong style="color:#fde68a;">${{ $extraGalpon }} / mes</strong> cada uno
                        @if($rate > 0)(≈ Bs. {{ number_format($extraGalpon * $rate, 2) }})@endif.
                        Cada galpón tiene su propio inventario, aves y registros completamente separados.
                    </div>
                </div>
                <a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer"
                   style="display:inline-flex;align-items:center;gap:.4rem;background:rgba(251,191,36,.15);border:1px solid rgba(251,191,36,.3);color:#fde68a;border-radius:.8rem;padding:.65rem 1.25rem;font-size:.88rem;font-weight:700;text-decoration:none;white-space:nowrap;transition:all .2s;"
                   onmouseenter="this.style.background='rgba(251,191,36,.25)'" onmouseleave="this.style.background='rgba(251,191,36,.15)'">
                    <svg style="width:18px;fill:#fde68a;" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Consultar por WhatsApp
                </a>
            </div>

            {{-- Tabla de comparación --}}
            <div class="section-label" style="margin-top:4rem;">
                <span class="material-symbols-outlined" style="font-size:.9rem;">compare</span>
                Comparativa completa
            </div>
            <h2 class="section-title">¿Qué incluye cada plan?</h2>

            <div style="overflow-x:auto;">
            <table class="comp-table">
                <thead>
                    <tr>
                        <th style="width:50%;">Característica</th>
                        <th>Básico</th>
                        <th class="col-pro">Pro</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="3" class="comp-section">Registro de Aves</td></tr>
                    <tr><td>Gallos registrados</td><td>Hasta {{ $freeGallos }}</td><td class="col-pro">Ilimitados</td></tr>
                    <tr><td>Gallinas registradas</td><td>Hasta {{ $freeGallinas }}</td><td class="col-pro">Ilimitadas</td></tr>
                    <tr><td>Fotos por ave</td><td class="check">✓</td><td class="col-pro check">✓</td></tr>
                    <tr><td>Registro de peso / pesaje</td><td class="check">✓</td><td class="col-pro check">✓</td></tr>
                    <tr><td colspan="3" class="comp-section">Pedigree</td></tr>
                    <tr><td>Registro de padre y madre</td><td class="check">✓</td><td class="col-pro check">✓</td></tr>
                    <tr><td>Árbol genealógico completo</td><td class="cross">✕</td><td class="col-pro check">✓</td></tr>
                    <tr><td>Exportar pedigree en PDF</td><td class="cross">✕</td><td class="col-pro check">✓</td></tr>
                    <tr><td>Coeficiente de consanguinidad</td><td class="cross">✕</td><td class="col-pro check">✓</td></tr>
                    <tr><td colspan="3" class="comp-section">Inventario y Finanzas</td></tr>
                    <tr><td>Control de inventario</td><td class="check">✓</td><td class="col-pro check">✓</td></tr>
                    <tr><td>Registro de ventas</td><td class="check">✓</td><td class="col-pro check">✓</td></tr>
                    <tr><td>Módulo de compras (proveedores)</td><td class="cross">✕</td><td class="col-pro check">✓</td></tr>
                    <tr><td>Tasa BCV automática</td><td class="cross">✕</td><td class="col-pro check">✓</td></tr>
                    <tr><td>Precios en Bs y USD simultáneos</td><td class="cross">✕</td><td class="col-pro check">✓</td></tr>
                    <tr><td colspan="3" class="comp-section">Reportes</td></tr>
                    <tr><td>Ficha PDF individual</td><td class="check">✓</td><td class="col-pro check">✓</td></tr>
                    <tr><td>Reporte PDF de todo el criadero</td><td class="cross">✕</td><td class="col-pro check">✓</td></tr>
                    <tr><td>Dashboard con estadísticas</td><td class="cross">✕</td><td class="col-pro check">✓</td></tr>
                    <tr><td colspan="3" class="comp-section">Marketplace</td></tr>
                    <tr><td>Ver publicaciones de otros criadores</td><td class="check">✓</td><td class="col-pro check">✓</td></tr>
                    <tr><td>Publicar gallos y gallinas</td><td class="cross">✕</td><td class="col-pro check">✓</td></tr>
                    <tr><td>Chat de negociación</td><td class="cross">✕</td><td class="col-pro check">✓</td></tr>
                    <tr><td>Reputación y calificaciones</td><td class="cross">✕</td><td class="col-pro check">✓</td></tr>
                    <tr><td colspan="3" class="comp-section">Soporte</td></tr>
                    <tr><td>Acceso a actualizaciones</td><td class="check">✓</td><td class="col-pro check">✓</td></tr>
                    <tr><td>Soporte prioritario por WhatsApp</td><td class="cross">✕</td><td class="col-pro check">✓</td></tr>
                </tbody>
            </table>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section style="background:rgba(255,255,255,.015);border-top:1px solid rgba(255,255,255,.06);">
        <div class="container" style="max-width:760px;">
            <div class="section-label" style="justify-content:center;display:flex;">
                <span class="material-symbols-outlined" style="font-size:.9rem;">help</span>
                Preguntas frecuentes
            </div>
            <h2 class="section-title" style="text-align:center;margin-bottom:2rem;">Acerca de los planes</h2>
            <div class="faq-list">
                <div class="faq-item">
                    <button class="faq-q" aria-expanded="false">
                        ¿Cómo activo el Plan Pro?
                        <span class="material-symbols-outlined faq-icon">add</span>
                    </button>
                    <div class="faq-a">
                        Crea tu cuenta gratis, accede al panel y ve a "Pago Pro" en el menú. Allí podrás subir tu comprobante de pago (transferencia, Zelle, Pago Móvil o USDT). El plan se activa tras verificación manual por nuestro equipo, normalmente en menos de 24 horas.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-q" aria-expanded="false">
                        ¿Qué métodos de pago aceptan?
                        <span class="material-symbols-outlined faq-icon">add</span>
                    </button>
                    <div class="faq-a">
                        Aceptamos Zelle (en USD), Pago Móvil (en bolívares al tipo de cambio BCV), transferencia bancaria Venezuela y USDT por la red TRC20 (Tron). Contáctanos por WhatsApp para confirmar los datos de pago actualizados.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-q" aria-expanded="false">
                        ¿Puedo cancelar el plan cuando quiera?
                        <span class="material-symbols-outlined faq-icon">add</span>
                    </button>
                    <div class="faq-a">
                        Sí. No hay contratos de permanencia. Si no renuevas el plan Pro, tu cuenta vuelve automáticamente al plan Básico (con sus límites). Todos tus datos permanecen intactos, solo se limita el acceso a funciones Pro.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-q" aria-expanded="false">
                        ¿Por qué el precio en bolívares cambia?
                        <span class="material-symbols-outlined faq-icon">add</span>
                    </button>
                    <div class="faq-a">
                        Los precios base están en dólares. Al momento de pagar en bolívares, usamos la tasa oficial del BCV vigente ese día. Si el plan es mensual, cada renovación usa la tasa del día de renovación. El plan anual fija el monto al momento del pago inicial.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-q" aria-expanded="false">
                        ¿Qué pasa con mis aves si supero el límite del plan Básico?
                        <span class="material-symbols-outlined faq-icon">add</span>
                    </button>
                    <div class="faq-a">
                        Si ya tienes {{ $freeGallos }} gallos registrados y no tienes plan Pro, el sistema te impedirá registrar nuevos. Pero tus aves existentes no se eliminan. Cuando activas el plan Pro, puedes continuar registrando sin límite.
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section style="text-align:center;padding:5rem 1.25rem;">
        <div class="container">
            <h2 style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;margin-bottom:1rem;">
                ¿Listo para gestionar tu criadero <span class="text-gradient">como los pros</span>?
            </h2>
            <p style="color:rgba(224,231,255,.6);font-size:1.05rem;margin-bottom:2rem;">
                Empieza gratis hoy. Sin tarjeta de crédito.
            </p>
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('register') }}" class="btn-primary">
                    <span class="material-symbols-outlined" style="font-size:1.1rem;">rocket_launch</span>
                    Crear cuenta gratis
                </a>
                <a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="btn-ghost">
                    <svg style="width:18px;fill:currentColor;" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Consultar por WhatsApp
                </a>
            </div>
        </div>
    </section>

    {{-- WA Float --}}
    <a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="wa-float" aria-label="WhatsApp">
        <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>

    <script>
    document.querySelectorAll('.faq-q').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.faq-item');
            const open = item.classList.contains('open');
            document.querySelectorAll('.faq-item.open').forEach(i => { i.classList.remove('open'); i.querySelector('.faq-q').setAttribute('aria-expanded','false'); });
            if (!open) { item.classList.add('open'); btn.setAttribute('aria-expanded','true'); }
        });
    });
    </script>
</body>
</html>

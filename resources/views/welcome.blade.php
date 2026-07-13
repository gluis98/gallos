@php
    use App\Services\SettingsService;
    $settings    = SettingsService::get();
    $proPrice    = $settings['plans']['pro_monthly_price']  ?? 15;
    $proYearly   = $settings['plans']['pro_yearly_price']   ?? 150;
    $proCurrency = $settings['plans']['pro_currency']       ?? 'USD';
    $freeGallos  = $settings['plans']['free_gallos_limit']  ?? 20;
    $whatsapp    = env('WHATSAPP_SUPPORT_URL', 'https://wa.me/584120000000');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ══ SEO Principal ══ --}}
    <title>Galpon — Software para Criaderos de Gallos y Gallinas | Venezuela y Latinoamérica</title>
    <meta name="description" content="Galpon es el software especializado para gestionar tu criadero de gallos finos y gallinas de raza. Controla pedigree, inventario, ventas, compras y reportes desde el celular. Para criadores en Venezuela, Colombia, México, Perú, Rep. Dominicana y toda Latinoamérica.">
    <meta name="keywords" content="
        software gallos, sistema gallos, gestión gallos, app gallos, aplicación gallos, programa gallos,
        criadero gallos, criadero de gallos, administrar criadero, manejo criadero,
        gallos finos, gallos de pelea, gallos de combate, gallos de línea, gallos cruzados, gallos puros, gallos de raza,
        pedigree gallos, árbol genealógico gallos, genealogía gallos, linaje gallos, registro gallos, certificado pedigree,
        gallos Venezuela, criadores gallos Venezuela, criadero gallos venezolano, galpón Venezuela,
        gallos Colombia, criadores gallos Colombia,
        gallos México, criadores gallos México,
        gallos Perú, gallos Ecuador, gallos República Dominicana, gallos Puerto Rico, gallos Cuba,
        gallos Panamá, gallos Costa Rica, gallos El Salvador, gallos Honduras, gallos Bolivia, gallos Paraguay,
        galpón avícola, galponera, gallinero, galpón de gallos, galpón crianza,
        inventario gallinas, gallinas de raza, gallinas ponedoras, gallinas finas, gestión gallinas,
        control aves, software avicultura, sistema avicultura, administración criadero avícola,
        control de gallos, base de datos gallos, registro aves, ficha técnica gallos, historial gallos,
        ventas gallos, compra gallos, mercado gallos, marketplace avícola, publicaciones aves,
        reportes criadero, exportar PDF gallos, reporte pedigree, estadísticas gallos,
        software agropecuario latinoamerica, software pecuario, programa avicultura,
        plataforma criadores gallos, gestión multiple galpones, varios galpones,
        peso gallos, pesaje aves, control peso gallos, desarrollo muscular gallos,
        crianza gallos, manejo gallos, cuidado gallos, alimentación gallos, vitaminas gallos,
        gallos campeones, gallos premiados, gallos finos Venezuela, gallos de alto rendimiento,
        tasa BCV gallos, precios dolares bolivares gallos, compra venta gallos Venezuela,
        app movil gallos, celular gallos, android gallos, ios gallos,
        vacunación gallos, vacunas avícolas, vacunación avícola gallos, calendario vacunación aves,
        Newcastle gallos, Marek gallos, Gumboro aves, Bronquitis Infecciosa gallos, Viruela Aviar gallos,
        plan sanitario criadero, control sanitario gallos, registro vacunas gallos,
        historial médico gallos, expediente médico aves, ficha médica gallos finos,
        tratamiento gallos enfermos, desparasitación gallos, parásitos externos gallos,
        control parásitos aves, medicamentos gallos, salud gallos finos,
        blog crianza gallos, artículos avicultura, guía gallos finos,
        FAQ crianza gallos, preguntas frecuentes gallos, cómo criar gallos
    ">
    <meta name="author" content="Galpon">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="theme-color" content="#1a2648">
    <meta name="geo.region" content="VE">
    <meta name="geo.placename" content="Venezuela">
    <link rel="canonical" href="{{ url('/') }}">

    {{-- ══ Open Graph ══ --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Galpon — Software para Criaderos de Gallos y Gallinas">
    <meta property="og:description" content="Gestiona tu criadero de gallos finos con Galpon: pedigree, inventario, ventas y reportes. Para criadores en Venezuela y toda Latinoamérica.">
    <meta property="og:image" content="{{ asset('img/logo-512.png') }}">
    <meta property="og:image:width" content="512">
    <meta property="og:image:height" content="512">
    <meta property="og:locale" content="es_VE">
    <meta property="og:site_name" content="Galpon">

    {{-- ══ Twitter Card ══ --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Galpon — Software para Criaderos de Gallos y Gallinas">
    <meta name="twitter:description" content="Pedigree, inventario, ventas y reportes para tu criadero. Hecho para criadores en Venezuela y Latinoamérica.">
    <meta name="twitter:image" content="{{ asset('img/logo-512.png') }}">

    {{-- ══ Favicon completo ══ --}}
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('img/logo-96.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/logo-96.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/logo-192.png') }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Galpon">
    <meta name="application-name" content="Galpon">
    <meta name="msapplication-TileImage" content="{{ asset('img/logo-192.png') }}">
    <meta name="msapplication-TileColor" content="#1a2648">

    {{-- ══ Schema.org: SoftwareApplication ══ --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "Galpon",
        "alternateName": "Galpon — Gestión Avícola",
        "applicationCategory": "BusinessApplication",
        "operatingSystem": "Web, Android, iOS",
        "description": "Software profesional para gestionar criaderos de gallos finos y gallinas de raza. Pedigree, inventario, ventas, compras y reportes. Para criadores en Venezuela y Latinoamérica.",
        "offers": [
            {
                "@type": "Offer",
                "name": "Plan Básico",
                "priceCurrency": "{{ $proCurrency }}",
                "price": "0",
                "description": "Hasta {{ $freeGallos }} gallos y gallinas. Gratis para siempre."
            },
            {
                "@type": "Offer",
                "name": "Plan Pro",
                "priceCurrency": "{{ $proCurrency }}",
                "price": "{{ $proPrice }}",
                "billingIncrement": "P1M",
                "description": "Gallos y gallinas ilimitados, pedigree completo, marketplace y soporte prioritario."
            }
        ],
        "inLanguage": "es",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('img/logo-512.png') }}",
        "screenshot": "{{ asset('img/logo-512.png') }}",
        "availableOnDevice": ["Desktop", "Mobile", "Tablet"],
        "countriesSupported": "VE CO MX PE EC DO PR PA CU BO PY GT SV HN BR"
    }
    </script>

    {{-- ══ Schema.org: FAQ ══ --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "¿Qué es Galpon y para qué sirve?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Galpon es un software especializado para gestionar criaderos de gallos finos y gallinas de raza. Permite registrar aves, construir árboles genealógicos (pedigree), controlar inventario de medicamentos y alimentos, registrar ventas y compras, y generar reportes en PDF. Está diseñado para criadores en Venezuela y toda Latinoamérica."
                }
            },
            {
                "@type": "Question",
                "name": "¿Puedo usar Galpon desde el celular?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Sí. Galpon es una Progressive Web App (PWA) que puedes instalar en tu celular Android o iPhone como si fuera una app nativa. Funciona perfectamente desde el galpón, sin necesidad de estar frente a una computadora."
                }
            },
            {
                "@type": "Question",
                "name": "¿Cómo maneja Galpon los precios en bolívares?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Galpon integra la tasa oficial del BCV (Banco Central de Venezuela) para convertir automáticamente los precios de dólares a bolívares. Cada vez que inicias sesión la tasa se actualiza, así tus precios siempre reflejan la economía actual venezolana."
                }
            },
            {
                "@type": "Question",
                "name": "¿Puedo registrar el pedigree de mis gallos?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Sí. Galpon incluye un módulo completo de pedigree donde puedes registrar el padre, la madre y los hijos de cada gallo. Puedes visualizar el árbol genealógico y exportarlo en PDF para mostrarlo a compradores o en exhibiciones."
                }
            },
            {
                "@type": "Question",
                "name": "¿Galpon funciona para criadores fuera de Venezuela?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Absolutamente. Galpon está disponible para criadores en Colombia, México, Perú, Ecuador, República Dominicana, Puerto Rico, Cuba, Panamá, Bolivia, Paraguay, Guatemala, El Salvador, Honduras, Brasil y toda Latinoamérica. El software está 100% en español."
                }
            },
            {
                "@type": "Question",
                "name": "¿Cuánto cuesta Galpon?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Galpon tiene un plan gratuito que incluye hasta {{ $freeGallos }} gallos y gallinas. El plan Pro cuesta ${{ $proPrice }} al mes e incluye aves ilimitadas, pedigree completo con exportación en PDF, marketplace activo y soporte prioritario."
                }
            },
            {
                "@type": "Question",
                "name": "¿Puedo manejar varios galpones con una sola cuenta?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Sí. Galpon permite gestionar múltiples criaderos desde un solo acceso. Cada galpón tiene su propio espacio privado con su inventario, sus aves y sus registros independientes."
                }
            },
            {
                "@type": "Question",
                "name": "¿Puedo registrar las vacunas de mis gallos en Galpon?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Sí. Galpon tiene un módulo de vacunación avícola donde puedes registrar para cada ave: la vacuna aplicada (Newcastle, Marek, Gumboro, Bronquitis Infecciosa, Viruela Aviar), la dosis, vía de administración, fecha, número de lote y próxima dosis. El sistema te alerta automáticamente cuando se acercan los refuerzos."
                }
            },
            {
                "@type": "Question",
                "name": "¿Qué incluye el historial médico de un gallo en Galpon?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "El historial médico incluye: todas las vacunaciones con fechas y lotes, tratamientos con medicamentos, desparasitaciones internas y externas, pesajes periódicos, enfermedades diagnosticadas y observaciones veterinarias. Se puede exportar en PDF al momento de vender el animal para documentar su estado sanitario."
                }
            },
            {
                "@type": "Question",
                "name": "¿Qué vacunas necesitan los gallos finos?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Las vacunas esenciales para gallos finos son: Newcastle (cada 3-4 meses), Marek (pollitos de 1 día), Gumboro IBD (pollitos 14-28 días), Bronquitis Infecciosa (cada 4-6 meses) y Viruela Aviar (anual). Con Galpon llevas el registro completo y recibes alertas de próximas dosis."
                }
            }
        ]
    }
    </script>

    {{-- ══ Assets locales (Core Web Vitals / sin CDN) ══ --}}
    <link rel="preload" href="{{ asset('fonts/poppins/poppins-400.ttf') }}" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="{{ asset('img/logo-96.png') }}" as="image">
    <link rel="preload" href="{{ asset('css/welcome.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('fonts/poppins/poppins.css') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

</head>
<body>

    {{-- ══ NAV ══ --}}
    <nav class="topnav" role="navigation" aria-label="Navegación principal">
        <div class="container nav-inner">
            <a href="{{ url('/') }}" class="nav-logo" aria-label="Galpon — Inicio">
                <img src="{{ asset('img/logo-96.png') }}" alt="Logo Galpon" width="36" height="36" decoding="async">
                <span>Galpon</span>
            </a>
            <nav class="nav-links-desktop" aria-label="Menú principal">
                <a href="{{ route('marketplace.index') }}">Marketplace</a>
                <a href="{{ route('plans') }}">Planes y Precios</a>
                <a href="{{ route('faq.index') }}">FAQ</a>
                <a href="{{ route('blog.index') }}">Blog</a>
            </nav>
            <div class="nav-actions">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('panel') }}" class="btn-primary">
                            @include('partials.landing-icon', ['name' => 'dashboard', 'size' => 18]) Mi Panel
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link-text">Iniciar sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary">Registrarse</a>
                        @endif
                    @endauth
                @endif
                <button type="button" class="nav-toggle" id="nav-toggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="nav-drawer">
                    @include('partials.landing-icon', ['name' => 'menu', 'size' => 24, 'class' => 'i-menu'])
                    @include('partials.landing-icon', ['name' => 'close', 'size' => 24, 'class' => 'i-close'])
                </button>
            </div>
        </div>
    </nav>

    <div class="nav-drawer" id="nav-drawer" aria-hidden="true">
        <div class="nav-drawer-backdrop" id="nav-backdrop" tabindex="-1"></div>
        <nav class="nav-drawer-panel" aria-label="Menú móvil">
            <a href="{{ route('marketplace.index') }}">Marketplace</a>
            <a href="{{ route('plans') }}">Planes y Precios</a>
            <a href="{{ route('faq.index') }}">Preguntas Frecuentes</a>
            <a href="{{ route('blog.index') }}">Blog</a>
            <div class="nav-drawer-divider"></div>
            @if (Route::has('login'))
                @auth
                    <a href="{{ route('panel') }}" class="btn-primary nav-drawer-cta">Mi Panel</a>
                @else
                    <a href="{{ route('login') }}">Iniciar sesión</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary nav-drawer-cta">Registrarse gratis</a>
                    @endif
                @endauth
            @endif
        </nav>
    </div>

    {{-- ══ HERO ══ --}}
    <header class="hero" role="banner">
        <img src="{{ asset('img/logo-192.png') }}" alt="Galpon — Software para criaderos de gallos" class="hero-logo" width="90" height="90" fetchpriority="high" decoding="async">
        <div class="hero-badge">
            @include('partials.landing-icon', ['name' => 'verified', 'size' => 16])
            Software avícola profesional
        </div>
        <h1>
            Gestiona tu <span class="text-gradient">criadero de gallos</span><br>
            con inteligencia y precisión
        </h1>
        <p class="lead">
            <strong>Galpon</strong> es el software especializado para criadores de gallos finos y gallinas de raza.
            Pedigree, inventario, ventas, compras y reportes desde tu celular.
            Hecho para Venezuela y toda Latinoamérica.
        </p>
        <div class="hero-actions">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-primary">
                    @include('partials.landing-icon', ['name' => 'rocket_launch', 'size' => 20])
                    Comenzar gratis
                </a>
            @endif
            <a href="{{ route('plans') }}" class="btn-secondary">
                @include('partials.landing-icon', ['name' => 'workspace_premium', 'size' => 20])
                Ver planes
            </a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat">
                <span class="num text-gradient">100%</span>
                <span class="lbl">En español</span>
            </div>
            <div class="hero-stat">
                <span class="num text-gradient">📱</span>
                <span class="lbl">App para el celular</span>
            </div>
            <div class="hero-stat">
                <span class="num text-gradient">∞</span>
                <span class="lbl">Gallos y gallinas</span>
            </div>
            <div class="hero-stat">
                <span class="num text-gradient">Bs·$</span>
                <span class="lbl">Tasa BCV integrada</span>
            </div>
        </div>
    </header>

    {{-- ══ FEATURES ══ --}}
    <section aria-labelledby="features-title">
        <div class="container">
            <div class="section-label">
                @include('partials.landing-icon', ['name' => 'auto_awesome', 'size' => 16])
                Funcionalidades
            </div>
            <h2 class="section-title" id="features-title">
                Todo lo que necesita tu <span class="text-gradient">galpón</span>
            </h2>
            <p class="section-subtitle">
                Desde registrar un pollito hasta exportar el árbol genealógico de tus gallos finos.
                Galpon cubre cada etapa del manejo de tu criadero.
            </p>
            <div class="features-grid">
                <article class="feature-card">
                    <div class="feature-icon" style="background:rgba(251,191,36,.1);">🐓</div>
                    <h3>Registro de Gallos y Gallinas</h3>
                    <p>Ficha completa por ave: nombre, raza, color, peso, fecha de nacimiento, fotos y notas. Compatible con gallos finos, gallos de línea, gallos cruzados y gallinas de raza.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-icon" style="background:rgba(96,165,250,.1);">🌳</div>
                    <h3>Pedigree y Árbol Genealógico</h3>
                    <p>Registra el padre, la madre y todos los hijos de cada gallo. Visualiza el linaje completo y exporta el pedigree en PDF para vender tus aves con certificado de origen.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-icon" style="background:rgba(52,211,153,.1);">📦</div>
                    <h3>Inventario de Medicamentos</h3>
                    <p>Lleva el control de medicamentos, vitaminas, alimentos y suministros de tu galponera. Registra entradas, salidas y mantén siempre el stock actualizado.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-icon" style="background:rgba(248,113,113,.1);">💰</div>
                    <h3>Ventas y Compras</h3>
                    <p>Registra la venta o compra de cada ave con precio en dólares y bolívares. Historial completo de clientes, proveedores y transacciones. Tasa BCV actualizada automáticamente.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-icon" style="background:rgba(167,139,250,.1);">📊</div>
                    <h3>Reportes en PDF</h3>
                    <p>Genera reportes detallados: gallos por raza, ventas del mes, historial de pesaje, rendimiento por línea genética. Exporta en PDF para compartir con compradores o veterinarios.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-icon" style="background:rgba(251,191,36,.1);">🏪</div>
                    <h3>Anuncios y Marketplace</h3>
                    <p>Publica tus gallos y gallinas para que otros criadores los vean. Conecta con compradores en Venezuela, Colombia, México, Perú y toda Latinoamérica desde la misma plataforma.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-icon" style="background:rgba(59,130,246,.1);">📱</div>
                    <h3>App en tu Celular</h3>
                    <p>Instala Galpon en tu celular como si fuera una app normal. Sin descargar nada de una tienda. Compatible con Android e iPhone. Ideal para usarlo en el galpón.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-icon" style="background:rgba(52,211,153,.1);">💱</div>
                    <h3>Tasa BCV Integrada</h3>
                    <p>Precios en bolívares calculados automáticamente con la tasa del BCV. Cada vez que entras al sistema la tasa se actualiza. Tu negocio siempre con la economía al día.</p>
                </article>
                <article class="feature-card">
                    <div class="feature-icon" style="background:rgba(248,113,113,.1);">➕</div>
                    <h3>Galpón Extra como Add-on</h3>
                    <p>Cada cuenta gestiona un criadero. Si necesitas administrar un segundo galpón, puedes activarlo como un servicio adicional por un monto mensual separado. Sin líos, sin mezclas.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- ══ SALUD AVÍCOLA ══ --}}
    <section class="bg-muted" aria-labelledby="salud-title">
        <div class="container">
            <div class="section-label">
                @include('partials.landing-icon', ['name' => 'vaccines', 'size' => 16])
                Salud Avícola Integrada
            </div>
            <h2 class="section-title" id="salud-title">
                Vacunación e <span class="text-gradient">historial médico</span> para tus aves
            </h2>
            <p class="section-subtitle">
                Lleva un control sanitario profesional de cada gallo y gallina: vacunas, tratamientos, desparasitaciones
                y alertas automáticas de próximas dosis. Todo en un mismo lugar.
            </p>
            <div class="features-grid" style="--cols:3;">
                <article class="feature-card">
                    <div class="feature-icon" style="background:rgba(34,197,94,.12);">💉</div>
                    <h3>Registro de Vacunaciones</h3>
                    <p>Registra cada vacuna con el tipo (Newcastle, Marek, Gumboro, Bronquitis Infecciosa, Viruela Aviar), dosis, vía de administración, lote del vial y veterinario responsable.</p>
                    <a href="{{ route('faq.vacunacion') }}" style="font-size:.82rem;color:#3b82f6;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;margin-top:.5rem;">
                        Guía de vacunación →
                    </a>
                </article>
                <article class="feature-card">
                    <div class="feature-icon" style="background:rgba(251,191,36,.12);">📋</div>
                    <h3>Historial Médico por Ave</h3>
                    <p>Cada gallo y gallina tiene su expediente médico digital: vacunas, tratamientos, pesajes, desparasitaciones y observaciones veterinarias. Exportable en PDF al momento de vender.</p>
                    <a href="{{ route('faq.historial-medico') }}" style="font-size:.82rem;color:#3b82f6;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;margin-top:.5rem;">
                        Qué incluye el historial →
                    </a>
                </article>
                <article class="feature-card">
                    <div class="feature-icon" style="background:rgba(239,68,68,.12);">⏰</div>
                    <h3>Alertas de Próximas Dosis</h3>
                    <p>El sistema te notifica automáticamente cuando un gallo o gallina tiene una vacuna o tratamiento próximo. Nunca más olvides una dosis de Newcastle o un refuerzo de Gumboro.</p>
                    <a href="{{ route('faq.vacunacion') }}" style="font-size:.82rem;color:#3b82f6;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;margin-top:.5rem;">
                        Ver calendario de vacunas →
                    </a>
                </article>
            </div>

            {{-- Tarjeta de vacunas destacada --}}
            <div style="margin-top:2.5rem;background:linear-gradient(135deg,#065f46 0%,#059669 60%,#0d9488 100%);border-radius:1.2rem;padding:2rem 2.5rem;display:flex;flex-wrap:wrap;gap:2rem;align-items:center;">
                <div style="flex:1;min-width:260px;">
                    <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.65);margin-bottom:.5rem;">Plan sanitario avícola</div>
                    <h3 style="color:#fff;font-size:1.2rem;font-weight:800;margin-bottom:.6rem;line-height:1.3;">Las vacunas más importantes para gallos finos</h3>
                    <p style="color:rgba(255,255,255,.8);font-size:.88rem;line-height:1.7;margin-bottom:1.25rem;">
                        Newcastle · Marek · Gumboro · Bronquitis Infecciosa · Viruela Aviar · Coriza Infecciosa — gestiona el calendario completo desde Galpon.
                    </p>
                    <a href="{{ route('faq.vacunacion') }}" style="display:inline-flex;align-items:center;gap:.45rem;background:#fff;color:#065f46;border-radius:.7rem;padding:.55rem 1.2rem;font-weight:700;font-size:.88rem;text-decoration:none;">
                        Ver guía de vacunación completa
                    </a>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:.65rem;flex:0 0 auto;">
                    @foreach(['Newcastle', 'Marek', 'Gumboro', 'Bronquitis', 'Viruela Aviar', 'Coriza'] as $v)
                    <span style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);color:#fff;border-radius:2rem;padding:.3rem .85rem;font-size:.78rem;font-weight:600;">{{ $v }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ══ CÓMO FUNCIONA ══ --}}
    <section class="bg-muted" aria-labelledby="howto-title">
        <div class="container">
            <div class="section-label">
                @include('partials.landing-icon', ['name' => 'play_circle', 'size' => 16])
                ¿Cómo funciona?
            </div>
            <h2 class="section-title" id="howto-title">
                Empieza a gestionar tu galpón<br><span class="text-gradient">en minutos</span>
            </h2>
            <p class="section-subtitle">
                No necesitas experiencia en tecnología. Si puedes usar WhatsApp, puedes usar Galpon.
            </p>
            <div class="steps-grid">
                <div class="step-card">
                    <h3>Crea tu cuenta gratis</h3>
                    <p>Regístrate con tu correo en menos de un minuto. Sin tarjeta de crédito. Sin compromisos. Empieza con el plan gratuito.</p>
                </div>
                <div class="step-card">
                    <h3>Registra tus aves</h3>
                    <p>Agrega tus gallos y gallinas con foto, raza, peso y toda la información. El sistema es fácil e intuitivo como llenar un formulario.</p>
                </div>
                <div class="step-card">
                    <h3>Arma el pedigree</h3>
                    <p>Vincula padres, madres e hijos para construir el árbol genealógico. Galpon genera el certificado de pedigree en PDF automáticamente.</p>
                </div>
                <div class="step-card">
                    <h3>Controla ventas e inventario</h3>
                    <p>Registra cada venta y compra. Lleva el inventario de medicamentos. Todos los reportes disponibles en cualquier momento.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ BENEFICIOS ══ --}}
    <section aria-labelledby="benefits-title">
        <div class="container">
            <div class="two-col">
                <div>
                    <div class="section-label">
                        @include('partials.landing-icon', ['name' => 'star', 'size' => 16])
                        ¿Por qué Galpon?
                    </div>
                    <h2 class="section-title" id="benefits-title">
                        El software que <span class="text-gold">los criadores</span><br>estaban esperando
                    </h2>
                    <p class="section-subtitle">
                        Diseñado desde cero para la realidad del criador venezolano y latinoamericano.
                        Sin tecnicismos, sin complicaciones.
                    </p>
                </div>
                <div class="benefits-grid">
                    <div class="benefit-item">
                        <span class="benefit-dot"></span>
                        <p>Registra gallos con foto, raza, peso y árbol genealógico completo en segundos.</p>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-dot"></span>
                        <p>Precios en bolívares y dólares con conversión automática por tasa BCV oficial.</p>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-dot"></span>
                        <p>Úsalo desde el celular en pleno galpón, sin necesidad de computadora.</p>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-dot"></span>
                        <p>Control completo de medicamentos, alimentos y suministros de tu galponera.</p>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-dot"></span>
                        <p>Exporta el pedigree de tus gallos finos en PDF para ventas y exhibiciones.</p>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-dot"></span>
                        <p>Publica y vende tus aves a criadores de Venezuela y Latinoamérica.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ PAÍSES ══ --}}
    <section class="countries-section" aria-labelledby="countries-title">
        <div class="container section-center">
            <div class="section-label">
                @include('partials.landing-icon', ['name' => 'language', 'size' => 16])
                Disponible en toda Latinoamérica
            </div>
            <h2 class="section-title" id="countries-title">
                Para criadores de <span class="text-gradient">Venezuela y el mundo</span>
            </h2>
            <p class="section-subtitle">
                La comunidad de criadores de gallos finos en toda la región ya puede gestionar su galpón
                de forma profesional con Galpon.
            </p>
            <div class="countries-grid" role="list" aria-label="Países disponibles">
                <span class="country-tag" role="listitem">🇻🇪 Venezuela</span>
                <span class="country-tag" role="listitem">🇨🇴 Colombia</span>
                <span class="country-tag" role="listitem">🇲🇽 México</span>
                <span class="country-tag" role="listitem">🇵🇪 Perú</span>
                <span class="country-tag" role="listitem">🇪🇨 Ecuador</span>
                <span class="country-tag" role="listitem">🇩🇴 Rep. Dominicana</span>
                <span class="country-tag" role="listitem">🇵🇷 Puerto Rico</span>
                <span class="country-tag" role="listitem">🇵🇦 Panamá</span>
                <span class="country-tag" role="listitem">🇨🇺 Cuba</span>
                <span class="country-tag" role="listitem">🇧🇴 Bolivia</span>
                <span class="country-tag" role="listitem">🇵🇾 Paraguay</span>
                <span class="country-tag" role="listitem">🇬🇹 Guatemala</span>
                <span class="country-tag" role="listitem">🇸🇻 El Salvador</span>
                <span class="country-tag" role="listitem">🇭🇳 Honduras</span>
                <span class="country-tag" role="listitem">🇧🇷 Brasil</span>
            </div>
        </div>
    </section>

    {{-- ══ HUB DE RECURSOS ══ --}}
    <section aria-labelledby="recursos-title">
        <div class="container">
            <div class="section-label">
                @include('partials.landing-icon', ['name' => 'library_books', 'size' => 16])
                Centro de recursos
            </div>
            <h2 class="section-title" id="recursos-title">
                Aprende sobre <span class="text-gradient">crianza avícola</span>
            </h2>
            <p class="section-subtitle">
                Guías, artículos y respuestas a las preguntas más comunes sobre vacunación, historial médico,
                crianza de gallos finos y manejo del galpón.
            </p>
            <div class="features-grid" style="--cols:2;">

                {{-- FAQ Hub --}}
                <div class="feature-card" style="background:linear-gradient(135deg,#f0fdf4,#fff);border-color:#bbf7d0;">
                    <div class="feature-icon" style="background:rgba(34,197,94,.12);">❓</div>
                    <h3 style="color:#065f46;">Preguntas Frecuentes</h3>
                    <p style="color:#374151;">Resuelve tus dudas sobre el software, vacunación, historial médico y crianza de gallos finos.</p>
                    <div style="display:flex;flex-direction:column;gap:.5rem;margin-top:1rem;">
                        <a href="{{ route('faq.vacunacion') }}" style="font-size:.83rem;color:#065f46;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:.5rem;padding:.45rem .75rem;background:rgba(34,197,94,.08);border-radius:.6rem;" onmouseover="this.style.background='rgba(34,197,94,.16)'" onmouseout="this.style.background='rgba(34,197,94,.08)'">
                            <span style="font-size:1rem;">💉</span> FAQ: Vacunación Avícola en Gallos
                        </a>
                        <a href="{{ route('faq.historial-medico') }}" style="font-size:.83rem;color:#92400e;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:.5rem;padding:.45rem .75rem;background:rgba(251,191,36,.08);border-radius:.6rem;" onmouseover="this.style.background='rgba(251,191,36,.16)'" onmouseout="this.style.background='rgba(251,191,36,.08)'">
                            <span style="font-size:1rem;">📋</span> FAQ: Historial Médico de Aves
                        </a>
                        <a href="{{ route('faq.crianza-gallos') }}" style="font-size:.83rem;color:#7e22ce;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:.5rem;padding:.45rem .75rem;background:rgba(147,51,234,.08);border-radius:.6rem;" onmouseover="this.style.background='rgba(147,51,234,.16)'" onmouseout="this.style.background='rgba(147,51,234,.08)'">
                            <span style="font-size:1rem;">🌱</span> FAQ: Crianza de Gallos Finos
                        </a>
                        <a href="{{ route('faq.index') }}" style="font-size:.82rem;color:#3b82f6;font-weight:700;text-decoration:none;margin-top:.25rem;display:flex;align-items:center;gap:.3rem;">
                            Ver todas las preguntas frecuentes →
                        </a>
                    </div>
                </div>

                {{-- Blog Hub --}}
                <div class="feature-card" style="background:linear-gradient(135deg,#eff6ff,#fff);border-color:#bfdbfe;">
                    <div class="feature-icon" style="background:rgba(59,130,246,.12);">📰</div>
                    <h3 style="color:#1e3a8a;">Blog Avícola</h3>
                    <p style="color:#374151;">Artículos y guías especializadas para criadores de gallos finos en Venezuela y Latinoamérica.</p>
                    <div style="display:flex;flex-direction:column;gap:.6rem;margin-top:1rem;">
                        @php
                            $latestPosts = \App\Models\BlogPost::published()->orderByDesc('published_at')->limit(3)->get();
                        @endphp
                        @forelse($latestPosts as $lp)
                        <a href="{{ route('blog.show', $lp->slug) }}" style="font-size:.83rem;color:#1e3a8a;font-weight:600;text-decoration:none;display:flex;align-items:flex-start;gap:.5rem;padding:.45rem .75rem;background:rgba(59,130,246,.06);border-radius:.6rem;" onmouseover="this.style.background='rgba(59,130,246,.12)'" onmouseout="this.style.background='rgba(59,130,246,.06)'">
                            <span style="font-size:.9rem;flex-shrink:0;margin-top:.05rem;">📄</span>
                            <span>{{ Str::limit($lp->title, 60) }}</span>
                        </a>
                        @empty
                        <p style="font-size:.83rem;color:#60708d;">Pronto encontrarás artículos sobre vacunación, crianza y manejo de gallos finos.</p>
                        @endforelse
                        <a href="{{ route('blog.index') }}" style="font-size:.82rem;color:#3b82f6;font-weight:700;text-decoration:none;margin-top:.25rem;display:flex;align-items:center;gap:.3rem;">
                            Ver todos los artículos del blog →
                        </a>
                    </div>
                </div>

            </div>

            {{-- Temas SEO en chips --}}
            <div style="margin-top:2rem;padding:1.5rem;background:#f8faff;border:1px solid #e5e9f2;border-radius:1rem;">
                <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;margin-bottom:.85rem;">Temas cubiertos en nuestra documentación</div>
                <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                    @php
                    $temas = [
                        ['Vacunación gallos Newcastle', route('faq.vacunacion')],
                        ['Vacuna Marek gallos', route('faq.vacunacion')],
                        ['Vacuna Gumboro avícola', route('faq.vacunacion')],
                        ['Historial médico gallos finos', route('faq.historial-medico')],
                        ['Desparasitación aves', route('faq.historial-medico')],
                        ['Pesaje y control de peso gallos', route('faq.historial-medico')],
                        ['Crianza gallos finos Venezuela', route('faq.crianza-gallos')],
                        ['Pedigree gallos', route('faq.crianza-gallos')],
                        ['Alimentación gallos de pelea', route('faq.crianza-gallos')],
                        ['Linajes Kelso Sweater Hatch', route('faq.crianza-gallos')],
                        ['Plan sanitario criadero', route('faq.vacunacion')],
                        ['Control parásitos externos gallos', route('faq.historial-medico')],
                    ];
                    @endphp
                    @foreach($temas as [$tema, $url])
                    <a href="{{ $url }}" style="background:#fff;border:1px solid #d4dded;color:#374151;border-radius:2rem;padding:.28rem .75rem;font-size:.77rem;font-weight:500;text-decoration:none;transition:all .15s;" onmouseover="this.style.background='#eff6ff';this.style.borderColor='#93c5fd';this.style.color='#1d4ed8'" onmouseout="this.style.background='#fff';this.style.borderColor='#d4dded';this.style.color='#374151'">{{ $tema }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ══ PLANES ══ --}}
    <section aria-labelledby="plans-title">
        <div class="container section-center">
            <div class="section-label">
                @include('partials.landing-icon', ['name' => 'workspace_premium', 'size' => 16])
                Planes y precios
            </div>
            <h2 class="section-title" id="plans-title">
                Elige el plan para tu <span class="text-gradient">galpón</span>
            </h2>
            <p class="section-subtitle">
                Empieza gratis y mejora cuando lo necesites. Sin contratos, sin sorpresas.
            </p>
            <div class="plans-grid">
                <div class="plan-card">
                    <div class="plan-name">Básico</div>
                    <div class="plan-price text-gradient">Gratis <small>/ mes</small></div>
                    <div class="plan-desc">Para criadores que están empezando</div>
                    <ul class="plan-features">
                        <li>Hasta {{ $freeGallos }} gallos y gallinas</li>
                        <li>Pedigree básico</li>
                        <li>Control de inventario</li>
                        <li>Reportes en PDF</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-secondary">Comenzar gratis</a>
                </div>
                <div class="plan-card featured">
                    <div class="plan-badge">Más popular</div>
                    <div class="plan-name">Pro</div>
                    <div class="plan-price text-gradient">${{ $proPrice }} <small>/ mes</small></div>
                    <div class="plan-desc">Para criaderos en crecimiento</div>
                    <ul class="plan-features">
                        <li>Gallos y gallinas sin límite</li>
                        <li>Pedigree completo con PDF</li>
                        <li>Inventario sin restricciones</li>
                        <li>Publicar aves en anuncios</li>
                        <li>Tasa BCV automática</li>
                        <li>Soporte por WhatsApp</li>
                    </ul>
                    <a href="{{ route('plans') }}" class="btn-primary">Ver precios completos</a>
                </div>
                <div class="plan-card">
                    <div class="plan-name">Add-on: Galpón Extra</div>
                    <div class="plan-price text-gradient">+${{ $settings['plans']['extra_galpon_price'] ?? 5 }} <small>/ mes</small></div>
                    <div class="plan-desc">Activa un segundo criadero en tu cuenta Pro</div>
                    <ul class="plan-features">
                        <li>Un galpón adicional independiente</li>
                        <li>Inventario y aves separados</li>
                        <li>Mismo panel, sin mezclas</li>
                        <li>Requiere Plan Pro activo</li>
                        <li>Precio por cada galpón extra</li>
                    </ul>
                    <a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="btn-secondary">Activar por WhatsApp</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ FAQ ══ --}}
    <section class="bg-muted" aria-labelledby="faq-title">
        <div class="container faq-wrap section-center">
            <div class="section-label">
                @include('partials.landing-icon', ['name' => 'help', 'size' => 16])
                Preguntas frecuentes
            </div>
            <h2 class="section-title" id="faq-title">
                Resolvemos tus <span class="text-gradient">dudas</span>
            </h2>
            <div class="faq-list">
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Para qué sirve Galpon exactamente?
                        @include('partials.landing-icon', ['name' => 'add', 'size' => 22, 'class' => 'faq-icon'])
                    </button>
                    <div class="faq-answer">
                        Galpon es un software para gestionar criaderos de gallos finos y gallinas de raza.
                        Te permite registrar cada ave con su ficha completa, construir el árbol genealógico (pedigree),
                        llevar el inventario de medicamentos y alimentos, registrar ventas y compras, registrar vacunaciones
                        y llevar el historial médico de cada ave, y generar reportes en PDF.
                        Todo desde el celular o la computadora.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Puedo usarlo desde el celular sin descargar nada?
                        @include('partials.landing-icon', ['name' => 'add', 'size' => 22, 'class' => 'faq-icon'])
                    </button>
                    <div class="faq-answer">
                        Sí. Galpon funciona como una app web que puedes instalar en tu celular Android o iPhone con un solo toque,
                        directamente desde el navegador. No necesitas ir a ninguna tienda de aplicaciones. Una vez instalada,
                        se abre como cualquier app normal y funciona perfectamente en el galpón.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Cómo maneja los precios en bolívares venezolanos?
                        @include('partials.landing-icon', ['name' => 'add', 'size' => 22, 'class' => 'faq-icon'])
                    </button>
                    <div class="faq-answer">
                        Galpon tiene integrada la tasa oficial del BCV (Banco Central de Venezuela).
                        Cada vez que inicias sesión la tasa se actualiza automáticamente, así tus ventas y compras
                        siempre reflejan el precio correcto en bolívares sin que tengas que calcular nada a mano.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Puedo exportar el pedigree de mis gallos en PDF?
                        @include('partials.landing-icon', ['name' => 'add', 'size' => 22, 'class' => 'faq-icon'])
                    </button>
                    <div class="faq-answer">
                        Sí. Con el plan Pro puedes exportar el árbol genealógico completo de cualquier gallo en un PDF
                        profesional. Ideal para enviárselo a compradores, para exhibiciones o simplemente para llevar
                        el registro de tus líneas genéticas.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Sirve para criadores fuera de Venezuela?
                        @include('partials.landing-icon', ['name' => 'add', 'size' => 22, 'class' => 'faq-icon'])
                    </button>
                    <div class="faq-answer">
                        Completamente. Galpon está disponible para criadores en Colombia, México, Perú, Ecuador,
                        República Dominicana, Puerto Rico, Cuba, Panamá y toda Latinoamérica. El sistema está 100% en
                        español y se adapta a cualquier país. La conversión BCV es una función específica para Venezuela
                        que no afecta el uso en otros países.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Cuánto cuesta el plan Pro?
                        @include('partials.landing-icon', ['name' => 'add', 'size' => 22, 'class' => 'faq-icon'])
                    </button>
                    <div class="faq-answer">
                        El plan Pro cuesta ${{ $proPrice }} al mes (o ${{ $proYearly }} al año si prefieres pagar anual).
                        Incluye gallos y gallinas sin límite, pedigree completo con PDF, publicación de anuncios, inventario sin restricciones y soporte por WhatsApp.
                        Puedes empezar con el plan gratuito (hasta {{ $freeGallos }} aves) sin necesidad de tarjeta.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Puedo registrar las vacunas de mis gallos en Galpon?
                        @include('partials.landing-icon', ['name' => 'add', 'size' => 22, 'class' => 'faq-icon'])
                    </button>
                    <div class="faq-answer">
                        Sí. Galpon tiene un módulo completo de vacunación avícola donde puedes registrar para cada ave:
                        la vacuna aplicada (Newcastle, Marek, Gumboro, Bronquitis Infecciosa, Viruela Aviar, etc.),
                        la dosis, la vía de administración, la fecha de aplicación, el número de lote del vial,
                        la próxima dosis programada y el veterinario responsable. El sistema te alerta
                        automáticamente cuando se acercan las fechas de refuerzo.
                        <a href="{{ route('faq.vacunacion') }}" style="color:#3b82f6;font-weight:600;">Ver guía completa de vacunación →</a>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Qué incluye el historial médico de un gallo en Galpon?
                        @include('partials.landing-icon', ['name' => 'add', 'size' => 22, 'class' => 'faq-icon'])
                    </button>
                    <div class="faq-answer">
                        El historial médico digital de cada ave en Galpon incluye: todas las vacunaciones aplicadas
                        con fechas y lotes, tratamientos con medicamentos y antibióticos, desparasitaciones internas
                        y externas, pesajes periódicos, enfermedades diagnosticadas, procedimientos veterinarios
                        y observaciones del criador. Este historial completo puede exportarse en PDF al momento de
                        vender el animal, lo que aumenta su valor y la confianza del comprador.
                        <a href="{{ route('faq.historial-medico') }}" style="color:#3b82f6;font-weight:600;">Ver qué incluye el historial médico →</a>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Qué vacunas necesitan los gallos finos?
                        @include('partials.landing-icon', ['name' => 'add', 'size' => 22, 'class' => 'faq-icon'])
                    </button>
                    <div class="faq-answer">
                        Las vacunas esenciales para gallos finos son: <strong>Newcastle</strong> (cada 3-4 meses, muy contagiosa y mortal),
                        <strong>Marek</strong> (en pollitos de 1 día, protege contra tumores),
                        <strong>Gumboro (IBD)</strong> (en pollitos, evita inmunosupresión),
                        <strong>Bronquitis Infecciosa</strong> (cada 4-6 meses) y
                        <strong>Viruela Aviar</strong> (anual, especialmente en zonas endémicas).
                        Con Galpon llevas el registro de cada vacuna y recibes alertas antes de los refuerzos.
                        <a href="{{ route('faq.vacunacion') }}" style="color:#3b82f6;font-weight:600;">Ver calendario completo de vacunación →</a>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" aria-expanded="false">
                        ¿Cómo afecta el historial médico al valor de venta de un gallo?
                        @include('partials.landing-icon', ['name' => 'add', 'size' => 22, 'class' => 'faq-icon'])
                    </button>
                    <div class="faq-answer">
                        Un gallo con historial médico documentado — vacunaciones al día, desparasitaciones registradas
                        y sin historial de enfermedades graves — se vende a un precio considerablemente mayor y genera
                        más confianza en el comprador. Con Galpon puedes generar el historial médico completo en PDF
                        y entregarlo junto al animal al momento de la venta. Esto diferencia a los criadores
                        profesionales de los aficionados.
                        <a href="{{ route('faq.historial-medico') }}" style="color:#3b82f6;font-weight:600;">Saber más sobre el historial médico →</a>
                    </div>
                </div>
            </div>
            <div style="text-align:center;margin-top:2rem;">
                <a href="{{ route('faq.index') }}" style="display:inline-flex;align-items:center;gap:.45rem;background:#fff;border:1.5px solid #d4dded;color:#374151;border-radius:.75rem;padding:.65rem 1.5rem;font-size:.88rem;font-weight:600;text-decoration:none;transition:all .15s;" onmouseover="this.style.borderColor='#93c5fd';this.style.color='#1d4ed8'" onmouseout="this.style.borderColor='#d4dded';this.style.color='#374151'">
                    Ver todas las preguntas frecuentes →
                </a>
            </div>
        </div>
    </section>

    {{-- ══ CTA FINAL ══ --}}
    <section class="cta-section" aria-labelledby="cta-title">
        <div class="container">
            <img src="{{ asset('img/logo-192.png') }}" alt="Galpon" class="cta-logo" width="72" height="72" loading="lazy" decoding="async">
            <h2 id="cta-title">
                Lleva tu criadero al <span class="text-gradient">siguiente nivel</span>
            </h2>
            <p>
                Únete a los criadores de gallos finos de Venezuela y Latinoamérica<br>
                que ya gestionan su galpón de forma profesional con Galpon.
            </p>
            <div class="hero-actions">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">
                        @include('partials.landing-icon', ['name' => 'rocket_launch', 'size' => 20])
                        Crear cuenta gratis
                    </a>
                @endif
                <a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="btn-secondary">
                    @include('partials.landing-icon', ['name' => 'chat', 'size' => 20])
                    Escribir por WhatsApp
                </a>
            </div>
        </div>
    </section>

    {{-- ══ FOOTER ══ --}}
    <footer role="contentinfo">
        <div class="container">
            <div class="footer-brand">
                <img src="{{ asset('img/logo-96.png') }}" alt="Galpon" width="28" height="28" loading="lazy" decoding="async">
                <span>Galpon</span>
            </div>
            <p>Software de gestión para criaderos de gallos y gallinas — Venezuela y Latinoamérica</p>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:2rem;text-align:left;margin:2rem 0;padding:2rem 0;border-top:1px solid rgba(255,255,255,.08);border-bottom:1px solid rgba(255,255,255,.08);">
                <div>
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.4);margin-bottom:.85rem;">Plataforma</div>
                    <div style="display:flex;flex-direction:column;gap:.5rem;">
                        <a href="{{ route('register') }}" style="color:rgba(255,255,255,.65);font-size:.84rem;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">Crear cuenta gratis</a>
                        <a href="{{ route('login') }}" style="color:rgba(255,255,255,.65);font-size:.84rem;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">Iniciar sesión</a>
                        <a href="{{ route('plans') }}" style="color:rgba(255,255,255,.65);font-size:.84rem;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">Planes y Precios</a>
                        <a href="{{ route('marketplace.index') }}" style="color:rgba(255,255,255,.65);font-size:.84rem;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">Marketplace</a>
                    </div>
                </div>
                <div>
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.4);margin-bottom:.85rem;">Salud Avícola</div>
                    <div style="display:flex;flex-direction:column;gap:.5rem;">
                        <a href="{{ route('faq.vacunacion') }}" style="color:rgba(255,255,255,.65);font-size:.84rem;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">Vacunación Avícola</a>
                        <a href="{{ route('faq.historial-medico') }}" style="color:rgba(255,255,255,.65);font-size:.84rem;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">Historial Médico de Aves</a>
                        <a href="{{ route('faq.crianza-gallos') }}" style="color:rgba(255,255,255,.65);font-size:.84rem;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">Crianza de Gallos Finos</a>
                        <a href="{{ route('faq.index') }}" style="color:rgba(255,255,255,.65);font-size:.84rem;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">Todas las FAQ</a>
                    </div>
                </div>
                <div>
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.4);margin-bottom:.85rem;">Recursos</div>
                    <div style="display:flex;flex-direction:column;gap:.5rem;">
                        <a href="{{ route('blog.index') }}" style="color:rgba(255,255,255,.65);font-size:.84rem;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">Blog Avícola</a>
                        <a href="{{ route('blog.index') }}?category=vacunacion" style="color:rgba(255,255,255,.65);font-size:.84rem;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">Artículos de Vacunación</a>
                        <a href="{{ route('blog.index') }}?category=crianza" style="color:rgba(255,255,255,.65);font-size:.84rem;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">Guías de Crianza</a>
                        <a href="{{ url('/sitemap.xml') }}" style="color:rgba(255,255,255,.65);font-size:.84rem;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">Sitemap</a>
                    </div>
                </div>
                <div>
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.4);margin-bottom:.85rem;">Soporte</div>
                    <div style="display:flex;flex-direction:column;gap:.5rem;">
                        <a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" style="color:rgba(255,255,255,.65);font-size:.84rem;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">WhatsApp</a>
                        <a href="{{ route('faq.index') }}" style="color:rgba(255,255,255,.65);font-size:.84rem;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,.65)'">Centro de Ayuda</a>
                    </div>
                </div>
            </div>

            <nav class="footer-links" aria-label="Enlaces del pie de página">
                <a href="{{ route('login') }}">Iniciar sesión</a>
                <span class="footer-sep" aria-hidden="true">·</span>
                <a href="{{ route('register') }}">Registrarse</a>
                <span class="footer-sep" aria-hidden="true">·</span>
                <a href="{{ route('plans') }}">Planes</a>
                <span class="footer-sep" aria-hidden="true">·</span>
                <a href="{{ route('faq.index') }}">FAQ</a>
                <span class="footer-sep" aria-hidden="true">·</span>
                <a href="{{ route('blog.index') }}">Blog</a>
                <span class="footer-sep" aria-hidden="true">·</span>
                <a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
            </nav>
        </div>
    </footer>

    {{-- ══ WhatsApp flotante ══ --}}
    <a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="wa-float" aria-label="Contáctanos por WhatsApp">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>
    <div class="wa-tooltip">¿Dudas? Escríbenos 💬</div>

    <script defer>
    (function () {
        var toggle = document.getElementById('nav-toggle');
        var drawer = document.getElementById('nav-drawer');
        var backdrop = document.getElementById('nav-backdrop');

        function setNavOpen(open) {
            if (!toggle || !drawer) return;
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            toggle.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
            drawer.classList.toggle('is-open', open);
            drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
            document.body.classList.toggle('nav-open', open);
        }

        if (toggle) {
            toggle.addEventListener('click', function () {
                setNavOpen(!drawer.classList.contains('is-open'));
            });
        }
        if (backdrop) {
            backdrop.addEventListener('click', function () { setNavOpen(false); });
        }
        drawer && drawer.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () { setNavOpen(false); });
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') setNavOpen(false);
        });

        document.querySelectorAll('.faq-question').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var item = btn.closest('.faq-item');
                var isOpen = item.classList.contains('open');
                document.querySelectorAll('.faq-item.open').forEach(function (i) {
                    i.classList.remove('open');
                    i.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
                });
                if (!isOpen) {
                    item.classList.add('open');
                    btn.setAttribute('aria-expanded', 'true');
                }
            });
        });
    })();
    </script>
</body>
</html>

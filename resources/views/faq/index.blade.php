<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Preguntas Frecuentes — Galpon | Software para Criaderos de Gallos</title>
    <meta name="description" content="Resuelve tus dudas sobre el software Galpon: vacunación avícola, historial médico de aves, crianza de gallos finos, pedigree, inventario y más. FAQ completa para criadores de gallos en Venezuela y Latinoamérica.">
    <meta name="keywords" content="
        preguntas frecuentes gallos, FAQ crianza gallos, dudas vacunación avícola,
        historial médico gallos finos, software gallos preguntas, galpon faq,
        cómo registrar vacunas gallos, control sanitario aves, plan sanitario gallinero,
        crianza gallos finos preguntas, pedigree gallos dudas, software criadero avícola
    ">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
    <link rel="canonical" href="{{ url('/faq') }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/faq') }}">
    <meta property="og:title" content="FAQ — Galpon | Software para Criaderos de Gallos">
    <meta property="og:description" content="Preguntas frecuentes sobre crianza de gallos, vacunación avícola, historial médico y el software Galpon.">
    <meta property="og:image" content="{{ asset('img/logo-512.png') }}">
    <meta property="og:locale" content="es_VE">
    <meta property="og:site_name" content="Galpon">

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "¿Qué vacunas necesitan los gallos finos?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Los gallos finos requieren principalmente: Newcastle (cada 3-4 meses), Marek (en pollitos), Viruela Aviar (una vez al año), Bronquitis Infecciosa y Gumboro (en pollitos). El esquema varía según la región y la edad del ave. Consulta siempre con un veterinario avícola."
                }
            },
            {
                "@type": "Question",
                "name": "¿Qué es el historial médico de un gallo y para qué sirve?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "El historial médico de un gallo es el registro cronológico de todas las vacunaciones, tratamientos, enfermedades y procedimientos veterinarios del ave. Sirve para monitorear su salud, planificar próximas vacunas, evaluar su rendimiento y tener documentación al vender el animal."
                }
            },
            {
                "@type": "Question",
                "name": "¿Cómo llevo el control de vacunación de mis aves con Galpon?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Con Galpon puedes registrar cada vacunación indicando la vacuna aplicada, dosis, vía de administración, fecha, lote y veterinario. El sistema te alerta cuando se acercan las próximas dosis y te muestra el historial completo por ave."
                }
            },
            {
                "@type": "Question",
                "name": "¿Galpon funciona en Venezuela con precios en bolívares?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Sí. Galpon se actualiza automáticamente con la tasa BCV/paralelo para mostrar precios tanto en USD como en bolívares. Está diseñado específicamente para el contexto venezolano, aunque también lo usan criadores en Colombia, México, Perú y toda Latinoamérica."
                }
            }
        ]
    }
    </script>

    <link rel="icon" type="image/png" href="{{ asset('img/logo-96.png') }}">
    <link rel="manifest" href="/manifest.json">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f4f7fb; color: #13203a; }
        .site-header { background: linear-gradient(165deg,rgba(26,38,72,.97),rgba(22,28,52,.97)); border-bottom: 1px solid rgba(255,255,255,.12); }
        .site-shell { max-width: 1100px; margin: 0 auto; padding: 0 1rem; }
        .btn-primary-soft { background: linear-gradient(95deg,#3b82f6,#6d5efc); color:#fff; border:0; border-radius:.7rem; font-weight:600; }
        .btn-primary-soft:hover { opacity:.9; color:#fff; }
        .faq-topic-card { background:#fff; border:1px solid #e5e9f2; border-radius:1rem; padding:1.5rem; transition:all .2s; text-decoration:none; display:block; }
        .faq-topic-card:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(16,39,77,.1); border-color:#bfdbfe; }
        .faq-topic-icon { width:52px; height:52px; border-radius:.85rem; display:flex; align-items:center; justify-content:center; margin-bottom:1rem; }
        .accordion-button { font-weight:600; font-size:.92rem; color:#1a2648; }
        .accordion-button:not(.collapsed) { background:#eff6ff; color:#1d4ed8; }
        .accordion-button:focus { box-shadow:none; }
        .accordion-item { border:1px solid #e5e9f2; border-radius:.75rem!important; margin-bottom:.5rem; overflow:hidden; }
        .accordion-body { font-size:.88rem; color:#374151; line-height:1.7; }
    </style>
</head>
<body>
<header class="site-header py-3">
    <div class="site-shell d-flex align-items-center justify-content-between">
        <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <img src="{{ asset('img/logo.png') }}" alt="Galpon" style="width:44px;height:44px;border-radius:10px;object-fit:cover;">
            <div>
                <div style="font-size:.9rem;font-weight:800;color:#fff;line-height:1.1;">Galpon</div>
                <div style="font-size:.62rem;color:#93c5fd;letter-spacing:.04em;">Software Avícola</div>
            </div>
        </a>
        <nav class="d-flex align-items-center gap-3">
            <a href="{{ url('/') }}" class="text-decoration-none" style="color:rgba(255,255,255,.7);font-size:.85rem;">Inicio</a>
            <a href="{{ url('/blog') }}" class="text-decoration-none" style="color:rgba(255,255,255,.7);font-size:.85rem;">Blog</a>
            <a href="{{ url('/faq') }}" class="text-decoration-none" style="color:#fff;font-size:.85rem;font-weight:700;">FAQ</a>
            <a href="{{ route('login') }}" class="btn btn-primary-soft btn-sm px-3">Ingresar</a>
        </nav>
    </div>
</header>

<section style="background:linear-gradient(135deg,#1a2648,#1e3a8a);padding:3rem 0;">
    <div class="site-shell text-center">
        <div style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:2rem;padding:.35rem .9rem;margin-bottom:1rem;">
            <span class="material-symbols-outlined" style="font-size:.95rem;color:#93c5fd;">help</span>
            <span style="font-size:.78rem;color:#bfdbfe;font-weight:600;">Preguntas Frecuentes</span>
        </div>
        <h1 style="color:#fff;font-size:clamp(1.6rem,4vw,2.4rem);font-weight:800;margin-bottom:.75rem;">
            ¿Cómo podemos ayudarte?
        </h1>
        <p style="color:#93c5fd;font-size:.95rem;max-width:520px;margin:0 auto;">
            Todo lo que necesitas saber sobre crianza de gallos, vacunación avícola, historial médico y el uso de Galpon.
        </p>
    </div>
</section>

<main class="site-shell py-5">
    {{-- Categorías de FAQ --}}
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('faq.vacunacion') }}" class="faq-topic-card">
                <div class="faq-topic-icon" style="background:#f0fdf4;">
                    <span class="material-symbols-outlined" style="font-size:1.5rem;color:#22c55e;">vaccines</span>
                </div>
                <h3 style="font-size:.95rem;font-weight:700;color:#1a2648;margin-bottom:.4rem;">Vacunación Avícola</h3>
                <p style="font-size:.82rem;color:#60708d;margin:0;">Vacunas, esquemas, frecuencias y registro de aplicaciones en gallos y gallinas.</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('faq.historial-medico') }}" class="faq-topic-card">
                <div class="faq-topic-icon" style="background:#fef3c7;">
                    <span class="material-symbols-outlined" style="font-size:1.5rem;color:#d97706;">medical_information</span>
                </div>
                <h3 style="font-size:.95rem;font-weight:700;color:#1a2648;margin-bottom:.4rem;">Historial Médico</h3>
                <p style="font-size:.82rem;color:#60708d;margin:0;">Qué incluir en el historial, cómo interpretarlo y cómo gestionarlo digitalmente.</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('faq.crianza-gallos') }}" class="faq-topic-card">
                <div class="faq-topic-icon" style="background:#fdf4ff;">
                    <span class="material-symbols-outlined" style="font-size:1.5rem;color:#9333ea;">eco</span>
                </div>
                <h3 style="font-size:.95rem;font-weight:700;color:#1a2648;margin-bottom:.4rem;">Crianza de Gallos</h3>
                <p style="font-size:.82rem;color:#60708d;margin:0;">Alimentación, cuidados, pedigree y manejo de gallos finos de alto rendimiento.</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ url('/blog') }}" class="faq-topic-card">
                <div class="faq-topic-icon" style="background:#eff6ff;">
                    <span class="material-symbols-outlined" style="font-size:1.5rem;color:#3b82f6;">article</span>
                </div>
                <h3 style="font-size:.95rem;font-weight:700;color:#1a2648;margin-bottom:.4rem;">Blog & Guías</h3>
                <p style="font-size:.82rem;color:#60708d;margin:0;">Artículos detallados y guías prácticas para criadores avanzados y principiantes.</p>
            </a>
        </div>
    </div>

    {{-- FAQ general rápida --}}
    <div class="row g-4">
        <div class="col-lg-8">
            <h2 style="font-size:1.2rem;font-weight:700;color:#1a2648;margin-bottom:1.25rem;">Preguntas frecuentes generales</h2>

            <div class="accordion" id="faqGeneral">
                @php
                $faqs = [
                    ['¿Qué es Galpon y para qué sirve?', 'Galpon es un software especializado para gestionar criaderos de gallos finos y gallinas de raza. Permite registrar aves con su pedigree completo, controlar inventario de medicamentos y alimentos, gestionar compras y ventas, registrar vacunaciones y llevar el historial médico de cada ave, y generar reportes en PDF. Está diseñado para criadores en Venezuela y toda Latinoamérica.'],
                    ['¿Puedo usar Galpon desde el celular?', 'Sí. Galpon es una Progressive Web App (PWA) que puedes instalar en tu celular Android o iPhone como si fuera una app nativa. Funciona perfectamente desde el galpón. Solo abre el navegador, entra a la plataforma y selecciona "Añadir a pantalla de inicio".'],
                    ['¿Cómo registro las vacunas de mis gallos?', 'En el módulo de Vacunación Avícola puedes registrar cada vacuna indicando el ave, el tipo de vacuna, la dosis, la vía de administración, la fecha de aplicación, la próxima dosis programada, el lote y el veterinario. El sistema te alertará cuando se acerquen las fechas de refuerzo.'],
                    ['¿Qué incluye el historial médico de un ave?', 'El historial médico incluye todas las vacunaciones aplicadas, los tratamientos con medicamentos, los pesajes y eventos registrados, enfermedades diagnosticadas, procedimientos veterinarios y observaciones del criador. Es el expediente digital completo del ave.'],
                    ['¿Galpon maneja precios en bolívares y dólares?', 'Sí. Galpon se actualiza automáticamente con la tasa BCV/paralelo para mostrar precios tanto en USD como en bolívares venezolanos (Bs). Todas las transacciones de compra, venta e inventario se pueden registrar con conversión automática.'],
                    ['¿Puedo manejar varios galpones o criaderos?', 'Sí. Con el plan Pro puedes activar la funcionalidad de múltiples galpones, que te permite gestionar varios criaderos desde una sola cuenta, con inventarios, aves y reportes separados por galpón.'],
                    ['¿Cómo funciona el pedigree en Galpon?', 'El módulo de pedigree te permite vincular padre y madre a cada ave, construyendo automáticamente el árbol genealógico. Puedes consultar el árbol visual de cualquier gallo o gallina y exportarlo en PDF para documentación o venta.'],
                    ['¿Es segura mi información en Galpon?', 'Sí. Cada criador tiene su propio espacio aislado (tenant) con sus datos completamente separados de otros usuarios. La plataforma usa HTTPS, autenticación segura y backups automáticos.'],
                ];
                @endphp

                @foreach($faqs as $i => [$q, $a])
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">
                            {{ $q }}
                        </button>
                    </h3>
                    <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#faqGeneral">
                        <div class="accordion-body">{{ $a }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-4">
            <div style="background:#fff;border:1px solid #e5e9f2;border-radius:1rem;padding:1.5rem;margin-bottom:1.5rem;">
                <h4 style="font-size:.95rem;font-weight:700;color:#1a2648;margin-bottom:1rem;">Explorar por tema</h4>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('faq.vacunacion') }}" class="d-flex align-items-center gap-2 text-decoration-none p-2 rounded-2" style="color:#374151;transition:background .15s;" onmouseenter="this.style.background='#f0f9ff'" onmouseleave="this.style.background=''">
                        <span class="material-symbols-outlined" style="font-size:1rem;color:#22c55e;">vaccines</span>
                        Vacunación Avícola
                    </a>
                    <a href="{{ route('faq.historial-medico') }}" class="d-flex align-items-center gap-2 text-decoration-none p-2 rounded-2" style="color:#374151;transition:background .15s;" onmouseenter="this.style.background='#f0f9ff'" onmouseleave="this.style.background=''">
                        <span class="material-symbols-outlined" style="font-size:1rem;color:#d97706;">medical_information</span>
                        Historial Médico
                    </a>
                    <a href="{{ route('faq.crianza-gallos') }}" class="d-flex align-items-center gap-2 text-decoration-none p-2 rounded-2" style="color:#374151;transition:background .15s;" onmouseenter="this.style.background='#f0f9ff'" onmouseleave="this.style.background=''">
                        <span class="material-symbols-outlined" style="font-size:1rem;color:#9333ea;">eco</span>
                        Crianza de Gallos
                    </a>
                    <a href="{{ url('/blog') }}" class="d-flex align-items-center gap-2 text-decoration-none p-2 rounded-2" style="color:#374151;transition:background .15s;" onmouseenter="this.style.background='#f0f9ff'" onmouseleave="this.style.background=''">
                        <span class="material-symbols-outlined" style="font-size:1rem;color:#3b82f6;">article</span>
                        Blog y Artículos
                    </a>
                </div>
            </div>

            <div style="background:linear-gradient(135deg,#1a2648,#1e3a8a);border-radius:1rem;padding:1.5rem;text-align:center;">
                <span class="material-symbols-outlined d-block mb-2" style="font-size:2rem;color:#93c5fd;">rocket_launch</span>
                <h4 style="color:#fff;font-size:.95rem;font-weight:700;margin-bottom:.5rem;">¿Listo para comenzar?</h4>
                <p style="color:#93c5fd;font-size:.82rem;margin-bottom:1rem;">Crea tu cuenta gratis y empieza a gestionar tu criadero hoy.</p>
                <a href="{{ route('register') }}" class="btn btn-primary-soft btn-sm w-100 py-2">Crear cuenta gratis</a>
                <a href="{{ url('/') }}" class="d-block text-center mt-2" style="color:rgba(255,255,255,.55);font-size:.78rem;text-decoration:none;">Ver todas las funciones</a>
            </div>
        </div>
    </div>
</main>

<footer style="background:#1a2648;color:rgba(255,255,255,.6);padding:2rem 0;margin-top:3rem;">
    <div class="site-shell d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <div style="font-weight:700;color:#fff;margin-bottom:.3rem;">Galpon</div>
            <div style="font-size:.8rem;">Software para criaderos avícolas en Latinoamérica</div>
        </div>
        <nav class="d-flex gap-4" style="font-size:.82rem;">
            <a href="{{ url('/') }}" class="text-decoration-none" style="color:rgba(255,255,255,.6);">Inicio</a>
            <a href="{{ url('/blog') }}" class="text-decoration-none" style="color:rgba(255,255,255,.6);">Blog</a>
            <a href="{{ url('/faq') }}" class="text-decoration-none" style="color:rgba(255,255,255,.6);">FAQ</a>
            <a href="{{ url('/plans') }}" class="text-decoration-none" style="color:rgba(255,255,255,.6);">Planes</a>
        </nav>
    </div>
</footer>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>

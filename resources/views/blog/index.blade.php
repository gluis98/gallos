@php
    use App\Services\SettingsService;
    $settings = SettingsService::get();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Blog — Galpon | Crianza de Gallos, Vacunación Avícola e Historial Médico</title>
    <meta name="description" content="Artículos y guías especializadas sobre crianza de gallos finos, vacunación avícola, historial médico de aves, pedigree y gestión de criaderos en Venezuela y Latinoamérica.">
    <meta name="keywords" content="
        blog crianza gallos, artículos gallos finos, guía vacunación gallos, vacunas avícolas,
        historial médico gallos, calendario vacunación gallinas, enfermedades gallos,
        Newcastle gallos, Marek gallos, Gumboro vacuna, bronquitis infecciosa gallos,
        crianza gallos finos, cuidados gallos de combate, alimentación gallos,
        software gallos, gestión criadero avícola, pedigree gallos, árbol genealógico gallos,
        gallos Venezuela, criadores gallos Latinoamérica, blog avicultura
    ">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
    <link rel="canonical" href="{{ url('/blog') }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/blog') }}">
    <meta property="og:title" content="Blog Galpon — Crianza, Vacunación y Cuidado de Gallos">
    <meta property="og:description" content="Guías y artículos especializados para criadores de gallos finos en Venezuela y Latinoamérica.">
    <meta property="og:image" content="{{ asset('img/logo-512.png') }}">
    <meta property="og:locale" content="es_VE">
    <meta property="og:site_name" content="Galpon">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Blog",
        "name": "Blog Galpon",
        "description": "Artículos y guías sobre crianza de gallos finos, vacunación avícola e historial médico de aves",
        "url": "{{ url('/blog') }}",
        "publisher": {
            "@type": "Organization",
            "name": "Galpon",
            "url": "{{ url('/') }}",
            "logo": "{{ asset('img/logo-512.png') }}"
        },
        "inLanguage": "es"
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
        .site-shell { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        .btn-primary-soft { background: linear-gradient(95deg,#3b82f6,#6d5efc); color:#fff; border:0; border-radius:.7rem; font-weight:600; }
        .btn-primary-soft:hover { opacity:.9; color:#fff; }
        .post-card { background:#fff; border:1px solid #e5e9f2; border-radius:1rem; overflow:hidden; transition:transform .2s,box-shadow .2s; }
        .post-card:hover { transform:translateY(-3px); box-shadow:0 16px 40px rgba(16,39,77,.1); }
        .category-badge { background:#eff6ff; color:#2563eb; border-radius:.5rem; padding:.2rem .65rem; font-size:.75rem; font-weight:600; display:inline-block; }
        .category-badge.vacunacion { background:#f0fdf4; color:#166534; }
        .category-badge.historial-medico { background:#fef3c7; color:#92400e; }
        .category-badge.crianza { background:#fdf4ff; color:#7e22ce; }
        .category-badge.nutricion { background:#fff7ed; color:#9a3412; }
        .category-badge.pedigree { background:#f0f9ff; color:#0369a1; }
        .hero-gradient { background: linear-gradient(135deg,#1a2648 0%,#1e3a8a 50%,#2563eb 100%); }
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
            <a href="{{ url('/') }}" class="text-decoration-none" style="color:rgba(255,255,255,.7);font-size:.85rem;font-weight:500;">Inicio</a>
            <a href="{{ url('/blog') }}" class="text-decoration-none" style="color:#fff;font-size:.85rem;font-weight:700;">Blog</a>
            <a href="{{ url('/faq') }}" class="text-decoration-none" style="color:rgba(255,255,255,.7);font-size:.85rem;font-weight:500;">FAQ</a>
            <a href="{{ url('/marketplace') }}" class="text-decoration-none" style="color:rgba(255,255,255,.7);font-size:.85rem;font-weight:500;">Marketplace</a>
            <a href="{{ route('login') }}" class="btn btn-primary-soft btn-sm px-3">Ingresar</a>
        </nav>
    </div>
</header>

<section class="hero-gradient py-5">
    <div class="site-shell text-center py-3">
        <div style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:2rem;padding:.35rem .9rem;margin-bottom:1rem;">
            <span class="material-symbols-outlined" style="font-size:.95rem;color:#93c5fd;">article</span>
            <span style="font-size:.78rem;color:#bfdbfe;font-weight:600;">Blog Avícola</span>
        </div>
        <h1 style="color:#fff;font-size:clamp(1.6rem,4vw,2.5rem);font-weight:800;margin-bottom:.75rem;">
            Guías y Artículos para Criadores
        </h1>
        <p style="color:#93c5fd;font-size:1rem;max-width:540px;margin:0 auto;">
            Aprende sobre vacunación, historial médico, crianza y manejo de gallos finos y gallinas de raza.
        </p>
    </div>
</section>

<main class="site-shell py-5">
    @if($posts->isEmpty())
    <div class="text-center py-5" style="color:#94a3b8;">
        <span class="material-symbols-outlined d-block mb-3" style="font-size:3rem;">article</span>
        <p>No hay artículos publicados aún. ¡Vuelve pronto!</p>
        <a href="{{ url('/') }}" class="btn btn-primary-soft mt-2">Ir al inicio</a>
    </div>
    @else
    <div class="row g-4">
        @foreach($posts as $post)
        <div class="col-md-6 col-lg-4">
            <article class="post-card h-100">
                @if($post->featured_image)
                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                    style="width:100%;height:180px;object-fit:cover;" loading="lazy">
                @else
                <div style="width:100%;height:140px;background:linear-gradient(135deg,#e0e7ff,#dbeafe);display:flex;align-items:center;justify-content:center;">
                    <span class="material-symbols-outlined" style="font-size:2.5rem;color:#6366f1;opacity:.4;">article</span>
                </div>
                @endif
                <div class="p-4">
                    <span class="category-badge {{ $post->category }} mb-2 d-inline-block">
                        {{ ucfirst(str_replace('-', ' ', $post->category)) }}
                    </span>
                    <h2 style="font-size:1rem;font-weight:700;color:#1a2648;line-height:1.4;margin-bottom:.5rem;">
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none" style="color:inherit;">
                            {{ $post->title }}
                        </a>
                    </h2>
                    @if($post->excerpt)
                    <p style="font-size:.84rem;color:#60708d;line-height:1.6;margin-bottom:.75rem;">
                        {{ Str::limit($post->excerpt, 120) }}
                    </p>
                    @endif
                    <div class="d-flex align-items-center justify-content-between mt-auto">
                        <span style="font-size:.74rem;color:#94a3b8;">
                            {{ $post->published_at?->format('d M Y') }}
                        </span>
                        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-sm btn-primary-soft px-3">
                            Leer más
                        </a>
                    </div>
                </div>
            </article>
        </div>
        @endforeach
    </div>

    @if($posts->hasPages())
    <div class="d-flex justify-content-center mt-5">
        {{ $posts->links() }}
    </div>
    @endif
    @endif
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
            <a href="{{ url('/marketplace') }}" class="text-decoration-none" style="color:rgba(255,255,255,.6);">Marketplace</a>
            <a href="{{ url('/plans') }}" class="text-decoration-none" style="color:rgba(255,255,255,.6);">Planes</a>
        </nav>
    </div>
</footer>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>

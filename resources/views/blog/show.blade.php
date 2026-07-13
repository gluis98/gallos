<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $post->meta_title ?: $post->title }} — Blog Galpon</title>
    <meta name="description" content="{{ $post->meta_description ?: Str::limit(strip_tags($post->excerpt ?: $post->content), 160) }}">
    @if($post->keywords)
    <meta name="keywords" content="{{ $post->keywords }}">
    @endif
    <meta name="author" content="{{ $post->author->name ?? 'Galpon' }}">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
    <link rel="canonical" href="{{ url('/blog/' . $post->slug) }}">

    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url('/blog/' . $post->slug) }}">
    <meta property="og:title" content="{{ $post->meta_title ?: $post->title }}">
    <meta property="og:description" content="{{ $post->meta_description ?: Str::limit(strip_tags($post->content), 160) }}">
    @if($post->featured_image)
    <meta property="og:image" content="{{ $post->featured_image }}">
    @else
    <meta property="og:image" content="{{ asset('img/logo-512.png') }}">
    @endif
    <meta property="og:locale" content="es_VE">
    <meta property="og:site_name" content="Galpon">
    <meta property="article:published_time" content="{{ $post->published_at?->toIso8601String() }}">
    <meta property="article:author" content="{{ $post->author->name ?? 'Galpon' }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->meta_title ?: $post->title }}">
    <meta name="twitter:description" content="{{ $post->meta_description ?: Str::limit(strip_tags($post->content), 160) }}">

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "BlogPosting",
        "headline": "{{ addslashes($post->title) }}",
        "description": "{{ addslashes($post->meta_description ?: Str::limit(strip_tags($post->content), 160)) }}",
        "datePublished": "{{ $post->published_at?->toIso8601String() }}",
        "dateModified": "{{ $post->updated_at->toIso8601String() }}",
        "author": {
            "@type": "Person",
            "name": "{{ $post->author->name ?? 'Galpon' }}"
        },
        "publisher": {
            "@type": "Organization",
            "name": "Galpon",
            "url": "{{ url('/') }}",
            "logo": "{{ asset('img/logo-512.png') }}"
        },
        "url": "{{ url('/blog/' . $post->slug) }}",
        "mainEntityOfPage": "{{ url('/blog/' . $post->slug) }}"
        @if($post->featured_image)
        ,"image": "{{ $post->featured_image }}"
        @endif
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
        .article-content h2 { font-size:1.35rem; font-weight:700; color:#1a2648; margin:2rem 0 .75rem; }
        .article-content h3 { font-size:1.1rem; font-weight:700; color:#1e3a8a; margin:1.5rem 0 .5rem; }
        .article-content p  { color:#374151; line-height:1.8; margin-bottom:1rem; }
        .article-content ul, .article-content ol { color:#374151; line-height:1.8; padding-left:1.5rem; margin-bottom:1rem; }
        .article-content li { margin-bottom:.35rem; }
        .article-content strong { color:#1a2648; }
        .article-content blockquote { border-left:4px solid #3b82f6; background:#eff6ff; padding:1rem 1.25rem; border-radius:0 .7rem .7rem 0; margin:1.5rem 0; color:#1e3a8a; font-style:italic; }
        .article-content a { color:#3b82f6; }
        .category-badge { background:#eff6ff; color:#2563eb; border-radius:.5rem; padding:.2rem .65rem; font-size:.75rem; font-weight:600; display:inline-block; }
        .category-badge.vacunacion { background:#f0fdf4; color:#166534; }
        .category-badge.historial-medico { background:#fef3c7; color:#92400e; }
        .category-badge.crianza { background:#fdf4ff; color:#7e22ce; }
        .category-badge.nutricion { background:#fff7ed; color:#9a3412; }
        .related-card { background:#fff; border:1px solid #e5e9f2; border-radius:.9rem; overflow:hidden; transition:box-shadow .2s; }
        .related-card:hover { box-shadow:0 8px 24px rgba(16,39,77,.1); }
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
            <a href="{{ url('/blog') }}" class="text-decoration-none" style="color:#fff;font-size:.85rem;font-weight:700;">Blog</a>
            <a href="{{ url('/faq') }}" class="text-decoration-none" style="color:rgba(255,255,255,.7);font-size:.85rem;">FAQ</a>
            <a href="{{ route('login') }}" class="btn btn-primary-soft btn-sm px-3">Ingresar</a>
        </nav>
    </div>
</header>

<main class="py-5">
    <div class="site-shell">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size:.82rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-primary">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/blog') }}" class="text-decoration-none text-primary">Blog</a></li>
                <li class="breadcrumb-item active" style="color:#60708d;">{{ Str::limit($post->title, 50) }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            {{-- Artículo principal --}}
            <div class="col-lg-8">
                <article>
                    {{-- Encabezado --}}
                    <header class="mb-4">
                        <span class="category-badge {{ $post->category }} mb-3 d-inline-block">
                            {{ ucfirst(str_replace('-', ' ', $post->category)) }}
                        </span>
                        <h1 style="font-size:clamp(1.4rem,3.5vw,2rem);font-weight:800;color:#1a2648;line-height:1.35;margin-bottom:1rem;">
                            {{ $post->title }}
                        </h1>
                        <div class="d-flex align-items-center gap-3" style="color:#94a3b8;font-size:.82rem;">
                            <span class="d-flex align-items-center gap-1">
                                <span class="material-symbols-outlined" style="font-size:.9rem;">person</span>
                                {{ $post->author->name ?? 'Galpon' }}
                            </span>
                            <span class="d-flex align-items-center gap-1">
                                <span class="material-symbols-outlined" style="font-size:.9rem;">calendar_today</span>
                                {{ $post->published_at?->format('d \d\e F \d\e Y') }}
                            </span>
                        </div>
                    </header>

                    @if($post->featured_image)
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                        class="rounded-3 w-100 mb-4" style="max-height:400px;object-fit:cover;" loading="lazy">
                    @endif

                    {{-- Contenido --}}
                    <div class="article-content" style="background:#fff;border:1px solid #e5e9f2;border-radius:1rem;padding:2rem;">
                        {!! $post->content !!}
                    </div>

                    {{-- CTA al final --}}
                    <div class="mt-4 p-4 rounded-3 text-center" style="background:linear-gradient(135deg,#1a2648,#1e3a8a);">
                        <h3 style="color:#fff;font-size:1.1rem;font-weight:700;margin-bottom:.5rem;">
                            Gestiona tu criadero con Galpon
                        </h3>
                        <p style="color:#93c5fd;font-size:.88rem;margin-bottom:1rem;">
                            Registra vacunaciones, lleva el historial médico de tus aves y mucho más.
                        </p>
                        <a href="{{ route('register') }}" class="btn btn-primary-soft px-4">
                            Probar gratis — Sin tarjeta
                        </a>
                    </div>
                </article>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="sticky-top" style="top:1.5rem;">
                    {{-- Sobre Galpon --}}
                    <div class="section-card p-4 mb-4" style="background:#fff;border:1px solid #e5e9f2;border-radius:1rem;">
                        <img src="{{ asset('img/logo.png') }}" alt="Galpon" style="width:48px;height:48px;border-radius:.7rem;object-fit:cover;margin-bottom:.75rem;">
                        <h4 style="font-size:.95rem;font-weight:700;color:#1a2648;margin-bottom:.4rem;">Galpon — Software Avícola</h4>
                        <p style="font-size:.82rem;color:#60708d;line-height:1.6;margin-bottom:1rem;">
                            Gestiona tu criadero de gallos finos: pedigree, vacunaciones, historial médico, inventario, ventas y más.
                        </p>
                        <a href="{{ route('register') }}" class="btn btn-primary-soft btn-sm w-100 d-block text-center py-2">
                            Crear cuenta gratis
                        </a>
                    </div>

                    {{-- FAQ rápida --}}
                    <div style="background:#fff;border:1px solid #e5e9f2;border-radius:1rem;padding:1.25rem;margin-bottom:1.5rem;">
                        <h4 style="font-size:.88rem;font-weight:700;color:#1a2648;margin-bottom:1rem;">Más información</h4>
                        <div class="d-flex flex-column gap-2" style="font-size:.83rem;">
                            <a href="{{ route('faq.vacunacion') }}" class="text-decoration-none d-flex align-items-center gap-2" style="color:#3b82f6;">
                                <span class="material-symbols-outlined" style="font-size:.9rem;">vaccines</span>
                                FAQ: Vacunación Avícola
                            </a>
                            <a href="{{ route('faq.historial-medico') }}" class="text-decoration-none d-flex align-items-center gap-2" style="color:#3b82f6;">
                                <span class="material-symbols-outlined" style="font-size:.9rem;">medical_information</span>
                                FAQ: Historial Médico
                            </a>
                            <a href="{{ route('faq.crianza-gallos') }}" class="text-decoration-none d-flex align-items-center gap-2" style="color:#3b82f6;">
                                <span class="material-symbols-outlined" style="font-size:.9rem;">eco</span>
                                FAQ: Crianza de Gallos
                            </a>
                        </div>
                    </div>

                    {{-- Artículos relacionados --}}
                    @if($related->isNotEmpty())
                    <div>
                        <h4 style="font-size:.88rem;font-weight:700;color:#1a2648;margin-bottom:.75rem;">Artículos relacionados</h4>
                        <div class="d-flex flex-column gap-3">
                            @foreach($related as $rel)
                            <a href="{{ route('blog.show', $rel->slug) }}" class="related-card text-decoration-none d-flex gap-3 p-3">
                                @if($rel->featured_image)
                                <img src="{{ $rel->featured_image }}" alt="{{ $rel->title }}" style="width:56px;height:56px;object-fit:cover;border-radius:.5rem;flex-shrink:0;">
                                @else
                                <div style="width:56px;height:56px;background:#eff6ff;border-radius:.5rem;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                    <span class="material-symbols-outlined" style="font-size:1.2rem;color:#6366f1;">article</span>
                                </div>
                                @endif
                                <div>
                                    <div style="font-size:.82rem;font-weight:600;color:#1a2648;line-height:1.3;">{{ Str::limit($rel->title, 60) }}</div>
                                    <div style="font-size:.73rem;color:#94a3b8;margin-top:.2rem;">{{ $rel->published_at?->format('d/m/Y') }}</div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Catálogo de gallos — {{ $tenant->name ?? 'Criadero' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
</head>
<body class="cat-page">
    <header class="cat-header">
        <h1>{{ $tenant->name ?? 'Catálogo de gallos' }}</h1>
        <p>Gallos disponibles — información y fotografías</p>
        <span class="cat-badge">{{ $gallos->count() }} {{ $gallos->count() === 1 ? 'gallo' : 'gallos' }} en catálogo</span>
    </header>

    <main class="cat-main">
        @if ($gallos->isEmpty())
            <div class="cat-empty">
                <p style="font-size:2.5rem;margin:0 0 .75rem;">🐓</p>
                <h2 style="margin:0 0 .5rem;font-size:1.1rem;">Sin gallos disponibles</h2>
                <p style="margin:0;color:var(--cat-muted);font-size:.9rem;">El criadero no tiene gallos publicados en este momento.</p>
            </div>
        @else
            <div class="cat-grid">
                @foreach ($gallos as $gallo)
                    @php
                        $images = $gallo->gallos_imagenes->map(fn ($img) => asset('files/gallos/'.$gallo->id.'/'.$img->imagen))->values()->all();
                        if (empty($images)) {
                            $images = [asset('img/avatar.png')];
                        }
                        $hijo = $gallo->gallos_hijos;
                    @endphp
                    <article class="cat-card" data-card-id="{{ $gallo->id }}">
                        <div class="cat-gallery" data-images='@json($images)'>
                            <span class="cat-placa">{{ $gallo->placa }}</span>
                            @if (count($images) > 1)
                                <button type="button" class="cat-gallery-btn prev" aria-label="Foto anterior">‹</button>
                                <button type="button" class="cat-gallery-btn next" aria-label="Foto siguiente">›</button>
                                <div class="cat-gallery-nav" aria-hidden="true"></div>
                            @endif
                            <img src="{{ $images[0] }}" alt="Foto {{ $gallo->placa }}" class="cat-gallery-img" loading="lazy">
                        </div>
                        <div class="cat-body">
                            <h2 class="cat-title">{{ $gallo->nombre ?: 'Sin nombre' }}</h2>
                            <p class="cat-subtitle">
                                @if ($gallo->estatus)
                                    Estatus: <strong>{{ $gallo->estatus }}</strong>
                                @endif
                                @if ($gallo->color)
                                    · Color: <strong>{{ $gallo->color }}</strong>
                                @endif
                            </p>
                            <div class="cat-details">
                                @if ($gallo->marca_nacimiento || $gallo->marca)
                                    <div class="cat-row"><span>Marca</span><span>{{ $gallo->marca_nacimiento ?: $gallo->marca }}</span></div>
                                @endif
                                @if ($gallo->marca_federacion || $gallo->anillo)
                                    <div class="cat-row"><span>Anillo / Federación</span><span>{{ $gallo->anillo ?: $gallo->marca_federacion }}</span></div>
                                @endif
                                @if ($gallo->color_alternativo)
                                    <div class="cat-row"><span>Color alt.</span><span>{{ $gallo->color_alternativo }}</span></div>
                                @endif
                                @if ($gallo->cresta)
                                    <div class="cat-row"><span>Cresta</span><span>{{ $gallo->cresta }}</span></div>
                                @endif
                                @if ($gallo->fecha_nacimiento)
                                    <div class="cat-row"><span>Nacimiento</span><span>{{ $gallo->fecha_nacimiento }}</span></div>
                                @endif
                                @if ($gallo->luna)
                                    <div class="cat-row"><span>Luna</span><span>{{ $gallo->luna }}</span></div>
                                @endif
                                @if ($gallo->peleas)
                                    <div class="cat-row"><span>Peleas</span><span>{{ $gallo->peleas }}</span></div>
                                @endif
                            </div>
                            @if ($hijo && ($hijo->padre || $hijo->madre))
                                <div class="cat-lineage">
                                    <strong>Linaje:</strong>
                                    @if ($hijo->padre)
                                        Padre {{ $hijo->padre->placa }}{{ $hijo->padre->nombre ? ' ('.$hijo->padre->nombre.')' : '' }}
                                    @endif
                                    @if ($hijo->padre && $hijo->madre) · @endif
                                    @if ($hijo->madre)
                                        Madre {{ $hijo->madre->placa }}{{ $hijo->madre->nombre ? ' ('.$hijo->madre->nombre.')' : '' }}
                                    @endif
                                </div>
                            @endif
                            @if ($gallo->observaciones)
                                <div class="cat-notes">{{ $gallo->observaciones }}</div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </main>

    <footer class="cat-footer">
        Catálogo generado por Galpon · Solo consulta
    </footer>

    <div class="cat-lightbox" id="cat-lightbox" role="dialog" aria-modal="true" aria-label="Vista ampliada">
        <button type="button" class="cat-lightbox-close" id="cat-lightbox-close" aria-label="Cerrar">✕</button>
        <img src="" alt="" id="cat-lightbox-img">
    </div>

    <script>
    (function () {
        document.querySelectorAll('.cat-gallery').forEach(function (gallery) {
            var images = JSON.parse(gallery.dataset.images || '[]');
            if (!images.length) return;
            var idx = 0;
            var img = gallery.querySelector('.cat-gallery-img');
            var nav = gallery.querySelector('.cat-gallery-nav');
            var prev = gallery.querySelector('.cat-gallery-btn.prev');
            var next = gallery.querySelector('.cat-gallery-btn.next');

            function show(i) {
                idx = (i + images.length) % images.length;
                img.src = images[idx];
                if (nav) {
                    nav.innerHTML = images.map(function (_, n) {
                        return '<button type="button" class="cat-dot' + (n === idx ? ' active' : '') + '" data-i="' + n + '"></button>';
                    }).join('');
                    nav.querySelectorAll('.cat-dot').forEach(function (dot) {
                        dot.addEventListener('click', function () { show(parseInt(dot.dataset.i, 10)); });
                    });
                }
            }

            if (prev) prev.addEventListener('click', function () { show(idx - 1); });
            if (next) next.addEventListener('click', function () { show(idx + 1); });
            if (images.length > 1) show(0);

            img.addEventListener('click', function () {
                var lb = document.getElementById('cat-lightbox');
                var lbImg = document.getElementById('cat-lightbox-img');
                lbImg.src = images[idx];
                lbImg.alt = img.alt;
                lb.classList.add('open');
            });
        });

        var lb = document.getElementById('cat-lightbox');
        document.getElementById('cat-lightbox-close').addEventListener('click', function () { lb.classList.remove('open'); });
        lb.addEventListener('click', function (e) { if (e.target === lb) lb.classList.remove('open'); });
    })();
    </script>
</body>
</html>

@php
    use App\Support\MarketplacePresenter;

    $ave       = $pub->ave;
    $isGallo   = MarketplacePresenter::isGallo($ave, $pub->ave_type);
    $typeLabel = MarketplacePresenter::aveLabel($isGallo);
    $images    = MarketplacePresenter::aveImages($ave, $isGallo);
    $nombre    = $ave?->nombre ?? 'Sin nombre';
    $pageTitle = "{$typeLabel}: {$nombre} — Marketplace Galpon";
    $pageDesc  = trim("{$typeLabel} {$nombre}" .
        ($ave?->color ? " color {$ave->color}" : '') .
        ($ave?->marca_nacimiento ? " línea {$ave->marca_nacimiento}" : '') .
        ". Precio \${$pub->precio}. Compra gallos finos y gallinas de raza en Galpon.");
    $canonical = route('marketplace.show', $pub->id);
    $ogImage   = $images->first() ?? asset('img/logo-512.png');
    $whatsapp  = env('WHATSAPP_SUPPORT_URL', 'https://wa.me/584120000000');
    $vendedor  = $pub->tenant_id ?? 'Criador';
    $initials  = strtoupper(substr($vendedor, 0, 1));
    $repScore  = round($avgRating, 1);
    $repPercent = ($repScore / 5) * 100;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:site_name" content="Galpon">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <meta name="theme-color" content="#1a2648">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": @json("{$typeLabel}: {$nombre}"),
        "description": @json($pageDesc),
        "image": @json($images->all()),
        "url": @json($canonical),
        "offers": {
            "@type": "Offer",
            "price": "{{ number_format($pub->precio ?? 0, 2, '.', '') }}",
            "priceCurrency": "USD",
            "availability": "https://schema.org/InStock",
            "url": @json($canonical)
        }
    }
    </script>
    <style>
        *, ::before, ::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background: #f4f7fb; color: #13203a; }
        nav.topnav { position:fixed;top:0;left:0;right:0;z-index:200;background:rgba(7,9,15,.92);border-bottom:1px solid rgba(255,255,255,.07);backdrop-filter:blur(14px);padding:.75rem 0; }
        .nav-inner { max-width:1280px;margin:0 auto;padding:0 1.25rem;display:flex;align-items:center;justify-content:space-between; }
        .nav-logo { display:flex;align-items:center;gap:.65rem;text-decoration:none; }
        .nav-logo img { width:36px;height:36px;border-radius:9px;object-fit:cover; }
        .nav-logo span { font-weight:800;font-size:1.1rem;color:#fff; }
        .nav-links a { color:rgba(255,255,255,.65);font-weight:500;font-size:.85rem;text-decoration:none;padding:.38rem .7rem;border-radius:.55rem;transition:all .2s; }
        .nav-links a:hover, .nav-links a.active { color:#fff;background:rgba(255,255,255,.08); }
        .page-shell { max-width:900px;margin:0 auto;padding:5.5rem 1.25rem 3rem; }
        .breadcrumb { font-size:.8rem;color:#60708d;margin-bottom:1rem; }
        .breadcrumb a { color:#3b82f6;text-decoration:none; }
        .detail-card { background:#fff;border:1px solid #e5e9f2;border-radius:1.3rem;padding:1.5rem;box-shadow:0 4px 24px rgba(16,39,77,.06); }
        .detail-gallery img { width:100%;max-height:380px;object-fit:cover;border-radius:.85rem;margin-bottom:.75rem; }
        .thumb-row { display:flex;gap:.5rem;overflow-x:auto;margin-bottom:1.25rem; }
        .thumb-row img { height:70px;width:90px;object-fit:cover;border-radius:.6rem;flex-shrink:0; }
        .detail-price-box { background:linear-gradient(135deg,#1a2648,#2d4278);border-radius:1rem;padding:1.1rem 1.25rem;margin-bottom:1.25rem;display:flex;flex-wrap:wrap;gap:1rem;align-items:center; }
        .detail-price-usd { font-size:1.8rem;font-weight:900;color:#fff; }
        .detail-price-bs { font-size:.88rem;color:rgba(255,255,255,.65); }
        .badge-dest { background:rgba(245,158,11,.25);color:#fde68a;font-size:.72rem;font-weight:700;border-radius:999px;padding:.2rem .65rem; }
        .seller-box { background:#f8faff;border:1px solid #e8eef8;border-radius:.9rem;padding:1rem 1.1rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:1rem;flex-wrap:wrap; }
        .seller-big-avatar { width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#dbeafe,#ede9fe);display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:800;color:#3b5ea6;flex-shrink:0; }
        .seller-info .name { font-size:.95rem;font-weight:700;color:#0f1830; }
        .seller-info .sub { font-size:.75rem;color:#60708d;margin-top:.1rem; }
        .rep-meter { display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-top:.35rem; }
        .stars-row span { color:#f59e0b;font-size:.9rem; }
        .stars-row span.empty { color:#d1d5db; }
        .rep-bar-wrap { height:6px;width:80px;background:#e5e9f2;border-radius:99px;overflow:hidden; }
        .rep-bar { height:100%;background:linear-gradient(90deg,#f59e0b,#f97316);border-radius:99px; }
        .rep-label { font-size:.72rem;color:#60708d;font-weight:600; }
        .detail-grid { display:grid;grid-template-columns:1fr 1fr;gap:.6rem .85rem;margin-bottom:1.25rem; }
        .detail-field label { display:block;font-size:.68rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#60708d;margin-bottom:.15rem; }
        .detail-field span { font-size:.9rem;font-weight:600;color:#13203a; }
        .detail-actions { display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1.5rem; }
        .btn-comprar { flex:1;min-width:140px;background:linear-gradient(135deg,#3b82f6,#7c3aed);color:#fff;border:none;border-radius:.8rem;padding:.75rem 1rem;font-family:'Poppins',sans-serif;font-size:.95rem;font-weight:700;cursor:pointer; }
        .btn-preguntar { flex:1;min-width:120px;background:#f3f6fd;color:#1a2648;border:1.5px solid #dce5f3;border-radius:.8rem;padding:.75rem 1rem;font-family:'Poppins',sans-serif;font-size:.92rem;font-weight:600;cursor:pointer; }
        .section-box { background:#f8faff;border:1px solid #e8eef8;border-radius:.9rem;padding:1.25rem;margin-top:1.25rem; }
        .section-box h3 { font-size:.95rem;font-weight:700;color:#374151;margin-bottom:1rem; }
        .form-row { display:grid;grid-template-columns:1fr 1fr;gap:.75rem; }
        .form-field { display:flex;flex-direction:column;gap:.3rem;margin-bottom:.75rem; }
        .form-field label { font-size:.75rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:#60708d; }
        .form-input { height:42px;border:1.5px solid #dce5f3;border-radius:.7rem;padding:0 .85rem;font-family:'Poppins',sans-serif;font-size:.9rem;color:#13203a;outline:none; }
        textarea.form-input { height:100px;padding:.75rem .85rem;resize:vertical; }
        .btn-confirm { width:100%;background:linear-gradient(135deg,#3b82f6,#7c3aed);color:#fff;border:none;border-radius:.8rem;padding:.85rem;font-family:'Poppins',sans-serif;font-size:.97rem;font-weight:700;cursor:pointer; }
        .disclaimer-box { background:#fff8e6;border:1px solid rgba(245,158,11,.3);border-radius:.8rem;padding:.85rem 1rem;margin-bottom:1.25rem;font-size:.82rem;color:#92400e;line-height:1.65; }
        .qa-item { background:#fff;border:1px solid #e8eef8;border-radius:.75rem;padding:.8rem .9rem;margin-bottom:.55rem; }
        .qa-q { font-size:.83rem;font-weight:600;color:#1a2648; }
        .qa-meta { font-size:.72rem;color:#60708d;margin-top:.25rem; }
        .alert-ok { background:#dcfce7;border:1px solid #86efac;color:#166534;border-radius:.65rem;padding:.65rem 1rem;font-size:.88rem;font-weight:600;margin-bottom:1rem; }
        @media(max-width:640px) { .detail-grid, .form-row { grid-template-columns:1fr; } .detail-actions { flex-direction:column; } }
    </style>
</head>
<body>

<nav class="topnav">
    <div class="nav-inner">
        <a href="{{ url('/') }}" class="nav-logo">
            <img src="{{ asset('img/logo.png') }}" alt="Galpon" width="36" height="36">
            <span>Galpon</span>
        </a>
        <div class="nav-links">
            <a href="{{ route('marketplace.index') }}" class="active">Marketplace</a>
            <a href="{{ route('plans') }}">Planes</a>
        </div>
    </div>
</nav>

<div class="page-shell">
    <div class="breadcrumb">
        <a href="{{ route('marketplace.index') }}">Marketplace</a> / {{ $typeLabel }}: {{ $nombre }}
    </div>

    @if(session('ok'))
        <div class="alert-ok">{{ session('ok') }}</div>
    @endif

    <article class="detail-card">
        <p style="font-size:.68rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#60a5fa;margin-bottom:.35rem;">{{ $typeLabel }}</p>
        <h1 style="font-size:clamp(1.4rem,3vw,1.9rem);font-weight:800;color:#0f1830;margin-bottom:1.25rem;">{{ $nombre }}</h1>

        @if($images->isNotEmpty())
            <div class="detail-gallery">
                <img src="{{ $images->first() }}" alt="{{ $nombre }}" loading="eager">
            </div>
            @if($images->count() > 1)
                <div class="thumb-row">
                    @foreach($images->slice(1) as $thumb)
                        <img src="{{ $thumb }}" alt="{{ $nombre }}" loading="lazy">
                    @endforeach
                </div>
            @endif
        @else
            <div style="text-align:center;font-size:5rem;margin-bottom:1.25rem;">{{ $isGallo ? '🐓' : '🐔' }}</div>
        @endif

        <div class="detail-price-box">
            <div>
                <div class="detail-price-usd">${{ number_format($pub->precio ?? 0, 2) }}</div>
                @if($rate > 0)
                    <div class="detail-price-bs">≈ Bs. {{ number_format(($pub->precio ?? 0) * $rate, 2) }}</div>
                @endif
            </div>
            @if($pub->destacado)
                <span class="badge-dest">⭐ Destacado</span>
            @endif
        </div>

        <div class="seller-box">
            <div class="seller-big-avatar">{{ $initials }}</div>
            <div class="seller-info">
                <div class="name">{{ $vendedor }}</div>
                <div class="sub">{{ $totalSales }} venta{{ $totalSales !== 1 ? 's' : '' }} · {{ $totalPubs }} publicación{{ $totalPubs !== 1 ? 'es' : '' }} activa{{ $totalPubs !== 1 ? 's' : '' }}</div>
                <div class="rep-meter">
                    <div class="stars-row">
                        @for($s = 1; $s <= 5; $s++)
                            <span class="{{ $s <= round($repScore) ? '' : 'empty' }}">★</span>
                        @endfor
                    </div>
                    <div class="rep-bar-wrap"><div class="rep-bar" style="width:{{ $repPercent }}%;"></div></div>
                    <span class="rep-label">{{ number_format($repScore, 1) }} / 5.0</span>
                </div>
            </div>
        </div>

        @php
            $fields = array_filter([
                $ave?->color ? ['Color', $ave->color . ($ave->color_alternativo ? ' / ' . $ave->color_alternativo : '')] : null,
                $ave?->cresta ? ['Cresta', $ave->cresta] : null,
                $ave?->marca_nacimiento ? ['Línea', $ave->marca_nacimiento] : null,
                $ave?->placa ? ['Placa', $ave->placa] : null,
                $ave?->anillo ? ['Anillo', $ave->anillo] : null,
                $ave?->fecha_nacimiento ? ['Nacimiento', $ave->fecha_nacimiento] : null,
            ]);
        @endphp

        @if(count($fields) > 0)
            <div class="detail-grid">
                @foreach($fields as [$label, $value])
                    <div class="detail-field">
                        <label>{{ $label }}</label>
                        <span>{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        @if($pub->descripcion)
            <p style="font-size:.9rem;color:#374151;line-height:1.7;margin-bottom:1.25rem;">{{ $pub->descripcion }}</p>
        @endif

        <div class="detail-actions">
            <button type="button" class="btn-comprar" onclick="document.getElementById('purchase-section').scrollIntoView({behavior:'smooth'})">
                Comprar esta ave
            </button>
            <button type="button" class="btn-preguntar" onclick="document.getElementById('question-section').scrollIntoView({behavior:'smooth'})">
                Hacer una pregunta
            </button>
        </div>

        @if($preguntas->isNotEmpty())
            <div class="section-box">
                <h3>Preguntas de compradores</h3>
                @foreach($preguntas as $pregunta)
                    <div class="qa-item">
                        <div class="qa-q">{{ $pregunta->cuerpo }}</div>
                        <div class="qa-meta">{{ $pregunta->nombre }} · {{ $pregunta->created_at?->diffForHumans() }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </article>

    <div class="section-box" id="question-section">
        <h3>Hacer una pregunta al vendedor</h3>
        <form method="POST" action="{{ route('marketplace.question') }}">
            @csrf
            <input type="hidden" name="publicacion_id" value="{{ $pub->id }}">
            <div class="form-row">
                <div class="form-field">
                    <label>Tu nombre *</label>
                    <input type="text" name="nombre" class="form-input" required>
                </div>
                <div class="form-field">
                    <label>WhatsApp o correo *</label>
                    <input type="text" name="contacto" class="form-input" required>
                </div>
            </div>
            <div class="form-field">
                <label>Tu pregunta *</label>
                <textarea name="cuerpo" class="form-input" required maxlength="1000" placeholder="¿Cuántos años tiene? ¿Tiene pedigree?"></textarea>
            </div>
            <button type="submit" class="btn-confirm">Enviar pregunta</button>
        </form>
    </div>

    <div class="section-box" id="purchase-section">
        <h3>Confirmar interés de compra</h3>
        <div class="disclaimer-box">
            <strong>Aviso:</strong> Galpon no interviene ni garantiza la transacción. Verifica al vendedor y acuerda pago y entrega directamente. Se creará un chat para coordinar.
        </div>
        <form method="POST" action="{{ route('marketplace.order') }}">
            @csrf
            <input type="hidden" name="publicacion_id" value="{{ $pub->id }}">
            <div class="form-row">
                <div class="form-field">
                    <label>Tu nombre completo *</label>
                    <input type="text" name="buyer_nombre" class="form-input" required>
                </div>
                <div class="form-field">
                    <label>Correo electrónico *</label>
                    <input type="email" name="buyer_email" class="form-input" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>WhatsApp / Teléfono</label>
                    <input type="text" name="buyer_telefono" class="form-input">
                </div>
                <div class="form-field">
                    <label>País</label>
                    <input type="text" name="buyer_pais" class="form-input" placeholder="Venezuela">
                </div>
            </div>
            <div class="form-field" style="display:flex;flex-direction:row;align-items:flex-start;gap:.6rem;">
                <input type="checkbox" name="disclaimer" value="1" required style="margin-top:.25rem;">
                <label style="text-transform:none;letter-spacing:0;font-weight:500;font-size:.82rem;line-height:1.55;">
                    Acepto que Galpon no es responsable de la transacción y confirmo mi interés de compra.
                </label>
            </div>
            <button type="submit" class="btn-confirm">Confirmar interés de compra</button>
        </form>
    </div>
</div>

</body>
</html>

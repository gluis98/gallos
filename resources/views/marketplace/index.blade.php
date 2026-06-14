@php
    use App\Support\MarketplacePresenter;

    $whatsapp   = env('WHATSAPP_SUPPORT_URL', 'https://wa.me/584120000000');
    $ratingDays = (int) config('marketplace.rating_days', 12);
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Marketplace de Gallos y Gallinas — Galpon</title>
    <meta name="description" content="Compra y vende gallos finos y gallinas de raza en el marketplace de Galpon. Criadores de Venezuela, Colombia, México y toda Latinoamérica.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('marketplace.index') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}">
    <meta name="theme-color" content="#1a2648">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        *, ::before, ::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Poppins', sans-serif; background: #f4f7fb; color: #13203a; }

        /* ── Nav ── */
        nav.topnav { position:fixed;top:0;left:0;right:0;z-index:200;background:rgba(7,9,15,.92);border-bottom:1px solid rgba(255,255,255,.07);backdrop-filter:blur(14px);padding:.75rem 0; }
        .nav-inner { max-width:1280px;margin:0 auto;padding:0 1.25rem;display:flex;align-items:center;justify-content:space-between; }
        .nav-logo { display:flex;align-items:center;gap:.65rem;text-decoration:none; }
        .nav-logo img { width:36px;height:36px;border-radius:9px;object-fit:cover; }
        .nav-logo span { font-weight:800;font-size:1.1rem;color:#fff; }
        .nav-links { display:flex;align-items:center;gap:.25rem; }
        .nav-links a { color:rgba(255,255,255,.65);font-weight:500;font-size:.85rem;text-decoration:none;padding:.38rem .7rem;border-radius:.55rem;transition:all .2s; }
        .nav-links a:hover, .nav-links a.active { color:#fff;background:rgba(255,255,255,.08); }
        .nav-actions { display:flex;align-items:center;gap:.65rem; }
        .btn-nav-primary { display:inline-flex;align-items:center;gap:.35rem;background:linear-gradient(135deg,#3b82f6,#7c3aed);color:#fff;border:none;border-radius:.75rem;padding:.55rem 1.1rem;font-family:'Poppins',sans-serif;font-size:.85rem;font-weight:700;cursor:pointer;text-decoration:none;transition:all .2s; }
        .btn-nav-primary:hover { color:#fff;opacity:.9; }

        /* ── Layout ── */
        .page-shell { max-width:1280px;margin:0 auto;padding:5.5rem 1.25rem 3rem; }
        .page-header { margin-bottom:1.5rem; }
        .page-header h1 { font-size:clamp(1.5rem,3vw,2rem);font-weight:800;color:#0f1830;margin-bottom:.3rem; }
        .page-header p { font-size:.9rem;color:#60708d; }

        /* ── Filters ── */
        .filters-bar { background:#fff;border:1px solid #e5e9f2;border-radius:1rem;padding:1.1rem 1.25rem;margin-bottom:1.5rem;box-shadow:0 2px 12px rgba(16,39,77,.05);display:flex;flex-wrap:wrap;gap:.75rem;align-items:flex-end; }
        .filter-group { display:flex;flex-direction:column;gap:.3rem;flex:1;min-width:140px; }
        .filter-group label { font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#60708d; }
        .filter-input { height:38px;border:1.5px solid #dce5f3;border-radius:.65rem;padding:0 .75rem;font-family:'Poppins',sans-serif;font-size:.85rem;color:#13203a;outline:none;transition:border-color .2s;background:#f9fbff; }
        .filter-input:focus { border-color:#6ea4ff;background:#fff; }
        .filter-input option { color:#13203a; }
        .btn-filter { height:38px;background:linear-gradient(135deg,#3b82f6,#7c3aed);color:#fff;border:none;border-radius:.65rem;padding:0 1.25rem;font-family:'Poppins',sans-serif;font-size:.85rem;font-weight:700;cursor:pointer;transition:opacity .2s;white-space:nowrap; }
        .btn-filter:hover { opacity:.9; }
        .btn-clear { height:38px;background:#f3f4f6;color:#60708d;border:none;border-radius:.65rem;padding:0 1rem;font-family:'Poppins',sans-serif;font-size:.82rem;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center; }
        .btn-clear:hover { background:#e5e9f2;color:#1a2648; }

        /* ── Grid ── */
        .listings-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(270px,1fr));gap:1.25rem; }
        .listing-card { background:#fff;border:1px solid #e5e9f2;border-radius:1.1rem;overflow:hidden;cursor:pointer;transition:transform .2s,box-shadow .2s,border-color .2s;display:flex;flex-direction:column; }
        .listing-card:hover { transform:translateY(-3px);box-shadow:0 12px 36px rgba(16,39,77,.12);border-color:#c7d6f0; }
        .listing-card.destacado { border-color:#f59e0b;box-shadow:0 0 0 1px #f59e0b; }
        .card-img-wrap { position:relative;aspect-ratio:4/3;overflow:hidden;background:#f4f7fb; }
        .card-img-wrap img { width:100%;height:100%;object-fit:cover;transition:transform .3s; }
        .listing-card:hover .card-img-wrap img { transform:scale(1.05); }
        .card-no-img { display:flex;align-items:center;justify-content:center;font-size:3.5rem;height:100%;background:linear-gradient(135deg,#e8f0fe,#f3f0ff); }
        .card-badge { position:absolute;top:.6rem;left:.6rem;background:rgba(26,38,72,.85);color:#fff;font-size:.65rem;font-weight:700;border-radius:999px;padding:.2rem .6rem;letter-spacing:.05em;text-transform:uppercase;backdrop-filter:blur(4px); }
        .card-badge.dest { background:linear-gradient(135deg,#f59e0b,#f97316); }
        .card-body { padding:.9rem 1rem 1rem;flex:1;display:flex;flex-direction:column;gap:.35rem; }
        .card-type { font-size:.68rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#60a5fa; }
        .card-name { font-size:1rem;font-weight:700;color:#0f1830;line-height:1.2; }
        .card-meta { display:flex;flex-wrap:wrap;gap:.3rem;margin-top:.1rem; }
        .card-tag { background:#f3f6fd;color:#3b5ea6;font-size:.7rem;font-weight:600;border-radius:999px;padding:.15rem .5rem; }
        .card-price { margin-top:auto;padding-top:.5rem;display:flex;flex-direction:column;gap:.05rem; }
        .price-usd { font-size:1.2rem;font-weight:800;color:#0f1830; }
        .price-bs { font-size:.75rem;color:#60708d;font-weight:500; }
        .card-seller { display:flex;align-items:center;gap:.5rem;padding:.65rem 1rem;border-top:1px solid #f1f5fb;background:#fafbff; }
        .seller-avatar { width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#dbeafe,#ede9fe);display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:800;color:#3b5ea6;flex-shrink:0; }
        .seller-name { font-size:.75rem;font-weight:600;color:#374151; }
        .seller-stars { display:flex;align-items:center;gap:.15rem;margin-left:auto; }
        .star { color:#f59e0b;font-size:.7rem; }
        .star.empty { color:#d1d5db; }
        .sales-badge { font-size:.65rem;color:#60708d;margin-left:.25rem; }

        /* ── Empty state ── */
        .empty-state { text-align:center;padding:4rem 1rem;color:#60708d; }
        .empty-state .icon { font-size:4rem;margin-bottom:1rem;opacity:.4; }
        .empty-state h3 { font-size:1.1rem;font-weight:700;color:#374151;margin-bottom:.4rem; }

        /* ── Modals ── */
        .modal-backdrop { display:none;position:fixed;inset:0;z-index:1000;background:rgba(10,20,50,.6);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:1rem; }
        .modal-backdrop.open { display:flex; }
        .modal-box { background:#fff;border-radius:1.3rem;width:min(680px,100%);max-height:90vh;overflow-y:auto;box-shadow:0 30px 80px rgba(10,20,50,.25);animation:modalIn .25s ease; }
        .modal-box.wide { width:min(820px,100%); }
        @keyframes modalIn { from{transform:translateY(24px);opacity:0} to{transform:translateY(0);opacity:1} }
        .modal-header { display:flex;justify-content:space-between;align-items:flex-start;padding:1.3rem 1.5rem;border-bottom:1px solid #f1f5fb; }
        .modal-header h2 { font-size:1.1rem;font-weight:800;color:#0f1830; }
        .modal-close { background:#f3f4f6;border:none;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:1rem;color:#60708d;flex-shrink:0;margin-top:-.1rem;transition:background .2s; }
        .modal-close:hover { background:#e5e7eb;color:#111; }
        .modal-body { padding:1.5rem; }

        /* Detail modal */
        .detail-gallery { display:grid;grid-template-columns:1fr;gap:.5rem;margin-bottom:1.25rem; }
        .detail-gallery img { width:100%;max-height:300px;object-fit:cover;border-radius:.75rem; }
        .detail-grid { display:grid;grid-template-columns:1fr 1fr;gap:.6rem .85rem;margin-bottom:1.25rem; }
        .detail-field label { display:block;font-size:.68rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#60708d;margin-bottom:.15rem; }
        .detail-field span { font-size:.9rem;font-weight:600;color:#13203a; }
        .detail-price-box { background:linear-gradient(135deg,#1a2648,#2d4278);border-radius:1rem;padding:1.1rem 1.25rem;margin-bottom:1.25rem;display:flex;flex-wrap:wrap;gap:1rem;align-items:center; }
        .detail-price-usd { font-size:1.8rem;font-weight:900;color:#fff; }
        .detail-price-bs { font-size:.88rem;color:rgba(255,255,255,.65); }
        .detail-actions { display:flex;gap:.75rem;flex-wrap:wrap; }
        .btn-comprar { flex:1;min-width:140px;background:linear-gradient(135deg,#3b82f6,#7c3aed);color:#fff;border:none;border-radius:.8rem;padding:.75rem 1rem;font-family:'Poppins',sans-serif;font-size:.95rem;font-weight:700;cursor:pointer;transition:opacity .2s; }
        .btn-comprar:hover { opacity:.9; }
        .btn-preguntar { flex:1;min-width:120px;background:#f3f6fd;color:#1a2648;border:1.5px solid #dce5f3;border-radius:.8rem;padding:.75rem 1rem;font-family:'Poppins',sans-serif;font-size:.92rem;font-weight:600;cursor:pointer;transition:all .2s; }
        .btn-preguntar:hover { border-color:#93c5fd;background:#edf4ff; }

        /* Seller box */
        .seller-box { background:#f8faff;border:1px solid #e8eef8;border-radius:.9rem;padding:1rem 1.1rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:1rem;flex-wrap:wrap; }
        .seller-big-avatar { width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#dbeafe,#ede9fe);display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:800;color:#3b5ea6;flex-shrink:0; }
        .seller-info { flex:1; }
        .seller-info .name { font-size:.95rem;font-weight:700;color:#0f1830; }
        .seller-info .sub { font-size:.75rem;color:#60708d;margin-top:.1rem; }
        .rep-meter { display:flex;align-items:center;gap:.5rem;flex-wrap:wrap; }
        .stars-row { display:flex;gap:.15rem; }
        .rep-bar-wrap { height:6px;width:80px;background:#e5e9f2;border-radius:99px;overflow:hidden; }
        .rep-bar { height:100%;background:linear-gradient(90deg,#f59e0b,#f97316);border-radius:99px;transition:width .5s; }
        .rep-label { font-size:.72rem;color:#60708d;font-weight:600; }

        /* Q&A */
        .qa-section { margin-top:1.25rem; }
        .qa-section h4 { font-size:.85rem;font-weight:700;color:#374151;margin-bottom:.75rem; }
        .qa-item { background:#f8faff;border:1px solid #e8eef8;border-radius:.75rem;padding:.8rem .9rem;margin-bottom:.55rem; }
        .qa-q { font-size:.83rem;font-weight:600;color:#1a2648;display:flex;align-items:flex-start;gap:.4rem; }
        .qa-a { font-size:.8rem;color:#60708d;margin-top:.3rem;padding-left:1.1rem; }

        /* Purchase modal */
        .disclaimer-box { background:#fff8e6;border:1px solid rgba(245,158,11,.3);border-radius:.8rem;padding:.85rem 1rem;margin-bottom:1.25rem;font-size:.82rem;color:#92400e;line-height:1.65; }
        .disclaimer-box strong { color:#78350f; }
        .form-row { display:grid;grid-template-columns:1fr 1fr;gap:.75rem; }
        .form-field { display:flex;flex-direction:column;gap:.3rem;margin-bottom:.75rem; }
        .form-field label { font-size:.75rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:#60708d; }
        .form-input { height:42px;border:1.5px solid #dce5f3;border-radius:.7rem;padding:0 .85rem;font-family:'Poppins',sans-serif;font-size:.9rem;color:#13203a;outline:none;transition:border-color .2s; }
        .form-input:focus { border-color:#6ea4ff; }
        .form-check { display:flex;align-items:flex-start;gap:.6rem;margin-bottom:1rem; }
        .form-check input { margin-top:.2rem;flex-shrink:0; }
        .form-check label { font-size:.82rem;color:#374151;line-height:1.55; }
        .btn-confirm { width:100%;background:linear-gradient(135deg,#3b82f6,#7c3aed);color:#fff;border:none;border-radius:.8rem;padding:.85rem;font-family:'Poppins',sans-serif;font-size:.97rem;font-weight:700;cursor:pointer;transition:opacity .2s; }
        .btn-confirm:hover { opacity:.9; }

        /* Success state */
        .success-box { text-align:center;padding:2rem 1rem; }
        .success-icon { font-size:3.5rem;margin-bottom:.75rem; }
        .success-box h3 { font-size:1.15rem;font-weight:800;color:#0f1830;margin-bottom:.5rem; }
        .success-box p { font-size:.88rem;color:#60708d;line-height:1.65;margin-bottom:1.25rem; }

        /* Pagination */
        .pagination-wrap { display:flex;justify-content:center;margin-top:2rem; }
        .pagination-wrap nav ul { display:flex;gap:.35rem;list-style:none; }
        .pagination-wrap nav ul li a, .pagination-wrap nav ul li span { display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:.6rem;font-size:.85rem;font-weight:600;text-decoration:none;transition:all .2s; }
        .pagination-wrap nav ul li a { background:#fff;border:1px solid #e5e9f2;color:#374151; }
        .pagination-wrap nav ul li a:hover { background:#edf4ff;border-color:#93c5fd;color:#1a2648; }
        .pagination-wrap nav ul li span.active { background:linear-gradient(135deg,#3b82f6,#7c3aed);color:#fff;border:none; }

        /* WA float */
        .wa-float { position:fixed;bottom:2rem;right:1.5rem;z-index:9999;width:52px;height:52px;border-radius:50%;border:none;cursor:pointer;background:#25d366;box-shadow:0 6px 20px rgba(37,211,102,.5);display:flex;align-items:center;justify-content:center;transition:transform .2s;text-decoration:none; }
        .wa-float:hover { transform:scale(1.1); }
        .wa-float svg { width:26px;height:26px;fill:#fff; }

        @media(max-width:640px) {
            .nav-links { display:none; }
            .detail-grid { grid-template-columns:1fr; }
            .form-row { grid-template-columns:1fr; }
            .detail-actions { flex-direction:column; }
        }
    </style>
</head>
<body>

{{-- NAV --}}
<nav class="topnav">
    <div class="nav-inner">
        <a href="{{ url('/') }}" class="nav-logo">
            <img src="{{ asset('img/logo.png') }}" alt="Galpon" width="36" height="36">
            <span>Galpon</span>
        </a>
        <div class="nav-links">
            <a href="{{ route('marketplace.index') }}" class="active">Marketplace</a>
            <a href="{{ route('plans') }}">Planes y Precios</a>
        </div>
        <div class="nav-actions">
            @auth
                <a href="{{ route('panel') }}" class="btn-nav-primary">
                    <span class="material-symbols-outlined" style="font-size:.9rem;">dashboard</span> Mi Panel
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-nav-primary">Iniciar sesión</a>
            @endauth
        </div>
    </div>
</nav>

<div class="page-shell">

    {{-- Header --}}
    <div class="page-header">
        <h1>
            <span class="material-symbols-outlined" style="font-size:1.4rem;vertical-align:middle;color:#3b82f6;">storefront</span>
            Marketplace Avícola
        </h1>
        <p>Compra y vende gallos finos y gallinas de raza. Criadores de Venezuela y toda Latinoamérica.</p>
        @if(session('ok'))
            <div style="background:#dcfce7;border:1px solid #86efac;color:#166534;border-radius:.65rem;padding:.65rem 1rem;font-size:.88rem;font-weight:600;margin-top:.75rem;display:flex;align-items:center;gap:.4rem;">
                <span class="material-symbols-outlined" style="font-size:1rem;">check_circle</span> {{ session('ok') }}
            </div>
        @endif
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('marketplace.index') }}" class="filters-bar">
        <div class="filter-group">
            <label>Tipo de ave</label>
            <select name="tipo" class="filter-input">
                <option value="">Todos</option>
                <option value="gallo" {{ request('tipo') === 'gallo' ? 'selected' : '' }}>Gallos</option>
                <option value="gallina" {{ request('tipo') === 'gallina' ? 'selected' : '' }}>Gallinas</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Color</label>
            <select name="color" class="filter-input">
                <option value="">Todos los colores</option>
                @foreach($colores as $c)
                    <option value="{{ $c }}" {{ request('color') === $c ? 'selected' : '' }}>{{ ucfirst($c) }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Cresta</label>
            <select name="cresta" class="filter-input">
                <option value="">Todas las crestas</option>
                @foreach($crestas as $c)
                    <option value="{{ $c }}" {{ request('cresta') === $c ? 'selected' : '' }}>{{ ucfirst($c) }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group" style="min-width:100px;">
            <label>Precio mín. $</label>
            <input type="number" name="precio_min" value="{{ request('precio_min') }}" min="0" class="filter-input" placeholder="0">
        </div>
        <div class="filter-group" style="min-width:100px;">
            <label>Precio máx. $</label>
            <input type="number" name="precio_max" value="{{ request('precio_max') }}" min="0" class="filter-input" placeholder="9999">
        </div>
        <div class="filter-group" style="min-width:120px;">
            <label>Raza / Placa</label>
            <input type="text" name="raza" value="{{ request('raza') }}" class="filter-input" placeholder="Buscar...">
        </div>
        <div style="display:flex;gap:.5rem;align-self:flex-end;">
            <button type="submit" class="btn-filter">
                <span class="material-symbols-outlined" style="font-size:.9rem;vertical-align:middle;">search</span> Filtrar
            </button>
            @if(request()->hasAny(['tipo','color','cresta','precio_min','precio_max','raza']))
                <a href="{{ route('marketplace.index') }}" class="btn-clear">✕</a>
            @endif
        </div>
    </form>

    {{-- Conteo --}}
    <div style="font-size:.82rem;color:#60708d;margin-bottom:1rem;font-weight:500;">
        {{ $items->total() }} publicación{{ $items->total() !== 1 ? 'es' : '' }} encontrada{{ $items->total() !== 1 ? 's' : '' }}
    </div>

    {{-- Grid --}}
    @if($items->isEmpty())
        <div class="empty-state">
            <div class="icon">🐓</div>
            <h3>No hay publicaciones con esos filtros</h3>
            <p>Intenta con otros filtros o <a href="{{ route('marketplace.index') }}" style="color:#3b82f6;">ver todo el marketplace</a>.</p>
        </div>
    @else
        <div class="listings-grid">
            @foreach($items as $item)
            @php
                $ave      = $item->ave;
                $isGallo  = $item->ave_type === \App\Models\Gallo::class;
                $typeLabel = $isGallo ? 'Gallo' : 'Gallina';
                $images   = MarketplacePresenter::aveImages($ave, $isGallo);
                $firstImg = $images->first();
                $initials = strtoupper(substr($item->tenant_id ?? 'V', 0, 1));
            @endphp
            <a href="{{ route('marketplace.show', $item->id) }}" class="listing-card {{ $item->destacado ? 'destacado' : '' }}" style="text-decoration:none;color:inherit;">
                <div class="card-img-wrap">
                    @if($firstImg)
                        <img src="{{ $firstImg }}"
                             alt="{{ $ave?->nombre ?? 'Ave' }}"
                             loading="lazy"
                             onerror="this.parentElement.innerHTML='<div class=\'card-no-img\'>{{ $isGallo ? '🐓' : '🐔' }}</div>'">
                    @else
                        <div class="card-no-img">{{ $isGallo ? '🐓' : '🐔' }}</div>
                    @endif
                    <div class="card-badge {{ $item->destacado ? 'dest' : '' }}">
                        {{ $item->destacado ? '⭐ Destacado' : $typeLabel }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-type">{{ $typeLabel }}</div>
                    <div class="card-name">{{ $ave?->nombre ?? 'Sin nombre' }}</div>
                    <div class="card-meta">
                        @if($ave?->color)
                            <span class="card-tag">{{ ucfirst($ave->color) }}</span>
                        @endif
                        @if($ave?->cresta)
                            <span class="card-tag">Cresta {{ ucfirst($ave->cresta) }}</span>
                        @endif
                        @if($ave?->marca_nacimiento)
                            <span class="card-tag">{{ $ave->marca_nacimiento }}</span>
                        @endif
                    </div>
                    <div class="card-price">
                        <div class="price-usd">${{ number_format($item->precio ?? 0, 2) }}</div>
                        @if($rate > 0)
                            <div class="price-bs">≈ Bs. {{ number_format(($item->precio ?? 0) * $rate, 2) }}</div>
                        @endif
                    </div>
                </div>
                <div class="card-seller">
                    <div class="seller-avatar">{{ $initials }}</div>
                    <div class="seller-name">{{ $item->tenant_id }}</div>
                    <div class="seller-stars">
                        @for($s=1;$s<=5;$s++)
                            <span class="star {{ $s <= 4 ? '' : 'empty' }}">★</span>
                        @endfor
                        <span class="sales-badge">• {{ rand(1,30) }} ventas</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="pagination-wrap">
            {{ $items->links() }}
        </div>
    @endif

</div>{{-- /page-shell --}}

{{-- ══════════════════════════════════════════
     MODAL: Detalle de publicación
══════════════════════════════════════════ --}}
<div class="modal-backdrop" id="modal-detail">
    <div class="modal-box wide" id="modal-detail-box">
        <div class="modal-header">
            <h2 id="detail-title">Cargando...</h2>
            <button class="modal-close" onclick="closeModal('modal-detail')">✕</button>
        </div>
        <div class="modal-body" id="detail-content">
            <div style="text-align:center;padding:3rem;color:#60708d;">Cargando información...</div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     MODAL: Hacer una pregunta
══════════════════════════════════════════ --}}
<div class="modal-backdrop" id="modal-question">
    <div class="modal-box">
        <div class="modal-header">
            <h2>Hacer una pregunta al vendedor</h2>
            <button class="modal-close" onclick="closeModal('modal-question')">✕</button>
        </div>
        <div class="modal-body">
            <div id="question-success" style="display:none;" class="success-box">
                <div class="success-icon">✅</div>
                <h3>¡Pregunta enviada!</h3>
                <p>El vendedor recibió tu pregunta y responderá pronto. Puedes dejar tu WhatsApp o correo para que te contacte.</p>
                <button onclick="closeModal('modal-question')" class="btn-confirm" style="max-width:200px;margin:0 auto;">Cerrar</button>
            </div>
            <div id="question-form-wrap">
                <form id="form-question" onsubmit="submitQuestion(event)">
                    @csrf
                    <input type="hidden" name="publicacion_id" id="q-pub-id">
                    <div class="form-row">
                        <div class="form-field">
                            <label>Tu nombre *</label>
                            <input type="text" name="nombre" class="form-input" required placeholder="Nombre completo">
                        </div>
                        <div class="form-field">
                            <label>WhatsApp o correo *</label>
                            <input type="text" name="contacto" class="form-input" required placeholder="+58 424...">
                        </div>
                    </div>
                    <div class="form-field">
                        <label>Tu pregunta *</label>
                        <textarea name="cuerpo" required maxlength="1000" placeholder="¿Cuántos años tiene? ¿Tiene pedigree? ¿Hace envíos?" style="height:100px;border:1.5px solid #dce5f3;border-radius:.7rem;padding:.75rem .85rem;font-family:'Poppins',sans-serif;font-size:.9rem;color:#13203a;outline:none;resize:vertical;width:100%;transition:border-color .2s;" onfocus="this.style.borderColor='#6ea4ff'" onblur="this.style.borderColor='#dce5f3'"></textarea>
                    </div>
                    <button type="submit" class="btn-confirm">Enviar pregunta</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     MODAL: Confirmar compra
══════════════════════════════════════════ --}}
<div class="modal-backdrop" id="modal-purchase">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <h2>Confirmar interés de compra</h2>
                <p style="font-size:.78rem;color:#60708d;margin-top:.2rem;" id="purchase-ave-name"></p>
            </div>
            <button class="modal-close" onclick="closeModal('modal-purchase')">✕</button>
        </div>
        <div class="modal-body">
            <div class="disclaimer-box">
                <strong>⚠️ Aviso importante:</strong> Al confirmar este interés de compra, <strong>Galpon no interviene ni garantiza la transacción</strong>.
                Eres responsable de verificar al vendedor, acordar los términos del pago y la entrega directamente.
                Esta confirmación queda registrada en el sistema como constancia del interés expresado por el comprador.
                <br><br>
                Se creará un chat para coordinar los detalles con el vendedor.
            </div>

            <form id="form-purchase" method="POST" action="{{ route('marketplace.order') }}">
                @csrf
                <input type="hidden" name="publicacion_id" id="p-pub-id">
                <div class="form-row">
                    <div class="form-field">
                        <label>Tu nombre completo *</label>
                        <input type="text" name="buyer_nombre" class="form-input" required placeholder="Juan Pérez">
                    </div>
                    <div class="form-field">
                        <label>Correo electrónico *</label>
                        <input type="email" name="buyer_email" class="form-input" required placeholder="tu@correo.com">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <label>WhatsApp / Teléfono</label>
                        <input type="text" name="buyer_telefono" class="form-input" placeholder="+58 424...">
                    </div>
                    <div class="form-field">
                        <label>País</label>
                        <select name="buyer_pais" class="form-input">
                            <option value="">Seleccionar...</option>
                            <option>Venezuela</option><option>Colombia</option><option>México</option>
                            <option>Perú</option><option>Ecuador</option><option>Rep. Dominicana</option>
                            <option>Puerto Rico</option><option>Panamá</option><option>Cuba</option>
                            <option>Bolivia</option><option>Paraguay</option><option>Guatemala</option>
                            <option>El Salvador</option><option>Honduras</option><option>Brasil</option>
                            <option>Otro</option>
                        </select>
                    </div>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="disclaimer" id="chk-disclaimer" required value="1">
                    <label for="chk-disclaimer">
                        Entiendo que <strong>Galpon no es responsable</strong> de esta transacción.
                        Esta confirmación queda registrada como constancia. Me comprometo a contactar al vendedor
                        a través del chat para coordinar el pago y la entrega.
                    </label>
                </div>
                <button type="submit" class="btn-confirm">
                    ✓ Confirmar interés y abrir chat
                </button>
            </form>
        </div>
    </div>
</div>

{{-- WA Float --}}
<a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="wa-float" aria-label="WhatsApp">
    <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>

{{-- JSON de publicaciones para el modal de detalle --}}
<script>
const RATE       = {{ $rate ?? 0 }};
const ITEMS_DATA = {
@foreach($items as $item)
@php
    $ave     = $item->ave;
    $isGallo = $item->ave_type === \App\Models\Gallo::class;
    $images  = MarketplacePresenter::aveImages($ave, $isGallo);
    // Calcular reputación simulada (se reemplazará con datos reales al escalar)
    $vendedorNombre = $item->tenant_id ?? 'Criador';
    $initials       = strtoupper(substr($vendedorNombre, 0, 1));
@endphp
{{ $item->id }}: {
    id:         {{ $item->id }},
    tipo:       "{{ $isGallo ? 'Gallo' : 'Gallina' }}",
    nombre:     @json($ave?->nombre ?? 'Sin nombre'),
    color:      @json($ave?->color ?? ''),
    color_alt:  @json($ave?->color_alternativo ?? ''),
    cresta:     @json($ave?->cresta ?? ''),
    raza:       @json($ave?->marca_nacimiento ?? ''),
    placa:      @json($ave?->placa ?? ''),
    anillo:     @json($ave?->anillo ?? ''),
    nacimiento: @json($ave?->fecha_nacimiento ?? ''),
    precio:     {{ $item->precio ?? 0 }},
    descripcion:@json($item->descripcion ?? ''),
    destacado:  {{ $item->destacado ? 'true' : 'false' }},
    vendedor:   @json($vendedorNombre),
    initials:   @json($initials),
    images:     @json($images->all()),
},
@endforeach
};

function openDetail(id) {
    const d = ITEMS_DATA[id];
    if (!d) return;

    document.getElementById('detail-title').textContent = d.tipo + ': ' + d.nombre;

    let imgs = '';
    if (d.images.length > 0) {
        imgs = `<div class="detail-gallery">
            <img src="${d.images[0]}" alt="${d.nombre}" onerror="this.style.display='none'">
        </div>
        ${d.images.length > 1 ? `<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;overflow-x:auto;">${d.images.slice(1).map(u=>`<img src="${u}" style="height:70px;width:90px;object-fit:cover;border-radius:.6rem;flex-shrink:0;" onerror="this.style.display='none'">`).join('')}</div>` : ''}`;
    } else {
        imgs = `<div style="text-align:center;font-size:5rem;margin-bottom:1.25rem;">${d.tipo==='Gallo'?'🐓':'🐔'}</div>`;
    }

    const bsPrice = RATE > 0 ? `<div class="detail-price-bs">≈ Bs. ${(d.precio * RATE).toLocaleString('es-VE',{minimumFractionDigits:2,maximumFractionDigits:2})}</div>` : '';

    const fields = [
        d.color     ? ['Color',    d.color + (d.color_alt ? ' / ' + d.color_alt : '')] : null,
        d.cresta    ? ['Cresta',   d.cresta]   : null,
        d.raza      ? ['Línea',    d.raza]     : null,
        d.placa     ? ['Placa',    d.placa]    : null,
        d.anillo    ? ['Anillo',   d.anillo]   : null,
        d.nacimiento? ['Nacimiento', d.nacimiento] : null,
    ].filter(Boolean);

    const grid = fields.length > 0
        ? `<div class="detail-grid">${fields.map(([l,v])=>`<div class="detail-field"><label>${l}</label><span>${v}</span></div>`).join('')}</div>`
        : '';

    const desc = d.descripcion
        ? `<p style="font-size:.88rem;color:#374151;line-height:1.7;margin-bottom:1.25rem;">${d.descripcion}</p>`
        : '';

    // Reputación (medidor)
    const repScore = 4.2; // se calculará desde backend en escala completa
    const totalSales = Math.floor(Math.random() * 30) + 1;
    const repPercent = (repScore / 5) * 100;
    const starsHtml  = [1,2,3,4,5].map(s => `<span style="color:${s<=Math.round(repScore)?'#f59e0b':'#d1d5db'};font-size:.9rem;">★</span>`).join('');

    const html = `
        ${imgs}
        <div class="detail-price-box">
            <div>
                <div class="detail-price-usd">$${parseFloat(d.precio).toFixed(2)}</div>
                ${bsPrice}
            </div>
            ${d.destacado ? '<span style="background:rgba(245,158,11,.25);color:#fde68a;font-size:.72rem;font-weight:700;border-radius:999px;padding:.2rem .65rem;">⭐ Destacado</span>' : ''}
        </div>

        <div class="seller-box">
            <div class="seller-big-avatar">${d.initials}</div>
            <div class="seller-info">
                <div class="name">${d.vendedor}</div>
                <div class="sub">${totalSales} venta${totalSales!==1?'s':''} completada${totalSales!==1?'s':''}</div>
                <div class="rep-meter" style="margin-top:.35rem;">
                    <div class="stars-row">${starsHtml}</div>
                    <div class="rep-bar-wrap"><div class="rep-bar" style="width:${repPercent}%;"></div></div>
                    <span class="rep-label">${repScore.toFixed(1)} / 5.0</span>
                </div>
            </div>
        </div>

        ${grid}
        ${desc}

        <div class="detail-actions">
            <button class="btn-comprar" onclick="openPurchase(${d.id}, '${d.tipo}: ${d.nombre.replace(/'/g,'')}')">
                🛒 Comprar esta ave
            </button>
            <button class="btn-preguntar" onclick="openQuestion(${d.id})">
                💬 Hacer una pregunta
            </button>
        </div>

        <div class="qa-section" id="qa-${d.id}">
            <h4>Preguntas y respuestas</h4>
            <div style="text-align:center;padding:1.25rem;color:#60708d;font-size:.85rem;">
                <span class="material-symbols-outlined" style="display:block;font-size:2rem;opacity:.4;margin-bottom:.3rem;">forum</span>
                Sin preguntas aún. ¡Sé el primero en preguntar!
            </div>
        </div>
    `;

    document.getElementById('detail-content').innerHTML = html;
    document.getElementById('modal-detail').classList.add('open');
}

function openQuestion(pubId) {
    closeModal('modal-detail');
    document.getElementById('q-pub-id').value = pubId;
    document.getElementById('question-form-wrap').style.display = 'block';
    document.getElementById('question-success').style.display = 'none';
    document.getElementById('modal-question').classList.add('open');
}

function openPurchase(pubId, aveName) {
    closeModal('modal-detail');
    document.getElementById('p-pub-id').value = pubId;
    document.getElementById('purchase-ave-name').textContent = aveName;
    document.getElementById('modal-purchase').classList.add('open');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}

// Enviar pregunta vía AJAX
async function submitQuestion(e) {
    e.preventDefault();
    const form = e.target;
    const btn  = form.querySelector('button[type=submit]');
    btn.disabled = true; btn.textContent = 'Enviando...';

    try {
        const fd = new FormData(form);
        const res = await fetch("{{ route('marketplace.question') }}", {
            method: 'POST',
            body: fd,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || fd.get('_token') }
        });
        const data = await res.json();
        if (data.ok) {
            document.getElementById('question-form-wrap').style.display = 'none';
            document.getElementById('question-success').style.display = 'block';
            form.reset();
        } else {
            alert('Error al enviar. Intenta de nuevo.');
        }
    } catch (err) {
        alert('Error de conexión. Intenta de nuevo.');
    } finally {
        btn.disabled = false; btn.textContent = 'Enviar pregunta';
    }
}

// Cerrar modales con Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-backdrop.open').forEach(m => m.classList.remove('open'));
    }
});
// Cerrar al click en backdrop
document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
    backdrop.addEventListener('click', e => {
        if (e.target === backdrop) backdrop.classList.remove('open');
    });
});
</script>

{{-- CSRF meta para AJAX --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
</body>
</html>

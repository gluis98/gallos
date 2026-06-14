@php
    use App\Support\MarketplacePresenter;
@endphp
{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach ($publicaciones as $publicacion)
@php
    $ave = $publicacion->ave;
    $isGallo = MarketplacePresenter::isGallo($ave, $publicacion->ave_type);
    $images = MarketplacePresenter::aveImages($ave, $isGallo);
    $typeLabel = MarketplacePresenter::aveLabel($isGallo);
    $title = trim(($ave?->nombre ?? 'Ave') . ' — ' . $typeLabel);
@endphp
    <url>
        <loc>{{ $base }}/marketplace/{{ $publicacion->id }}</loc>
        <lastmod>{{ $publicacion->updated_at?->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>{{ $publicacion->destacado ? '0.8' : '0.75' }}</priority>
@foreach ($images as $imageUrl)
        <image:image>
            <image:loc>{{ $imageUrl }}</image:loc>
            <image:title>{{ $title }}</image:title>
        </image:image>
@endforeach
    </url>
@endforeach
</urlset>

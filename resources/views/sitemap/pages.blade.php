{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ $base }}/</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ $base }}/plans</loc>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ $base }}/marketplace</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    @if (Route::has('register'))
    <url>
        <loc>{{ $base }}/register</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endif
    <url>
        <loc>{{ $base }}/blog</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ $base }}/faq</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ $base }}/faq/vacunacion-avicola</loc>
        <changefreq>monthly</changefreq>
        <priority>0.85</priority>
    </url>
    <url>
        <loc>{{ $base }}/faq/historial-medico-aves</loc>
        <changefreq>monthly</changefreq>
        <priority>0.85</priority>
    </url>
    <url>
        <loc>{{ $base }}/faq/crianza-gallos-finos</loc>
        <changefreq>monthly</changefreq>
        <priority>0.85</priority>
    </url>
</urlset>

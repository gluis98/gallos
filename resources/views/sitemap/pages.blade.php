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
</urlset>

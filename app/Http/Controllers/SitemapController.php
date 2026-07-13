<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Gallo;
use App\Models\Publicacion;
use App\Support\MarketplacePresenter;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $base = rtrim(config('app.url'), '/');
        $now  = now()->toAtomString();

        $xml = view('sitemap.index', compact('base', 'now'))->render();

        return $this->xmlResponse($xml);
    }

    public function pages(): Response
    {
        $base = rtrim(config('app.url'), '/');

        $xml = view('sitemap.pages', compact('base'))->render();

        return $this->xmlResponse($xml);
    }

    public function marketplace(): Response
    {
        $publicaciones = Publicacion::withoutGlobalScopes()
            ->where('activo', true)
            ->with([
                'ave',
                'ave.gallos_imagenes',
                'ave.gallinas_imagenes',
            ])
            ->orderByDesc('updated_at')
            ->get();

        $base = rtrim(config('app.url'), '/');

        $xml = view('sitemap.marketplace', compact('base', 'publicaciones'))->render();

        return $this->xmlResponse($xml);
    }

    public function blog(): Response
    {
        $posts = BlogPost::published()
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at']);

        $base = rtrim(config('app.url'), '/');

        $xml = view('sitemap.blog', compact('base', 'posts'))->render();

        return $this->xmlResponse($xml);
    }

    public function robots(): Response
    {
        $sitemap = rtrim(config('app.url'), '/') . '/sitemap.xml';

        $content = implode("\n", [
            'User-agent: *',
            'Disallow: /dashboard',
            'Disallow: /gallos',
            'Disallow: /gallinas',
            'Disallow: /inventario',
            'Disallow: /compras',
            'Disallow: /ventas',
            'Disallow: /report/',
            'Disallow: /pedigree/',
            'Disallow: /payments/',
            'Disallow: /super-admin/',
            'Disallow: /marketplace/chat/',
            'Disallow: /catalogo/',
            'Disallow: /api/',
            'Disallow: /home',
            'Disallow: /password/',
            '',
            "Sitemap: {$sitemap}",
            '',
        ]);

        return response($content, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    protected function xmlResponse(string $xml): Response
    {
        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}

<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Confiar en todos los proxies (Cloudflare, Nginx, load balancer).
     * Sin esto, Laravel ve la petición como HTTP aunque el usuario entre por HTTPS,
     * y genera formularios/action en http:// → aviso "envío no seguro" en Chrome.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}

// ═══════════════════════════════════════════════════════
//  Gallos Pro — Service Worker
//  Estrategia: Cache-first para assets, Network-first para páginas/API
// ═══════════════════════════════════════════════════════

const APP_VERSION   = 'v1.0.0';
const CACHE_STATIC  = `gallos-static-${APP_VERSION}`;
const CACHE_DYNAMIC = `gallos-dynamic-${APP_VERSION}`;

// Recursos a pre-cachear en la instalación
const PRECACHE_URLS = [
    '/',
    '/manifest.json',
    '/img/logo.png',
    '/img/logo.jpeg',
    '/img/avatar.png',
    '/offline.html',
];

// ── Instalación ──────────────────────────────────────
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_STATIC).then((cache) => {
            return cache.addAll(
                PRECACHE_URLS.map(url => new Request(url, { cache: 'reload' }))
            ).catch(() => { /* silencioso si falla algún recurso */ });
        }).then(() => self.skipWaiting())
    );
});

// ── Activación: limpiar cachés viejas ────────────────
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys
                    .filter(k => k !== CACHE_STATIC && k !== CACHE_DYNAMIC)
                    .map(k => caches.delete(k))
            )
        ).then(() => self.clients.claim())
    );
});

// ── Fetch: estrategia mixta ───────────────────────────
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Ignorar peticiones no GET y cross-origin
    if (request.method !== 'GET') return;
    if (url.origin !== location.origin) return;

    // API → Network-first (no cachear respuestas de API)
    if (url.pathname.startsWith('/api/')) {
        event.respondWith(networkFirst(request, false));
        return;
    }

    // Assets estáticos (css, js, img, fonts) → Cache-first
    if (isStaticAsset(url.pathname)) {
        event.respondWith(cacheFirst(request));
        return;
    }

    // Páginas HTML → Network-first con fallback a caché o offline
    event.respondWith(networkFirst(request, true));
});

// ── Estrategia: Cache-first ───────────────────────────
async function cacheFirst(request) {
    const cached = await caches.match(request);
    if (cached) return cached;

    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(CACHE_STATIC);
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        return new Response('Asset no disponible offline.', { status: 503 });
    }
}

// ── Estrategia: Network-first ─────────────────────────
async function networkFirst(request, storeInCache = false) {
    try {
        const response = await fetch(request);
        if (response.ok && storeInCache) {
            const cache = await caches.open(CACHE_DYNAMIC);
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        // Fallback a caché
        const cached = await caches.match(request);
        if (cached) return cached;

        // Fallback a página offline (solo para navegación HTML)
        const offlinePage = await caches.match('/offline.html');
        if (offlinePage) return offlinePage;

        return new Response(
            '<html><body style="font-family:sans-serif;text-align:center;padding:3rem"><h2>Sin conexión</h2><p>Verifica tu internet e intenta de nuevo.</p></body></html>',
            { headers: { 'Content-Type': 'text/html' }, status: 503 }
        );
    }
}

// ── Helper: detectar assets estáticos ─────────────────
function isStaticAsset(pathname) {
    return /\.(css|js|woff2?|ttf|eot|svg|png|jpg|jpeg|gif|ico|webp)(\?.*)?$/.test(pathname)
        || pathname.startsWith('/css/')
        || pathname.startsWith('/js/')
        || pathname.startsWith('/img/')
        || pathname.startsWith('/build/')
        || pathname.startsWith('/plugins/');
}

// ── Mensajes desde el cliente ──────────────────────────
self.addEventListener('message', (event) => {
    if (event.data?.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    if (event.data?.type === 'CLEAR_CACHE') {
        caches.keys().then(keys => keys.forEach(k => caches.delete(k)));
    }
});

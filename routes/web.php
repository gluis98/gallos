<?php

use App\Http\Controllers\BlogPublicController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\Public\CatalogController as PublicCatalogController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SugerenciaController;
use App\Http\Controllers\SuperAdmin\AuditController;
use App\Http\Controllers\SuperAdmin\BlogController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\PaymentOrderController as SuperAdminPaymentOrderController;
use App\Http\Controllers\SuperAdmin\SettingsController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\UserExtraGalponController;
use App\Http\Controllers\Tenant\GalponSwitchController;
use App\Http\Controllers\Tenant\PaymentOrderController;
use App\Services\DollarRateService;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/robots.txt', [SitemapController::class, 'robots']);
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages']);
Route::get('/sitemap-marketplace.xml', [SitemapController::class, 'marketplace']);
Route::get('/sitemap-blog.xml', [SitemapController::class, 'blog']);

// Landing pública: muestra la página de inicio a visitantes,
// redirige al dashboard si el usuario ya está autenticado.
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// Alias legacy: enlaces "Mi Panel" y redirecciones post-auth
Route::get('/home', function () {
    if (! auth()->check()) {
        return redirect()->guest(route('login'));
    }
    if (auth()->user()->is_superadmin) {
        return redirect()->route('superadmin.dashboard');
    }

    return redirect()->route('dashboard');
})->name('panel');

Route::get('/plans', function () {
    return view('plans', [
        'rate'     => DollarRateService::getCachedRate(),
        'settings' => SettingsService::get(),
    ]);
})->name('plans');
Route::post('/sugerencias', [SugerenciaController::class, 'store'])->name('sugerencias.store');

// ── Blog público ──────────────────────────────────────────────────────────────
Route::get('/blog', [BlogPublicController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogPublicController::class, 'show'])->name('blog.show')
    ->where('slug', '[a-z0-9\-]+');

// ── FAQ ───────────────────────────────────────────────────────────────────────
Route::controller(FaqController::class)->prefix('faq')->name('faq.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/vacunacion-avicola', 'vacunacion')->name('vacunacion');
    Route::get('/historial-medico-aves', 'historialMedico')->name('historial-medico');
    Route::get('/crianza-gallos-finos', 'crianzaGallos')->name('crianza-gallos');
});

// Catálogo público: solo acepta clave hexadecimal de 64 caracteres (no IDs de cuenta)
Route::get('/catalogo/{token}', [PublicCatalogController::class, 'show'])
    ->where('token', '[a-fA-F0-9]{64}')
    ->middleware('throttle:120,1')
    ->name('catalog.public');

Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/{id}', [MarketplaceController::class, 'show'])->name('marketplace.show')->where('id', '[0-9]+');
Route::post('/marketplace/question', [MarketplaceController::class, 'question'])->name('marketplace.question');
Route::post('/marketplace/order', [MarketplaceController::class, 'confirmOrder'])->name('marketplace.order');
Route::get('/marketplace/chat/{order}', [MarketplaceController::class, 'chat'])->name('marketplace.chat');
Route::post('/marketplace/chat/{order}/send', [MarketplaceController::class, 'sendChat'])->name('marketplace.chat.send');
Route::post('/marketplace/chat/{order}/rate', [MarketplaceController::class, 'rateSeller'])->name('marketplace.rate');
Route::post('/marketplace/contact', [MarketplaceController::class, 'question'])->name('marketplace.contact');

Route::middleware(['auth', 'tenant.context'])->group(function () {
    Route::view('/gallos', 'admin.gallos.index')->name('gallos');
    Route::view('/vacunaciones', 'admin.vacunaciones.index')->name('vacunaciones');
    Route::view('/gallinas', 'admin.gallinas.index')->name('gallinas');
    Route::view('/inventario', 'admin.inventario.index')->name('inventario');
    Route::view('/compras', 'admin.compras.index')->name('compras');
    Route::view('/ventas', 'admin.ventas.index')->name('ventas');

    Route::get('/dashboard', \App\Http\Controllers\Tenant\DashboardController::class)->name('dashboard');

    Route::post('/galpon/switch', [GalponSwitchController::class, 'switch'])->name('tenant.galpon.switch');

    Route::get('/pedigree/gallo/{id}', fn (string $id) => view('pedigree.gallo', ['id' => $id]))->name('pedigree.gallo');

    Route::get('/payments/create', [PaymentOrderController::class, 'create'])->name('tenant.payments.create');
    Route::post('/payments', [PaymentOrderController::class, 'store'])->name('tenant.payments.store');

    Route::controller(ReporteController::class)->group(function () {
        Route::get('/report/all', 'all')->name('report.all');
        Route::get('/report/show/{id}', 'show')->name('report.show');
        Route::get('/report/gallinas/all', 'allGallinas')->name('report.all.gallinas');
        Route::get('/report/show-gallina/{id}', 'showGallina')->name('report.show.gallina');
    });
});

Route::middleware(['auth', 'can:superadmin'])->prefix('super-admin')->name('superadmin.')->group(function () {
    Route::get('/', SuperAdminDashboardController::class)->name('dashboard');

    Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
    Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
    Route::post('/tenants/{id}/suspend', [TenantController::class, 'suspend'])->name('tenants.suspend');
    Route::post('/tenants/{id}/activate', [TenantController::class, 'activate'])->name('tenants.activate');

    Route::get('/payments', [SuperAdminPaymentOrderController::class, 'index'])->name('payments.index');
    Route::post('/payments/{id}/verify', [SuperAdminPaymentOrderController::class, 'verify'])->name('payments.verify');
    Route::post('/payments/{id}/reject', [SuperAdminPaymentOrderController::class, 'reject'])->name('payments.reject');

    Route::get('/users', [SuperAdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [SuperAdminUserController::class, 'show'])->name('users.show');

    Route::get('/users/{id}/galpones', [UserExtraGalponController::class, 'index'])->name('users.galpones');
    Route::post('/users/{id}/galpones/enable', [UserExtraGalponController::class, 'enable'])->name('users.galpones.enable');
    Route::post('/users/{id}/galpones/disable', [UserExtraGalponController::class, 'disable'])->name('users.galpones.disable');
    Route::post('/users/{id}/galpones', [UserExtraGalponController::class, 'store'])->name('users.galpones.store');
    Route::delete('/users/{id}/galpones/{galponId}', [UserExtraGalponController::class, 'destroy'])->name('users.galpones.destroy');

    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions/{id}/activate', [SubscriptionController::class, 'activate'])->name('subscriptions.activate');
    Route::post('/subscriptions/{id}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('/subscriptions/{id}/plan', [SubscriptionController::class, 'setPlan'])->name('subscriptions.plan');

    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');

    // ── Blog (gestión superadmin) ─────────────────────────────────────────────
    Route::resource('blog', BlogController::class)
        ->parameters(['blog' => 'blog']);

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/refresh-rate', [SettingsController::class, 'refreshRate'])->name('settings.refresh_rate');
});

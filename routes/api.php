<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VacunacionController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\GallinaController;
use App\Http\Controllers\GalloController;
use App\Http\Controllers\Tenant\CatalogController as TenantCatalogController;
use App\Http\Controllers\Tenant\GalponSwitchController;
use App\Http\Controllers\Tenant\EventoAveController;
use App\Http\Controllers\Tenant\ReportsExportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VentaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [ApiAuthController::class, 'login']);
Route::post('/auth/logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'tenant.context', 'subscription.limit'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/galpon/switch', [GalponSwitchController::class, 'switch']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead']);

    Route::get('/gallos/{id}/pedigree', [GalloController::class, 'pedigree']);
    Route::get('/gallinas/{id}/pedigree', [GallinaController::class, 'pedigree']);

    Route::apiResource('gallos', GalloController::class);
    Route::post('gallos/search', [GalloController::class, 'search']);

    Route::apiResource('gallinas', GallinaController::class);
    Route::post('gallinas/search', [GallinaController::class, 'search']);

    Route::apiResource('clientes', ClienteController::class);
    Route::apiResource('inventario', InventarioController::class)->except(['create', 'edit', 'show']);
    Route::post('inventario/search', [InventarioController::class, 'search']);

    Route::apiResource('compras', CompraController::class)->only(['index', 'store', 'destroy']);
    Route::post('compras/search', [CompraController::class, 'search']);
    Route::apiResource('ventas', VentaController::class);
    Route::post('ventas/search', [VentaController::class, 'search']);

    Route::apiResource('users', UserController::class);

    Route::get('/eventos-ave', [EventoAveController::class, 'index']);
    Route::post('/eventos-ave', [EventoAveController::class, 'store']);
    Route::delete('/eventos-ave/{id}', [EventoAveController::class, 'destroy']);

    Route::get('/reports/gallo/{id}/pdf', [ReportsExportController::class, 'galloPdf']);
    Route::get('/reports/inventario/excel', [ReportsExportController::class, 'inventarioExcel']);

    Route::get('/catalog', [TenantCatalogController::class, 'info']);
    Route::post('/catalog/regenerate', [TenantCatalogController::class, 'regenerate']);
    Route::post('/catalog/toggle', [TenantCatalogController::class, 'toggle']);

    // ── Vacunaciones ──────────────────────────────────────────────────────────
    Route::get('/vacunaciones/estadisticas', [VacunacionController::class, 'estadisticas']);
    Route::get('/vacunaciones/historial/{aveType}/{aveId}', [VacunacionController::class, 'historial']);
    Route::apiResource('vacunaciones', VacunacionController::class);
});

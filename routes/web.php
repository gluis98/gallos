<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Auth::routes();

Route::middleware('auth')->group(function () {
    Route::view('/', 'admin.gallos.index')->name('home');
    Route::view('/gallinas', 'admin.gallinas.index')->name('gallinas');
    Route::view('/ventas', 'admin.ventas.index')->name('ventas');
    Route::controller(ReporteController::class)->group(function(){
        Route::get('/report/all', 'all')->name('report.all');
        Route::get('/report/show/{id}', 'show')->name('report.show');
        Route::get('/report/gallinas/all', 'allGallinas')->name('report.all.gallinas');
        Route::get('/report/show-gallina/{id}', 'showGallina')->name('report.show.gallina');
    });
});


<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('gallos', \App\Http\Controllers\GalloController::class);
Route::post('gallos/search', [\App\Http\Controllers\GalloController::class,'search']);

Route::apiResource('gallinas', \App\Http\Controllers\GallinaController::class);
Route::post('gallinas/search', [\App\Http\Controllers\GallinaController::class,'search']);
Route::apiResource('clientes', \App\Http\Controllers\ClienteController::class);
Route::apiResource('ventas', \App\Http\Controllers\VentaController::class);
Route::post('ventas/search', [\App\Http\Controllers\VentaController::class,'search']);
Route::apiResource('users', \App\Http\Controllers\UserController::class);
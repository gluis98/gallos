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
Route::apiResource('gallinas', \App\Http\Controllers\GallinaController::class);
Route::apiResource('clientes', \App\Http\Controllers\ClienteController::class);
Route::apiResource('ventas', \App\Http\Controllers\VentaController::class);
Route::apiResource('users', \App\Http\Controllers\UserController::class);
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CategoriaController;
use App\Http\Controllers\Api\V1\ArticuloController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\UsuarioController;
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user()->load('roles');
});

Route::prefix('v1')->group(function () {
    // Rutas públicas
    Route::get('/categorias', [CategoriaController::class, 'index']);
    Route::get('/articulos', [ArticuloController::class, 'index']);
    Route::get('/articulos/{articulo}', [ArticuloController::class, 'show']);
    
    // Rutas privadas (Admin)
    Route::middleware(['auth:sanctum', 'role:admin', 'verified'])->prefix('admin')->group(function () {
        Route::get('/dashboard-stats', [DashboardController::class, 'index']);
        Route::post('/categorias', [CategoriaController::class, 'store']);
        Route::post('/categorias/{categoria}', [CategoriaController::class, 'update']);
        Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy']);
        Route::get('/articulos', [ArticuloController::class, 'index']);
        Route::post('/articulos', [ArticuloController::class, 'store']);
        Route::post('/articulos/{articulo}', [ArticuloController::class, 'update']);
        Route::delete('/articulos/{articulo}', [ArticuloController::class, 'destroy']);
        Route::get('/usuarios', [UsuarioController::class, 'index']);
    });
});
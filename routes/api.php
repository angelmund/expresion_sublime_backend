<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CategoriaController;
use App\Http\Controllers\Api\V1\ArticuloController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\UsuarioController;
use App\Http\Controllers\Api\V1\EstatusVentaController;
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user()->load('roles');
});

Route::prefix('v1')->group(function () {
    // Rutas públicas
    Route::get('/categorias', [CategoriaController::class, 'index']);
    Route::get('/articulos', [ArticuloController::class, 'index']);
    Route::get('/articulos/{articulo}', [ArticuloController::class, 'show']);
    
    // Rutas privadas (Admin)
    Route::middleware(['auth:sanctum', 'role:admin|user', 'verified'])->prefix('admin')->group(function () {
        Route::get('/dashboard-stats', [DashboardController::class, 'index']);
        Route::post('/categorias', [CategoriaController::class, 'store']);
        Route::post('/categorias/{categoria}', [CategoriaController::class, 'update']);
        Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy']);
        Route::post('/categorias/{categoria}/activar', [CategoriaController::class, 'activar']);
        Route::get('/articulos', [ArticuloController::class, 'index']);
        Route::post('/articulos', [ArticuloController::class, 'store']);
        Route::post('/articulos/{articulo}', [ArticuloController::class, 'update']);
        Route::delete('/articulos/{articulo}', [ArticuloController::class, 'destroy']);
        Route::post('/articulos/{articulo}/activar', [ArticuloController::class, 'activar']);
        Route::get('/usuarios', [UsuarioController::class, 'index']);
        Route::patch('/usuarios/{usuario}/role', [UsuarioController::class, 'toggleRole']);
        Route::get('/estatus-venta', [EstatusVentaController::class, 'index']);
        Route::post('/estatus-venta', [EstatusVentaController::class, 'store']);
        Route::put('/estatus-venta/{estatus_venta}', [EstatusVentaController::class, 'update']);
        Route::delete('/estatus-venta/{estatus_venta}', [EstatusVentaController::class, 'destroy']);
        Route::post('/estatus-venta/{estatus_venta}/activar', [EstatusVentaController::class, 'activar']);
    });
});
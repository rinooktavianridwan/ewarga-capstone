<?php

use Illuminate\Support\Facades\Route;
use Modules\Umkm\Http\Controllers\DashboardUmkmController;
use Modules\Umkm\Http\Controllers\UmkmProdukController;
use Modules\Umkm\Http\Controllers\UmkmController;
use Modules\Umkm\Http\Controllers\UmkmMasterController;

Route::middleware('auth:sanctum')->prefix('umkm')->group(function () {

    Route::prefix('dashboard')->group(function () {
        Route::get('/summary', [DashboardUmkmController::class, 'index']);
        Route::get('/latest', [DashboardUmkmController::class, 'latestUmkm']);
        Route::get('/growth', [DashboardUmkmController::class, 'growthByMonth']);
    });

    Route::get('/', [UmkmController::class, 'index']);
    Route::get('/{umkm}', [UmkmController::class, 'show']);
    Route::post('/', [UmkmController::class, 'store']);
    Route::put('/{umkm}', [UmkmController::class, 'update']);
    Route::delete('/{umkm}', [UmkmController::class, 'destroy']);

    Route::prefix('produk')->group(function () {
        Route::get('/index', [UmkmProdukController::class, 'index']);
        Route::get('/{produk}', [UmkmProdukController::class, 'show']);
        Route::post('/', [UmkmProdukController::class, 'store']);
        Route::put('/{produk}', [UmkmProdukController::class, 'update']);
        Route::delete('/{produk}', [UmkmProdukController::class, 'destroy']);
    });

    Route::prefix('umkm-master')->group(function () {
        Route::get('/index', [UmkmMasterController::class, 'index']);
        Route::get('/{type}/{id}', [UmkmMasterController::class, 'show']);
        Route::post('/{type}', [UmkmMasterController::class, 'store']);
        Route::put('/{type}/{id}', [UmkmMasterController::class, 'update']);
        Route::delete('/{type}/{id}', [UmkmMasterController::class, 'destroy']);
    });
});

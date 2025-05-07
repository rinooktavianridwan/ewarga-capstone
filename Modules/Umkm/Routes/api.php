<?php

use Illuminate\Support\Facades\Route;
use Modules\Umkm\Http\Controllers\DashboardUmkmController;
use Modules\Umkm\Http\Controllers\PendataanProdukController;
use Modules\Umkm\Http\Controllers\PendataanUmkmController;
use Modules\Umkm\Http\Controllers\ReferensiUmkmController;

Route::middleware('auth:sanctum')->prefix('umkm')->group(function () {

    Route::prefix('umkm-master')->group(function () {
        Route::get('/{type}', [ReferensiUmkmController::class, 'index']);
        Route::post('/{type}', [ReferensiUmkmController::class, 'store']);
        Route::get('/{type}/{id}', [ReferensiUmkmController::class, 'show']);
        Route::put('/{type}/{id}', [ReferensiUmkmController::class, 'update']);
        Route::delete('/{type}/{id}', [ReferensiUmkmController::class, 'destroy']);
    });

    Route::get('/', [PendataanUmkmController::class, 'index']);
    Route::get('/{umkm}', [PendataanUmkmController::class, 'show']);
    Route::post('/', [PendataanUmkmController::class, 'store']);
    Route::put('/{umkm}', [PendataanUmkmController::class, 'update']);
    Route::delete('/{umkm}', [PendataanUmkmController::class, 'destroy']);

    Route::prefix('produk')->group(function () {
        Route::get('/search', [PendataanProdukController::class, 'index']);
        Route::get('/{produk}', [PendataanProdukController::class, 'show']);
        Route::post('/store', [PendataanProdukController::class, 'store']);
        Route::put('/{produk}', [PendataanProdukController::class, 'update']);
        Route::delete('/{produk}', [PendataanProdukController::class, 'destroy']);
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('/summary', [DashboardUmkmController::class, 'index']);
        Route::get('/latest', [DashboardUmkmController::class, 'latestUmkm']);
        Route::get('/growth', [DashboardUmkmController::class, 'growthByMonth']);
    });
});

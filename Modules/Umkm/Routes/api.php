<?php

use Illuminate\Support\Facades\Route;
use Modules\Umkm\Http\Controllers\DashboardUmkmController;
use Modules\Umkm\Http\Controllers\PendataanProdukController;
use Modules\Umkm\Http\Controllers\PendataanUmkmController;
use Modules\Umkm\Http\Controllers\ReferensiUmkmController;

Route::middleware('auth:sanctum')->prefix('umkm')->group(function () {

    Route::get('/bentuk-usaha', [ReferensiUmkmController::class, 'getBentukUsaha']);
    Route::get('/jenis-usaha', [ReferensiUmkmController::class, 'getJenisUsaha']);

    Route::get('/', [PendataanUmkmController::class, 'index']);
    Route::get('/{umkm}', [PendataanUmkmController::class, 'show']);
    Route::post('/', [PendataanUmkmController::class, 'store']);
    Route::post('/{umkm}', [PendataanUmkmController::class, 'update']);
    Route::delete('/{umkm}', [PendataanUmkmController::class, 'destroy']);

    Route::prefix('produk')->group(function () {
        Route::get('/search', [PendataanProdukController::class, 'index']);
        Route::get('/{produk}', [PendataanProdukController::class, 'show']);
        Route::post('/store', [PendataanProdukController::class, 'store']);
        Route::post('/{produk}', [PendataanProdukController::class, 'update']);
        Route::delete('/{produk}', [PendataanProdukController::class, 'destroy']);
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('/summary', [DashboardUmkmController::class, 'index']);
        Route::get('/latest', [DashboardUmkmController::class, 'latestUmkm']);
        Route::get('/growth', [DashboardUmkmController::class, 'growthByMonth']);
    });
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\Umkm\Http\Controllers\DashboardUmkmController;
use Modules\Umkm\Http\Controllers\PendataanProdukController;
use Modules\Umkm\Http\Controllers\PendataanUmkmController;
use Modules\Umkm\Http\Controllers\ReferensiUmkmController;
use Modules\Umkm\Http\Controllers\UmkmDashboardController; // pastikan ini sudah diimport

Route::middleware('auth:sanctum')->prefix('umkm')->group(function () {

    Route::get('/bentuk-usaha', [ReferensiUmkmController::class, 'getBentukUsaha']);
    Route::get('/jenis-usaha', [ReferensiUmkmController::class, 'getJenisUsaha']);


    Route::get('/search', [PendataanUmkmController::class, 'index']);
    Route::get('/{id}', [PendataanUmkmController::class, 'show']);
    Route::post('/', [PendataanUmkmController::class, 'store']);
    Route::post('/{id}', [PendataanUmkmController::class, 'update']);
    Route::delete('/{id}', [PendataanUmkmController::class, 'destroy']);


    Route::prefix('produk')->group(function () {
        Route::get('/search', [PendataanProdukController::class, 'index']);
        Route::get('/{id}', [PendataanProdukController::class, 'show']);
        Route::post('/c', [PendataanProdukController::class, 'store']);
        Route::post('/{id}', [PendataanProdukController::class, 'update']);
        Route::delete('/{id}', [PendataanProdukController::class, 'destroy']);
    });


    Route::prefix('dashboard')->group(function () {
        Route::get('/summary', [DashboardUmkmController::class, 'index']);
        Route::get('/latest', [DashboardUmkmController::class, 'latestUmkm']);
        Route::get('/growth', [DashboardUmkmController::class, 'growthByMonth']);
    });

});

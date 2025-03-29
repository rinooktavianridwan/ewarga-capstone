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


    Route::get('/', [PendataanUmkmController::class, 'index']);
    Route::get('/{id}', [PendataanUmkmController::class, 'show']);
    Route::post('/', [PendataanUmkmController::class, 'store']);

    // saya ingin update/edit data tidak bisa menggunakan PUT atau PATCH
    // karena data tidak terkirim dan susah dumo and die di controller kosong datanya
    // bisa terkirim datanya hanya menggunakan POST
    Route::post('/{id}', [PendataanUmkmController::class, 'update']);
    Route::delete('/{id}', [PendataanUmkmController::class, 'destroy']);


    Route::prefix('produk')->group(function () {
        // jika saya hanya menggunakan /api/umkm/produk tidak bisa
        // jika saya menggunakan /api/umkm/produk/[suatu kata] bisa
        Route::get('/', [PendataanProdukController::class, 'index']);
        Route::get('/{id}', [PendataanProdukController::class, 'show']);

        // ini hanya mnenggunakan /api/umkm/produk tidak bisa mengirim data dan not found response di postman
        // tapi jika setelah /umkm/produk/[suatu kata] bisa mengirim
        Route::post('/', [PendataanProdukController::class, 'store']);

        // saya ingin update/edit data tidak bisa menggunakan PUT atau PATCH
        // karena data tidak terkirim dan susah dumo and die di controller kosong datanya
        // bisa terkirim datanya hanya menggunakan POST
        Route::post('/{id}', [PendataanProdukController::class, 'update']);
        Route::delete('/{id}', [PendataanProdukController::class, 'destroy']);
    });


    Route::prefix('dashboard')->group(function () {
        Route::get('/summary', [DashboardUmkmController::class, 'index']);
        Route::get('/latest', [DashboardUmkmController::class, 'latestUmkm']);
        Route::get('/growth', [DashboardUmkmController::class, 'growthByMonth']);
    });

});

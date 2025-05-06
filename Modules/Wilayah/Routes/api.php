<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Wilayah\Http\Controllers\AsetController;
use Modules\Wilayah\Http\Controllers\AsetFotoController;
use Modules\Wilayah\Http\Controllers\AsetMJenisController;
use Modules\Wilayah\Http\Controllers\AsetMStatusController;
use Modules\Wilayah\Http\Controllers\AsetPenghuniController;
use Modules\Wilayah\Http\Controllers\WilayahController;
use Modules\Wilayah\Http\Controllers\AsetMasterController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/wilayah', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->prefix('/wilayah')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/aset-statistics', [WilayahController::class, 'getAsetStatistics']);
    });

    Route::prefix('aset')->group(function () {
        Route::get('/', [AsetController::class, 'index']);
        Route::get('/search/by-name', [AsetController::class, 'searchByName']);
        Route::get('/search/instansi/{id}', [AsetController::class, 'getAllByInstansi']);
        Route::get('/{id}', [AsetController::class, 'show']);
        Route::get('/{aset}/lokasi', [AsetController::class, 'showLokasi']);
        Route::post('/', [AsetController::class, 'store']);
        Route::put('/{aset}', [AsetController::class, 'update']);
        Route::put('/{aset}/lokasi', [AsetController::class, 'updateLokasi']);
        Route::delete('/{aset}', [AsetController::class, 'destroy']);
    });

    Route::prefix('aset-foto')->group(function () {
        Route::get('/', [AsetFotoController::class, 'index']);
        Route::get('/aset/{aset}', [AsetFotoController::class, 'byAset']);
        Route::get('/{id}', [AsetFotoController::class, 'show']);
    });

    Route::prefix('aset-penghuni')->group(function () {
        Route::get('/', [AsetPenghuniController::class, 'index']);
        Route::get('/aset/{aset}', [AsetPenghuniController::class, 'byAset']);
        Route::get('/{id}', [AsetPenghuniController::class, 'show']);
        Route::post('/{aset}', [AsetPenghuniController::class, 'store']);
        Route::put('/{aset}', [AsetPenghuniController::class, 'update']);
    });

    Route::prefix('aset-master')->group(function () {
        Route::get('/{type}', [AsetMasterController::class, 'index']);
        Route::get('/{type}/{id}', [AsetMasterController::class, 'show']);
        Route::post('/{type}', [AsetMasterController::class, 'store']);
        Route::put('/{type}/{id}', [AsetMasterController::class, 'update']);
        Route::delete('/{type}/{id}', [AsetMasterController::class, 'destroy']);
    });
});

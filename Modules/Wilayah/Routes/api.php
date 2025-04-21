<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Wilayah\Http\Controllers\AsetController;
use Modules\Wilayah\Http\Controllers\InstansiController;
use Modules\Wilayah\Http\Controllers\AsetFotoController;
use Modules\Wilayah\Http\Controllers\AsetMJenisController;
use Modules\Wilayah\Http\Controllers\AsetMStatusController;
use Modules\Wilayah\Http\Controllers\AsetPenghuniController;


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

Route::prefix('asets')->group(function () {
    Route::get('/', [AsetController::class, 'index']);
    Route::get('/search/by-name', [AsetController::class, 'searchByName']);
    Route::get('/{id}', [AsetController::class, 'show']);
    Route::post('/', [AsetController::class, 'store']);
    Route::put('/{aset}', [AsetController::class, 'update']);
    Route::put('/{aset}/lokasi', [AsetController::class, 'updateLokasi']);
    Route::delete('/{aset}', [AsetController::class, 'destroy']);
});

Route::prefix('aset-foto')->group(function () {
    Route::get('/', [AsetFotoController::class, 'index']);
    Route::get('/aset/{aset}', [AsetFotoController::class, 'byAset']);
    Route::get('/{id}', [AsetFotoController::class, 'show']);
    Route::post('/{aset}', [AsetFotoController::class, 'store']);
    Route::delete('/{aset}', [AsetFotoController::class, 'destroy']);
});

Route::prefix('aset-penghuni')->group(function () {
    Route::get('/', [AsetPenghuniController::class, 'index']);
    Route::get('/aset/{aset}', [AsetPenghuniController::class, 'byAset']);
    Route::get('/{id}', [AsetPenghuniController::class, 'show']);
    Route::post('/{aset}', [AsetPenghuniController::class, 'store']);
    Route::put('/{aset}', [AsetPenghuniController::class, 'update']);
    Route::delete('/{aset}', [AsetPenghuniController::class, 'destroy']);
});

Route::prefix('asetMJenis')->group(function () {
    Route::get('/', [AsetMJenisController::class, 'index']);
    Route::get('/{id}', [AsetMJenisController::class, 'show']);
    Route::post('/', [AsetMJenisController::class, 'store']);
    Route::put('/{asetMJenis}', [AsetMJenisController::class, 'update']);
    Route::delete('/{asetMJenis}', [AsetMJenisController::class, 'destroy']);
});

Route::prefix('asetMStatus')->group(function () {
    Route::get('/', [AsetMStatusController::class, 'index']);
    Route::get('/{id}', [AsetMStatusController::class, 'show']);
    Route::post('/', [AsetMStatusController::class, 'store']);
    Route::put('/{asetMStatus}', [AsetMStatusController::class, 'update']);
    Route::delete('/{asetMStatus}', [AsetMStatusController::class, 'destroy']);
});


<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InstansiController;

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


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/instansi/{instansiId}/is-owner', [InstansiController::class, 'isOwner']);
    Route::get('/instansi/{instansiId}/is-pengurus', [InstansiController::class, 'isPengurus']);
});


Route::get('/users/{userId}/warga', [UserController::class, 'getWarga']);
Route::post('/users/{userId}/is-owner', [UserController::class, 'isOwner']);
Route::post('/users/{userId}/is-pengurus', [UserController::class, 'isPengurus']);


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::delete('/logout-all', [AuthController::class, 'logoutAll']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/warga', [WargaController::class, 'daftar']);
    Route::get('/warga/{id}', [WargaController::class, 'validasiWarga']);
    Route::post('/warga/email', [WargaController::class, 'validasiWargaByEmail']);
    Route::get('/warga/me', [WargaController::class, 'validasiWargaByMe']);
    Route::post('/warga/save', [WargaController::class, 'save']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


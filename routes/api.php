<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WargaController;
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

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::delete('/logout', [AuthController::class, 'logout']);

    Route::get('/warga', [WargaController::class, 'index']);
    Route::get('/warga/{id}', [WargaController::class, 'show']);

    Route::get('/instansi', [InstansiController::class, 'index']);
    Route::get('/instansi/{id}', [InstansiController::class, 'show']);
});

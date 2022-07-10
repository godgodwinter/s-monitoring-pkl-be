<?php

use App\Http\Controllers\AuthPembimbingLapangan;
use App\Http\Controllers\pembimbinglapangan\pembimbingLapanganSettingsController;
use App\Http\Controllers\siswa\siswaAbsensiController;
use App\Http\Controllers\siswa\siswaPendaftaranPKLController;
use App\Http\Controllers\siswa\siswaProfileController;
use App\Http\Controllers\siswa\siswaSettingsController;
use Illuminate\Support\Facades\Route;



Route::middleware('api')->group(function () {
    Route::post('/pembimbinglapangan/auth/login', [AuthPembimbingLapangan::class, 'login']);
    Route::post('/pembimbinglapangan/auth/logout', [AuthPembimbingLapangan::class, 'logout']);
    Route::post('/pembimbinglapangan/auth/refresh', [AuthPembimbingLapangan::class, 'refresh']);
    Route::post('/pembimbinglapangan/auth/me', [AuthPembimbingLapangan::class, 'me']);


    // Route::get('/pSembimbinglapangan/settings/get', [pembimbingLapanganSettingsController::class, 'index']);
    Route::get('/pembimbinglapangan/profile/get', [pembimbingLapanganSettingsController::class, 'index']);
});

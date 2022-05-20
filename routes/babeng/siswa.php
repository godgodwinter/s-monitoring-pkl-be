<?php

use App\Http\Controllers\AuthSiswaController;
use App\Http\Controllers\siswa\siswaPendaftaranPKLController;
use App\Http\Controllers\siswa\siswaProfileController;
use App\Http\Controllers\siswa\siswaSettingsController;
use Illuminate\Support\Facades\Route;



Route::middleware('api')->group(function () {
    //menu-portofolio
    // Route::post('/siswa/auth/register', [AuthSiswaController::class, 'register'])->name('siswa.auth.register');
    Route::post('/siswa/auth/login', [AuthSiswaController::class, 'login'])->name('siswa.auth.login');
    Route::post('/siswa/auth/logout', [AuthSiswaController::class, 'logout'])->name('siswa.auth.logout');
    Route::post('/siswa/auth/refresh', [AuthSiswaController::class, 'refresh'])->name('siswa.auth.refresh');
    Route::post('/siswa/auth/me', [AuthSiswaController::class, 'me'])->name('siswa.auth.me');


    Route::get('/siswa/settings/get', [siswaSettingsController::class, 'index']);
    Route::get('/siswa/profile/get', [siswaProfileController::class, 'index']);

    Route::get('/siswa/profile/pendaftaranpkl', [siswaProfileController::class, 'pendaftaranpkl']);
    Route::get('/siswa/pendaftaranpkl/periksa', [siswaProfileController::class, 'pendaftaranpkl']);


    Route::post('/siswa/pendaftaranpkl/daftar', [siswaPendaftaranPKLController::class, 'daftar']);
    Route::get('/siswa/pendaftaranpkl/getdatatempatpkl', [siswaPendaftaranPKLController::class, 'getDataTempatPKL']);
    Route::get('/siswa/pendaftaranpkl/pengajuanpkl/get', [siswaPendaftaranPKLController::class, 'pengajuanTempatPklGet']);
    Route::post('/siswa/pendaftaranpkl/pengajuanpkl/store', [siswaPendaftaranPKLController::class, 'pengajuanTempatPklStore']);
    Route::get('/siswa/pendaftaranpkl/getStatuspkl', [siswaPendaftaranPKLController::class, 'getStatusPKL']);
});

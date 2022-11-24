<?php

use App\Http\Controllers\admin\AdminPenilaianController;
use App\Http\Controllers\AuthPembimbingSekolah;
use App\Http\Controllers\pembimbingsekolah\guruPenilaianController;
use App\Http\Controllers\pembimbingsekolah\guruPenilaianGuruController;
use App\Http\Controllers\pembimbingsekolah\guruPenilaianPembimbingLapanganController;
use App\Http\Controllers\siswa\siswaAbsensiController;
use App\Http\Controllers\siswa\siswaPendaftaranPKLController;
use App\Http\Controllers\siswa\siswaProfileController;
use App\Http\Controllers\siswa\siswaSettingsController;
use Illuminate\Support\Facades\Route;



Route::middleware('api')->group(function () {
    Route::post('/pembimbingsekolah/auth/login', [AuthPembimbingSekolah::class, 'login']);
    Route::post('/pembimbingsekolah/auth/logout', [AuthPembimbingSekolah::class, 'logout']);
    Route::post('/pembimbingsekolah/auth/refresh', [AuthPembimbingSekolah::class, 'refresh']);
    Route::post('/pembimbingsekolah/auth/me', [AuthPembimbingSekolah::class, 'me']);
    Route::post("pembimbingsekolah/auth/profile", [AuthPembimbingSekolah::class, 'refresh']);


    Route::get('/pembimbingsekolah/settings/get', [siswaSettingsController::class, 'index']);
    Route::get('/pembimbingsekolah/profile/get', [siswaProfileController::class, 'index']);


    Route::get('/guru/penilaian', [guruPenilaianController::class, 'index']);
    Route::post('/guru/penilaian', [guruPenilaianController::class, 'store']);
    Route::get('/guru/penilaian/{item}', [guruPenilaianController::class, 'edit']);
    Route::put('/guru/penilaian/{item}', [guruPenilaianController::class, 'update']);
    Route::delete('/guru/penilaian/{item}', [guruPenilaianController::class, 'destroy']);



    Route::get('/guru/datapenilaian/{penilaian}/guru', [guruPenilaianGuruController::class, 'index']);
    Route::post('/guru/datapenilaian/{penilaian}/guru', [guruPenilaianGuruController::class, 'store']);
    Route::get('/guru/datapenilaian/{penilaian}/guru/{penilaian_guru}', [guruPenilaianGuruController::class, 'edit']);
    Route::put('/guru/datapenilaian/{penilaian}/guru/{penilaian_guru}', [guruPenilaianGuruController::class, 'update']);
    Route::delete('/guru/datapenilaian/{penilaian}/guru/{penilaian_guru}', [guruPenilaianGuruController::class, 'destroy']);



    Route::get('/guru/datapenilaian/{penilaian}/pembimbinglapangan', [guruPenilaianPembimbingLapanganController::class, 'index']);
    Route::post('/guru/datapenilaian/{penilaian}/pembimbinglapangan', [guruPenilaianPembimbingLapanganController::class, 'store']);
    Route::get('/guru/datapenilaian/{penilaian}/pembimbinglapangan/{penilaian_pembimbinglapangan}', [guruPenilaianPembimbingLapanganController::class, 'edit']);
    Route::put('/guru/datapenilaian/{penilaian}/pembimbinglapangan/{penilaian_pembimbinglapangan}', [guruPenilaianPembimbingLapanganController::class, 'update']);
    Route::delete('/guru/datapenilaian/{penilaian}/pembimbinglapangan/{penilaian_pembimbinglapangan}', [guruPenilaianPembimbingLapanganController::class, 'destroy']);
});

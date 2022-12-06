<?php

use App\Http\Controllers\admin\adminSiswaController;
use App\Http\Controllers\AuthPembimbingLapangan;
use App\Http\Controllers\pembimbinglapangan\pembimbinglapanganAbsensiController;
use App\Http\Controllers\pembimbinglapangan\pembimbingLapanganSettingsController;
use App\Http\Controllers\pembimbinglapangan\pembimbinglapanganSiswaController;
use App\Http\Controllers\siswa\siswaAbsensiController;
use App\Http\Controllers\siswa\siswaPendaftaranPKLController;
use App\Http\Controllers\siswa\siswaProfileController;
use App\Http\Controllers\siswa\siswaSettingsController;
use Illuminate\Support\Facades\Route;


Route::post('/pembimbinglapangan/auth/login', [AuthPembimbingLapangan::class, 'login']);
Route::post('/pembimbinglapangan/auth/logout', [AuthPembimbingLapangan::class, 'logout']);

// Route::middleware('api')->group(function () {
Route::middleware('auth:pembimbinglapangan')->group(function () {
    Route::post('/pembimbinglapangan/auth/refresh', [AuthPembimbingLapangan::class, 'refresh']);
    Route::post('/pembimbinglapangan/auth/me', [AuthPembimbingLapangan::class, 'me']);
    Route::post("pembimbinglapangan/auth/profile", [AuthPembimbingLapangan::class, 'refresh']);


    // Route::get('/pSembimbinglapangan/settings/get', [pembimbingLapanganSettingsController::class, 'index']);
    Route::get('/pembimbinglapangan/profile/get', [pembimbingLapanganSettingsController::class, 'index']);
    Route::put('/pembimbinglapangan/profile/update', [pembimbingLapanganSettingsController::class, 'update']);


    Route::get('/pembimbinglapangan/siswa/', [pembimbingLapanganSettingsController::class, 'index']);
    Route::get('/pembimbinglapangan/siswa/absensi', [pembimbingLapanganSettingsController::class, 'index']); //absensi and jurnal (request->thnbln) ALL
    Route::get('/pembimbinglapangan/siswa/absensi/{siswa}', [pembimbingLapanganSettingsController::class, 'index']); //absensi and jurnal (request->thnbln)

    Route::put('/pembimbinglapangan/siswa/absensi/{siswa}/absensi/confirm', [pembimbingLapanganSettingsController::class, 'index']); //absensi and jurnal (request->tgl)
    Route::put('/pembimbinglapangan/siswa/absensi/{siswa}/jurnal /confirm', [pembimbingLapanganSettingsController::class, 'index']); //absensi and jurnal (request->tgl)

    Route::get('/pembimbinglapangan/pkl/{siswa}/absen', [pembimbinglapanganAbsensiController::class, 'getDataAbsensi']);
    Route::post('/pembimbinglapangan/pkl/{siswa}/absen', [pembimbinglapanganAbsensiController::class, 'doAbsen']);
    Route::post('/pembimbinglapangan/pkl/{siswa}/jurnal', [pembimbinglapanganAbsensiController::class, 'doJurnal']);
    Route::post('/pembimbinglapangan/pkl/{siswa}/absen/batalkan', [pembimbinglapanganAbsensiController::class, 'doBatalkan']);

    Route::get('/pembimbinglapangan/dataku/siswa', [pembimbinglapanganSiswaController::class, 'siswa']);

    Route::get('/pembimbinglapangan/siswa/{item}', [adminSiswaController::class, 'edit']);
    Route::get('/pembimbinglapangan/datasiswa/profile/{item}', [adminSiswaController::class, 'profile']);
    // Penilaian
    Route::get('/pembimbinglapangan/datasiswa/profile/{item}/berinilai', [pembimbinglapanganSiswaController::class, 'penilaian_index']);
    Route::post('/pembimbinglapangan/datasiswa/profile/{item}/berinilai', [pembimbinglapanganSiswaController::class, 'penilaian_store']);
});

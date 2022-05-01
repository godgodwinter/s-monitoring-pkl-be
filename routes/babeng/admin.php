<?php

use App\Http\Controllers\admin\adminKelasController;
use App\Http\Controllers\admin\adminPembimbingLapanganController;
use App\Http\Controllers\admin\adminPembimbingSekolahController;
use App\Http\Controllers\admin\adminPendaftaranPrakerinController;
use App\Http\Controllers\admin\adminPendaftaranPrakerinDetailController;
use App\Http\Controllers\admin\adminPendaftaranPrakerinListController;
use App\Http\Controllers\admin\adminSiswaController;
use App\Http\Controllers\admin\adminTapelController;
use App\Http\Controllers\admin\adminTempatPklController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;



Route::post('/admin/auth/login', [AuthController::class, 'login'])->name('admin.auth.login');
// Route::post('/admin/auth/register', [AuthController::class, 'register'])->name('admin.auth.register');
// Route::middleware('api')->group(function () {
Route::middleware('auth:api')->group(function () {
    //menu-portofolio
    Route::post('/admin/auth/logout', [AuthController::class, 'logout'])->name('admin.auth.logout');
    Route::post('/admin/auth/refresh', [AuthController::class, 'refresh'])->name('admin.auth.refresh');
    Route::post('/admin/auth/me', [AuthController::class, 'me'])->name('admin.auth.me');


    Route::get('/admin/tapel', [adminTapelController::class, 'index'])->name('admin.tapel');
    Route::post('/admin/tapel/store', [adminTapelController::class, 'store'])->name('admin.tapel.store');
    Route::get('/admin/tapel/{item}', [adminTapelController::class, 'edit'])->name('admin.tapel.edit');
    Route::put('/admin/tapel/{item}', [adminTapelController::class, 'update'])->name('admin.tapel.update');
    Route::delete('/admin/tapel/{item}', [adminTapelController::class, 'destroy'])->name('admin.tapel.destroy');

    Route::get('/admin/kelas', [adminKelasController::class, 'index'])->name('admin.kelas');
    Route::post('/admin/kelas/store', [adminKelasController::class, 'store'])->name('admin.kelas.store');
    Route::get('/admin/kelas/{item}', [adminKelasController::class, 'edit'])->name('admin.kelas.edit');
    Route::put('/admin/kelas/{item}', [adminKelasController::class, 'update'])->name('admin.kelas.update');
    Route::delete('/admin/kelas/{item}', [adminKelasController::class, 'destroy'])->name('admin.kelas.destroy');

    Route::get('/admin/siswa', [adminSiswaController::class, 'index'])->name('admin.siswa');
    Route::post('/admin/siswa/store', [adminSiswaController::class, 'store'])->name('admin.siswa.store');
    Route::get('/admin/siswa/{item}', [adminSiswaController::class, 'edit'])->name('admin.siswa.edit');
    Route::put('/admin/siswa/{item}', [adminSiswaController::class, 'update'])->name('admin.siswa.update');
    Route::delete('/admin/siswa/{item}', [adminSiswaController::class, 'destroy'])->name('admin.siswa.destroy');
    Route::put('/admin/siswa/{item}/generatepassword', [adminSiswaController::class, 'generatepassword'])->name('admin.siswa.generatepassword');
    Route::post('/admin/siswa/generatepasswordall', [adminSiswaController::class, 'generatepasswordall'])->name('admin.siswa.generatepasswordall');


    Route::get('/admin/tempatpkl', [adminTempatPklController::class, 'index'])->name('admin.tempatpkl');
    Route::post('/admin/tempatpkl/store', [adminTempatPklController::class, 'store'])->name('admin.tempatpkl.store');
    Route::get('/admin/tempatpkl/{item}', [adminTempatPklController::class, 'edit'])->name('admin.tempatpkl.edit');
    Route::put('/admin/tempatpkl/{item}', [adminTempatPklController::class, 'update'])->name('admin.tempatpkl.update');
    Route::delete('/admin/tempatpkl/{item}', [adminTempatPklController::class, 'destroy'])->name('admin.tempatpkl.destroy');


    Route::get('/admin/pembimbinglapangan', [adminPembimbingLapanganController::class, 'index'])->name('admin.pembimbinglapangan');
    Route::post('/admin/pembimbinglapangan/store', [adminPembimbingLapanganController::class, 'store'])->name('admin.pembimbinglapangan.store');
    Route::get('/admin/pembimbinglapangan/{item}', [adminPembimbingLapanganController::class, 'edit'])->name('admin.pembimbinglapangan.edit');
    Route::put('/admin/pembimbinglapangan/{item}', [adminPembimbingLapanganController::class, 'update'])->name('admin.pembimbinglapangan.update');
    Route::delete('/admin/pembimbinglapangan/{item}', [adminPembimbingLapanganController::class, 'destroy'])->name('admin.pembimbinglapangan.destroy');
    Route::put('/admin/pembimbinglapangan/{item}/generatepassword', [adminPembimbingLapanganController::class, 'generatepassword'])->name('admin.pembimbinglapangan.generatepassword');
    Route::post('/admin/pembimbinglapangan/generatepasswordall', [adminPembimbingLapanganController::class, 'generatepasswordall'])->name('admin.pembimbinglapangan.generatepasswordall');


    Route::get('/admin/pembimbingsekolah', [adminPembimbingSekolahController::class, 'index'])->name('admin.pembimbingsekolah');
    Route::post('/admin/pembimbingsekolah/store', [adminPembimbingSekolahController::class, 'store'])->name('admin.pembimbingsekolah.store');
    Route::get('/admin/pembimbingsekolah/{item}', [adminPembimbingSekolahController::class, 'edit'])->name('admin.pembimbingsekolah.edit');
    Route::put('/admin/pembimbingsekolah/{item}', [adminPembimbingSekolahController::class, 'update'])->name('admin.pembimbingsekolah.update');
    Route::delete('/admin/pembimbingsekolah/{item}', [adminPembimbingSekolahController::class, 'destroy'])->name('admin.pembimbingsekolah.destroy');
    Route::put('/admin/pembimbingsekolah/{item}/generatepassword', [adminPembimbingSekolahController::class, 'generatepassword'])->name('admin.pembimbingsekolah.generatepassword');
    Route::post('/admin/pembimbingsekolah/generatepasswordall', [adminPembimbingSekolahController::class, 'generatepasswordall'])->name('admin.pembimbingsekolah.generatepasswordall');


    Route::get('/admin/pendaftaranprakerin', [adminPendaftaranPrakerinController::class, 'index'])->name('admin.pendaftaranprakerin');
    Route::post('/admin/pendaftaranprakerin/store', [adminPendaftaranPrakerinController::class, 'store'])->name('admin.pendaftaranprakerin.store');
    Route::get('/admin/pendaftaranprakerin/{item}', [adminPendaftaranPrakerinController::class, 'edit'])->name('admin.pendaftaranprakerin.edit');
    Route::put('/admin/pendaftaranprakerin/{item}', [adminPendaftaranPrakerinController::class, 'update'])->name('admin.pendaftaranprakerin.update');
    Route::delete('/admin/pendaftaranprakerin/{item}', [adminPendaftaranPrakerinController::class, 'destroy'])->name('admin.pendaftaranprakerin.destroy');

    Route::get('/admin/pendaftaranprakerin/{data}/pendaftaranprakerin_detail', [adminPendaftaranPrakerinDetailController::class, 'index'])->name('admin.pendaftaranprakerin_detail');
    Route::post('/admin/pendaftaranprakerin/{data}/pendaftaranprakerin_detail/store', [adminPendaftaranPrakerinDetailController::class, 'store'])->name('admin.pendaftaranprakerin_detail.store');
    Route::get('/admin/pendaftaranprakerin_detail/{item}', [adminPendaftaranPrakerinDetailController::class, 'edit'])->name('admin.pendaftaranprakerin_detail.edit');
    Route::put('/admin/pendaftaranprakerin_detail/{item}', [adminPendaftaranPrakerinDetailController::class, 'update'])->name('admin.pendaftaranprakerin_detail.update');
    Route::delete('/admin/pendaftaranprakerin_detail/{item}', [adminPendaftaranPrakerinDetailController::class, 'destroy'])->name('admin.pendaftaranprakerin_detail.destroy');

    Route::post('/admin/pendaftaranprakerin/{data}/pendaftaranprakerin_detail/{item}/ubahstatus', [adminPendaftaranPrakerinDetailController::class, 'ubahstatus'])->name('admin.pendaftaranprakerin_detail.ubahstatus');

    //pendaftaranprakerin List
    Route::get('/admin/pendaftaranpkl/list/menunggu', [adminPendaftaranPrakerinListController::class, 'menunggu'])->name('admin.pendaftaranprakerin.list.menunggu');
    Route::get('/admin/pendaftaranpkl/list/disetujui', [adminPendaftaranPrakerinListController::class, 'disetujui'])->name('admin.pendaftaranprakerin.list.disetujui');
    Route::get('/admin/pendaftaranpkl/list/getall', [adminPendaftaranPrakerinListController::class, 'getall'])->name('admin.pendaftaranprakerin.list.getall');
    Route::get('/admin/pendaftaranpkl/list/subsidebardata', [adminPendaftaranPrakerinListController::class, 'subsidebardata'])->name('admin.pendaftaranprakerin.list.subsidebardata');
});

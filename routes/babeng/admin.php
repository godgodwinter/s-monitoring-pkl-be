<?php

use App\Http\Controllers\admin\adminKelasController;
use App\Http\Controllers\admin\adminSiswaController;
use App\Http\Controllers\admin\adminTapelController;
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
});

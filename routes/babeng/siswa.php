<?php

use App\Http\Controllers\AuthSiswaController;
use Illuminate\Support\Facades\Route;



Route::middleware('api')->group(function () {
    //menu-portofolio
    Route::post('/siswa/auth/register', [AuthSiswaController::class, 'register'])->name('siswa.auth.register');
    Route::post('/siswa/auth/login', [AuthSiswaController::class, 'login'])->name('siswa.auth.login');
    Route::post('/siswa/auth/logout', [AuthSiswaController::class, 'logout'])->name('siswa.auth.logout');
    Route::post('/siswa/auth/refresh', [AuthSiswaController::class, 'refresh'])->name('siswa.auth.refresh');
    Route::post('/siswa/auth/me', [AuthSiswaController::class, 'me'])->name('siswa.auth.me');

});

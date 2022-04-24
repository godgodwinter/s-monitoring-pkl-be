<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;



Route::middleware('api')->group(function () {
    //menu-portofolio
    Route::post('/admin/auth/register', [AuthController::class, 'register'])->name('admin.auth.register');
    Route::post('/admin/auth/login', [AuthController::class, 'login'])->name('admin.auth.login');
    Route::post('/admin/auth/logout', [AuthController::class, 'logout'])->name('admin.auth.logout');
    Route::post('/admin/auth/refresh', [AuthController::class, 'refresh'])->name('admin.auth.refresh');
    Route::post('/admin/auth/me', [AuthController::class, 'me'])->name('admin.auth.me');

});


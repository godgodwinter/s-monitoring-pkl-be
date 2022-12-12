<?php

use App\Http\Controllers\admin\adminPengumumanController;
use App\Http\Controllers\guest\guestSettingsController;
use Illuminate\Support\Facades\Route;



Route::get('/guest/pengumuman', [adminPengumumanController::class, 'guest_index']);
Route::get('/guest/pengumuman/{item}', [adminPengumumanController::class, 'edit']);

Route::get('/guest/settings/bataswaktu', [guestSettingsController::class, 'index']);

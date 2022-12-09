<?php

use App\Http\Controllers\admin\adminPengumumanController;
use Illuminate\Support\Facades\Route;



Route::get('/guest/pengumuman', [adminPengumumanController::class, 'guest_index']);
Route::get('/guest/pengumuman/{item}', [adminPengumumanController::class, 'edit']);

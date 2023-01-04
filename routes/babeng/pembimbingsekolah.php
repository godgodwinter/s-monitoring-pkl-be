<?php

use App\Http\Controllers\admin\adminPendaftaranPrakerinListController;
use App\Http\Controllers\admin\AdminPenilaianController;
use App\Http\Controllers\admin\adminSiswaController;
use App\Http\Controllers\admin\adminTempatPklController;
use App\Http\Controllers\AuthPembimbingSekolah;
use App\Http\Controllers\guru\guruSiswaController;
use App\Http\Controllers\pembimbingsekolah\guruDatakuController;
use App\Http\Controllers\pembimbingsekolah\guruPenilaianController;
use App\Http\Controllers\pembimbingsekolah\guruPenilaianGuruController;
use App\Http\Controllers\pembimbingsekolah\guruPenilaianPembimbingLapanganController;
use App\Http\Controllers\siswa\siswaAbsensiController;
use App\Http\Controllers\siswa\siswaPendaftaranPKLController;
use App\Http\Controllers\siswa\siswaProfileController;
use App\Http\Controllers\siswa\siswaSettingsController;
use Illuminate\Support\Facades\Route;



// Route::middleware('api')->group(function () {
Route::post('/pembimbingsekolah/auth/login', [AuthPembimbingSekolah::class, 'login']);
Route::post('/pembimbingsekolah/auth/logout', [AuthPembimbingSekolah::class, 'logout']);
Route::middleware('auth:pembimbingsekolah')->group(function () {
    Route::post('/pembimbingsekolah/auth/refresh', [AuthPembimbingSekolah::class, 'refresh']);
    Route::post('/pembimbingsekolah/auth/me', [AuthPembimbingSekolah::class, 'me']);
    Route::post("pembimbingsekolah/auth/profile", [AuthPembimbingSekolah::class, 'refresh']);


    Route::get('/pembimbingsekolah/settings/get', [siswaSettingsController::class, 'index']);
    Route::get('/pembimbingsekolah/profile/get', [siswaProfileController::class, 'index']);


    // MENU GURU
    Route::get('/guru/dataku/tempatpkl', [guruDatakuController::class, 'tempatpkl']);
    Route::get('/guru/dataku/tempatpkldetail/{item}', [guruDatakuController::class, 'tempatpkl_detail']);
    Route::get('/guru/dataku/siswa', [guruDatakuController::class, 'siswa']);
    Route::get('/guru/dataku/penilaian', [guruDatakuController::class, 'index']);

    Route::get('/guru/dataku/penilai/tempatpkl', [guruDatakuController::class, 'penilai_tempatpkl']);
    Route::get('/guru/dataku/penilai/siswa', [guruDatakuController::class, 'penilai_siswa']);


    // MENU KEPALA JURUSAN
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




    //pendaftaranprakerin List
    Route::get('/guru/pendaftaranpkl/list/periksaidbaru/{siswa_id}', [adminPendaftaranPrakerinListController::class, 'periksaidbaru'])->name('admin.pendaftaranprakerin.list.periksaid');
    Route::get('/guru/pendaftaranpkl/list/periksaid/{id}', [adminPendaftaranPrakerinListController::class, 'periksaid'])->name('admin.pendaftaranprakerin.list.periksaid');
    Route::get('/guru/pendaftaranpkl/list/getall', [adminPendaftaranPrakerinListController::class, 'getall'])->name('admin.pendaftaranprakerin.list.getall');
    Route::get('/guru/pendaftaranpkl/list/gettelahdaftar', [adminPendaftaranPrakerinListController::class, 'getSiswaTelahDaftar']);
    Route::get('/guru/pendaftaranpkl/list/getbelumdaftar', [adminPendaftaranPrakerinListController::class, 'getSiswaBelumDaftar']);
    Route::get('/guru/pendaftaranpkl/list/getsiswapilihtempat/{tempatpkl}', [adminPendaftaranPrakerinListController::class, 'getSiswaPilihTempat']);


    Route::get('/guru/pendaftaranpkl/list/prosespengajuantempatpkl', [adminPendaftaranPrakerinListController::class, 'getProsesPengajuanTempatPKL']);
    Route::get('/guru/pendaftaranpkl/list/prosespenempatanpkl', [adminPendaftaranPrakerinListController::class, 'getProsesPenempatanPKL']);
    Route::get('/guru/pendaftaranpkl/list/prosespemberkasan', [adminPendaftaranPrakerinListController::class, 'getProsesPemberkasan']);
    Route::get('/guru/pendaftaranpkl/list/prosespersetujuan', [adminPendaftaranPrakerinListController::class, 'getProsesPersetujuan']);
    Route::get('/guru/pendaftaranpkl/list/disetujui', [adminPendaftaranPrakerinListController::class, 'getDisetujui']);
    Route::post('/guru/pendaftaranpkl/list/disetujui/addpembimbingsekolah/{pendaftaranprakerin_proses}', [adminPendaftaranPrakerinListController::class, 'addpembimbingsekolah']);
    Route::get('/guru/pendaftaranpkl/list/ditolak', [adminPendaftaranPrakerinListController::class, 'getDitolak']);

    Route::get('/guru/pendaftaranpkl/getdatatempatpkl', [adminPendaftaranPrakerinListController::class, 'getDataTempatPKL']);
    Route::get('/guru/pendaftaranpkl/getdatasiswa', [adminPendaftaranPrakerinListController::class, 'getDataSiswa']);
    // Route::get('/admin/pendaftaranpkl/list/prosesdaftar', [adminPendaftaranPrakerinListController::class, 'prosesdaftar'])->name('admin.pendaftaranprakerin.list.prosesdaftar');
    // Route::get('/admin/pendaftaranpkl/list/menunggu', [adminPendaftaranPrakerinListController::class, 'menunggu'])->name('admin.pendaftaranprakerin.list.menunggu');
    // Route::get('/admin/pendaftaranpkl/list/belumdaftar', [adminPendaftaranPrakerinListController::class, 'belumdaftar'])->name('admin.pendaftaranprakerin.list.belumdaftar');
    // Route::get('/admin/pendaftaranpkl/list/disetujui', [adminPendaftaranPrakerinListController::class, 'disetujui'])->name('admin.pendaftaranprakerin.list.disetujui');
    Route::get('/guru/pendaftaranpkl/list/subsidebardata', [adminPendaftaranPrakerinListController::class, 'subsidebardata'])->name('admin.pendaftaranprakerin.list.subsidebardata');

    Route::get('/guru/pendaftaranpkl/list/getpilihanlankah2', [adminPendaftaranPrakerinListController::class, 'getpilihanlankah2'])->name('admin.pendaftaranprakerin.list.getpilihanlankah2');
});



Route::get('/guru/tempatpkl', [adminTempatPklController::class, 'index'])->name('admin.tempatpkl');

Route::get('/guru/siswa/{item}', [adminSiswaController::class, 'edit'])->name('admin.siswa.edit');
Route::get('/guru/datasiswa/profile/{item}', [adminSiswaController::class, 'profile'])->name('admin.siswa.profile');

Route::get('/guru/penilai/siswadetail/{item}', [guruSiswaController::class, 'siswadetail']);
Route::post('/guru/penilai/siswadetail/{item}/absensi', [guruSiswaController::class, 'store_nilai_absensi']);
Route::post('/guru/penilai/siswadetail/{item}/jurnal', [guruSiswaController::class, 'store_nilai_jurnal']);
Route::post('/guru/penilai/siswadetail/{item}/penilaian_guru', [guruSiswaController::class, 'store_nilai_penilaian_guru']);

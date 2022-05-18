<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class siswaPendaftaranPKLController extends Controller
{
    public function daftar()
    {
        // insert data siswa
        $items = [];
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function pengajuantempatpkl(Request $request)
    {
        // insert 2 tempat pkl yang dipilih siswa
        $items = 'Data berhasil ditambahkan';
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function uploadberkas(Request $request)
    {
        // insert SURAT BALASAN DARI TEMPAT PKL
        $items = 'Data berhasil ditambahkan';
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }


    public function getDataTempatPKL(Request $request)
    {

        // get identitas tempat pkl dan teman yang berada di tempat pkl yang sama serta status pengajuan diacc/ditolak
        $items = 'Data berhasil ditambahkan';
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function guard()
    {
        return Auth::guard('siswa');
    }
    public function me()
    {
        return response()->json($this->guard()->user());
    }
}

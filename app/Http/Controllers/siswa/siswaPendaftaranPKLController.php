<?php

namespace App\Http\Controllers\siswa;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pendaftaranprakerin;
use App\Models\tempatpkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class siswaPendaftaranPKLController extends Controller
{
    public function daftar(Request $request)
    {
        // insert data siswa
        $items = [];
        $kode = 500;
        // $validator = Validator::make($request->all(), [
        //     'siswa_id'   => 'required',
        // ]);
        // //response error validation
        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 400);
        // }
        $tgl_daftar = date('Y-m-d');
        $siswa_id = $this->guard()->user()->id;
        $periksa = pendaftaranprakerin::firstOrCreate([
            'siswa_id'     =>   $siswa_id,
            'tgl_daftar'     =>   $tgl_daftar,
            'status'     =>   'Proses Daftar',
            'tapel_id'     =>   Fungsi::app_tapel_aktif()
        ]);
        if ($periksa->wasRecentlyCreated) {
            $items = 'Data berhasil ditambahkan!';
            $kode = 200;
        } else {
            $items = 'Data ditemukan ! Data gagal ditambahkan!';
        }
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], $kode);
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
        $cari = $request->cari;
        $items = tempatpkl::where('nama', 'like', "%" . $cari . "%")->get();
        foreach ($items as $item) {
            $item['tersedia'] = 4;
        }
        // get identitas tempat pkl dan teman yang berada di tempat pkl yang sama serta status pengajuan diacc/ditolak
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

<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pendaftaranprakerin;
use App\Models\pendaftaranprakerin_proses;
use App\Models\pendaftaranprakerin_prosesdetail;
use App\Models\Siswa;
use App\Models\tempatpkl;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class adminPendaftaranPrakerinController extends Controller
{
    public function daftar(Siswa $id, Request $request)
    {
        // insert data siswa
        $items = [];
        $kode = 500;
        $tgl_daftar = date('Y-m-d');
        $siswa_id = $id->id;
        $periksa = pendaftaranprakerin::firstOrCreate([
            'siswa_id'     =>   $siswa_id,
            'tgl_daftar'     =>   $tgl_daftar,
            'status'     =>   'Proses Pengajuan Tempat PKL',
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
        ], $kode);
    }

    public function prosesPenempatanPkl(tempatpkl $tempatpkl, Request $request)
    {
        $items = [];
        $kode = 500;
        // periksa apakah tempat pkl masih belum ada
        $periksa = pendaftaranprakerin_proses::where('tempatpkl_id', $tempatpkl->id)->count();
        // jika belum ada maka insert
        if ($periksa < 1) {
            $pendaftaranprakerin_proses_id = DB::table('pendaftaranprakerin_proses')->insertGetId([
                'tempatpkl_id'     =>   $tempatpkl->id,
                'tapel_id'     =>   Fungsi::app_tapel_aktif(),
                'created_at' =>   Carbon::now(),
                'updated_at' =>   Carbon::now(),

            ]);
        } else {
            $getPendaftaranProses = pendaftaranprakerin_proses::where('tempatpkl_id', $tempatpkl->id)->first();
            $pendaftaranprakerin_proses_id = $getPendaftaranProses->id;
            DB::table('pendaftaranprakerin_prosesdetail')->where('pendaftaranprakerin_proses_id', $pendaftaranprakerin_proses_id)->delete();
        }
        // a.get proses_id
        // b. insert siswa ke tapel prosesdetail
        foreach ($request->siswa as $siswa) {

            $kode = 200;

            pendaftaranprakerin_prosesdetail::insert([
                'pendaftaranprakerin_proses_id'     =>   $pendaftaranprakerin_proses_id,
                'siswa_id'     =>   $siswa['id'],
                'created_at' =>   Carbon::now(),
                'updated_at' =>   Carbon::now(),
            ]);
            // c. update status tiap siswa menjadi Proses Pemberkasan
            pendaftaranprakerin::where('siswa_id', $siswa['id'])->where('tapel_id', Fungsi::app_tapel_aktif())
                ->update([
                    'status'     =>   'Proses Pemberkasan',
                    'updated_at' =>   Carbon::now(),
                ]);
        }

        // jika sudah ada
        // a. jika belum penuh maka periksa tiap siswa
        // 1. jika belum maka insert
        // 2. jika sudah ada maka biarkan


        // b, jika sudah penuh maka periksa apakah sudah penuh maka tampilkan error
        return response()->json([
            'success'    => true,
            'data'    => $items,
            'siswa'    => [],
            'tempatpkl'    => $periksa,
            'request'    => $request->dataSiswa,
        ], $kode);
    }
    public function prosesPenempatanPklGet(pendaftaranprakerin_proses $pendaftaranprakerin_proses, Request $request)
    {
        $items = [];
        $tempatpkl = [];
        $siswa = [];
        $kode = 500;
        // periksa apakah tempat pkl masih belum ada
        $periksa = pendaftaranprakerin_proses::where('tempatpkl_id', $pendaftaranprakerin_proses->tempatpkl_id)->count();
        // jika belum ada maka tampilkan error
        if ($periksa > 0) {
            $kode = 200;
            $items = pendaftaranprakerin_proses::with('tempatpkl')->where('tempatpkl_id', $pendaftaranprakerin_proses->tempatpkl_id)->where('tapel_id', Fungsi::app_tapel_aktif())->first();
            $siswa = pendaftaranprakerin_prosesdetail::with('siswa')
                ->where('pendaftaranprakerin_proses_id', $pendaftaranprakerin_proses->id)
                ->get();
            $tempatpkl = tempatpkl::where('id', $pendaftaranprakerin_proses->id)->first();
        }
        // jika sudah ada
        // a. ambil tempatpkl , tambahkan jumlah kuota dan tersedia
        // 1. ambil siswa yang pkl di tempat tersebut


        return response()->json([
            'success'    => true,
            'data'    => $items,
            'siswa'    => $siswa,
            'tempatpkl'    => $tempatpkl,
        ], $kode);
    }
    public function prosesPenempatanPklUploadBerkas(pendaftaranprakerin_proses $pendaftaranprakerin_proses, Request $request)
    {
        $items = [];
        $kode = 500;
        $getSiswaId = pendaftaranprakerin_prosesdetail::with('siswa')->where('pendaftaranprakerin_proses_id', $pendaftaranprakerin_proses->id)->get();
        foreach ($getSiswaId as $dSiswa) {
            pendaftaranprakerin::where('siswa_id', $dSiswa->siswa_id)->where('tapel_id', Fungsi::app_tapel_aktif())
                ->update([
                    'status'     =>   'Proses Persetujuan',
                    'updated_at' =>   Carbon::now(),
                ]);
            $kode = 200;
        }
        // upload berkas
        return response()->json([
            'success'    => true,
            'data'    => $items,
            'siswa'    => $getSiswaId,
        ], $kode);
    }
    public function prosesPenempatanPklPersetujuan(pendaftaranprakerin_proses $pendaftaranprakerin_proses, Request $request)
    {
        $items = [];
        $kode = 500;
        $getSiswaId = [];
        if ($request->status == 'Disetujui') {

            $getSiswaId = pendaftaranprakerin_prosesdetail::with('siswa')->where('pendaftaranprakerin_proses_id', $pendaftaranprakerin_proses->id)->get();
            foreach ($getSiswaId as $dSiswa) {
                pendaftaranprakerin::where('siswa_id', $dSiswa->siswa_id)->where('tapel_id', Fungsi::app_tapel_aktif())
                    ->update([
                        'status'     =>   'Disetujui',
                        'updated_at' =>   Carbon::now(),
                    ]);
                $kode = 200;
            }
        }
        return response()->json([
            'success'    => true,
            'data'    => $items,
            'siswa'    => $getSiswaId,
        ], $kode);
    }
    public function index(Request $request)
    {
        $items = pendaftaranprakerin::where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }

    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'siswa_id'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::table('pendaftaranprakerin')->insert(
            array(
                'siswa_id'     =>   $request->siswa_id,
                'tgl_daftar'     =>   $request->tgl_daftar,
                'status'     =>   'Proses Daftar',
                'tapel_id'     =>   Fungsi::app_tapel_aktif(),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil ditambahkan!',
        ], 200);
    }

    public function edit(pendaftaranprakerin $item)
    {
        return response()->json([
            'success'    => true,
            'data'    => $item,
        ], 200);
    }
    public function update(pendaftaranprakerin $item, Request $request)
    {

        //set validation
        $validator = Validator::make($request->all(), [
            'siswa_id'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        pendaftaranprakerin::where('id', $item->id)
            ->update([
                'siswa_id'     =>   $request->siswa_id,
                'tgl_daftar'     =>   $request->tgl_daftar,
                'updated_at' => date("Y-m-d H:i:s")
            ]);


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
        ], 200);
    }
    public function destroy(pendaftaranprakerin $item)
    {

        pendaftaranprakerin::destroy($item->id);
        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di hapus!',
        ], 200);
    }

    public function getsiswafromtempatpkl(Request $request)
    {
        $items = [];
        $kode = 500;
        $siswa = [];
        $tempatpkl = [];
        $periksa = pendaftaranprakerin_proses::with('tempatpkl')->where('tempatpkl_id', $request->tempatpkl_id)
            ->where('tapel_id', Fungsi::app_tapel_aktif());
        if ($periksa->count()) {
            $kode = 200;
            $items = $periksa->get();
            $periksasiswa = pendaftaranprakerin_prosesdetail::with('siswa')->where('pendaftaranprakerin_proses_id', $periksa->first()->id);
            if ($periksasiswa->count()) {
                $siswa = $periksasiswa->get();
            }
        } else {
            $kode = 200;
            $items = "Tempat PKL belum memiliki siswa";
        }
        return response()->json([
            'success'    => true,
            'data'    => $items,
            'siswa'    => $siswa,
        ], $kode);
    }
}

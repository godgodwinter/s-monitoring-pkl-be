<?php

namespace App\Http\Controllers\siswa;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pendaftaranprakerin;
use App\Models\pendaftaranprakerin_detail;
use App\Models\pendaftaranprakerin_proses;
use App\Models\tempatpkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class siswaPendaftaranPKLController extends Controller
{
    protected $siswa_id = null;
    public function getStatusPKL(Request $request)
    {
        $this->siswa_id = $this->guard()->user()->id;
        $siswa_id = $this->guard()->user()->id;
        $id = $this->guard()->user()->id;

        // dd($this->siswa_id);
        // insert data siswa
        $items = [];
        $getTempatpkl = null;
        $getPembimbinglapangan = null;
        $getPembimbingSekolah = null;
        $getDataDetail = null;
        $periksa = "Belum Daftar";
        $kode = 500;
        $tgl_daftar = date('Y-m-d');
        $tapel_id = Fungsi::app_tapel_aktif();
        $getData = [];
        $jmlData = pendaftaranprakerin::with('pendaftaranprakerin_detail')->where('tapel_id', Fungsi::app_tapel_aktif())->where('siswa_id', $id)->count();
        $tgl_pengajuan = null;
        if ($jmlData > 0) {
            //get pendaftaranprakerin_proses where tapel_id sekarang and get siswa
            $get_pendaftaranprakerin = pendaftaranprakerin::where('tapel_id', Fungsi::app_tapel_aktif())->where('siswa_id', $id)->first();
            $periksa = $get_pendaftaranprakerin->status;
        }
        if ($periksa != 'Belum Daftar') {

            $kode = 200;
            //periksa penempatan di tabel pendaftaranprakerin_proses dan pendaftaranprakerin_prosesdetail
            $getData = pendaftaranprakerin_proses::with('pendaftaranprakerin_prosesdetail')
                ->where('tapel_id', Fungsi::app_tapel_aktif())
                ->whereHas('pendaftaranprakerin_prosesdetail', function ($query) {
                    $query->where('siswa_id', $this->siswa_id);
                })
                ->first();

            //periksa pembimbing di table pendaftaranprakerin_prosesdetail_detail
            $get_jml_pendaftaranprakerin_detail = pendaftaranprakerin_detail::where('pendaftaranprakerin_id', $get_pendaftaranprakerin->id)->count();
            if ($get_jml_pendaftaranprakerin_detail > 0) {
                $get_pendaftaranprakerin_detail = pendaftaranprakerin_detail::with('pembimbingsekolah')->with('pembimbinglapangan')->where('pendaftaranprakerin_id', $get_pendaftaranprakerin->id)->first();
                $getPembimbinglapangan = $get_pendaftaranprakerin_detail->pembimbinglapangan ? $get_pendaftaranprakerin_detail->pembimbinglapangan->nama : null;
                $getPembimbingSekolah = $get_pendaftaranprakerin_detail->pembimbingsekolah ? $get_pendaftaranprakerin_detail->pembimbingsekolah->nama : null;
            }
        }
        return response()->json([
            'success'    => true,
            'data'    => $getData,
            'status'    => $periksa,
            'tgl_pengajuan'    => $tgl_pengajuan,
            // 'detail' => $getDataDetail,
            'tempatpkl' => $getTempatpkl,
            'pembimbinglapangan' => $getPembimbinglapangan,
            'pembimbingsekolah' => $getPembimbingSekolah,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], $kode);
    }
    public function daftar(Request $request)
    {
        // insert data siswa
        $items = [];
        $kode = 500;
        $tgl_daftar = date('Y-m-d');
        $siswa_id = $this->guard()->user()->id;
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

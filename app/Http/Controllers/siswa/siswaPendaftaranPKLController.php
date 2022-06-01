<?php

namespace App\Http\Controllers\siswa;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pendaftaranprakerin;
use App\Models\pendaftaranprakerin_detail;
use App\Models\pendaftaranprakerin_pengajuansiswa;
use App\Models\pendaftaranprakerin_proses;
use App\Models\pendaftaranprakerin_prosesdetail;
use App\Models\tempatpkl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class siswaPendaftaranPKLController extends Controller
{
    protected $siswa_id = null;
    // construct
    public function __construct()
    {
        $this->siswa_id =  $this->guard()->user()->id;
    }

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
            $kode = 200;
            $items = 'Data ditemukan ! Data gagal ditambahkan!';
        }
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], $kode);
    }
    public function pengajuanTempatPklGet(Request $request)
    {
        // insert 2 tempat pkl yang dipilih siswa
        $items = [];
        // $items = 'Data berhasil ditambahkan';
        $items = pendaftaranprakerin::with('pendaftaranprakerin_pengajuansiswa')->where('siswa_id', $this->siswa_id)->where('tapel_id', Fungsi::app_tapel_aktif())->first();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function pengajuanTempatPklStore(Request $request)
    {
        // get pendaftaranprakerin where siswa id
        $getPendaftaranprakerin = pendaftaranprakerin::where('siswa_id', $this->siswa_id)->where('tapel_id', Fungsi::app_tapel_aktif())->first();
        $pendaftaranprakerin_id = $getPendaftaranprakerin->id;
        // 1. periksa apakah data pengajuan sudah ada
        $periksa = pendaftaranprakerin_pengajuansiswa::where('pendaftaranprakerin_id', $pendaftaranprakerin_id)->count();
        if ($periksa > 0) {
            pendaftaranprakerin_pengajuansiswa::where('pendaftaranprakerin_id', $pendaftaranprakerin_id)->delete();
        }
        //  jika ada maka delete and insert
        foreach ($request->tempatpkl as $data) {
            pendaftaranprakerin_pengajuansiswa::insert([
                'tempatpkl_id' => $data['id'],
                'pendaftaranprakerin_id' => $pendaftaranprakerin_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')

            ]);

            pendaftaranprakerin::where('siswa_id', $this->siswa_id)
                ->where('tapel_id', Fungsi::app_tapel_aktif())
                ->update([
                    'status'     =>   'Proses Penempatan PKL',
                    'updated_at' =>   Carbon::now(),
                ]);
        }

        // 2. jika tidak ada maka insert

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

        $file = $request->file('file');
        $success = false;
        $items = 'Data tidak ditemukan';
        $this->siswa_id = $this->guard()->user()->id;
        $periksa = 'Belum Daftar';
        $periksaPendaftaranPrakerin = pendaftaranprakerin::with('pendaftaranprakerin_detail')->where('tapel_id', Fungsi::app_tapel_aktif())->where('siswa_id', $this->siswa_id);
        $getPendaftaranPrakerin = pendaftaranprakerin_proses::with('pendaftaranprakerin_prosesdetail')
            ->whereHas('pendaftaranprakerin_prosesdetail', function ($query) {
                $query->where('siswa_id', $this->siswa_id);
            })->where('tapel_id', Fungsi::app_tapel_aktif());
        if ($getPendaftaranPrakerin->count() > 0) {
            // upload
            $UploadDir = public_path() . '/fileupload/suratbalasan';
            // $nama_file = rand() . "-" . $file->getClientOriginalName();
            $nama_file = $getPendaftaranPrakerin->first()->id . '.' . $file->extension();
            // dd($nama_file);

            $file->move($UploadDir, $nama_file);
            $success = true;
            $items = 'Berkas berhasil diupload';
        }

        return response()->json([
            'success'    => $success,
            'data'    => $items,
        ], 200);
    }
    protected $tempatpkl_id;
    public function getDataTempatPKL(Request $request)
    {
        $cari = $request->cari;
        $tersedia = $request->tersedia; //Tersedia atau Tidak Tersedia atau Semua Data
        $items = tempatpkl::where('nama', 'like', "%" . $cari . "%")->get();
        $data = [];
        foreach ($items as $item) {
            $this->tempatpkl_id = $item->id;
            $periksa = pendaftaranprakerin_prosesdetail::with('pendaftaranprakerin_proses')
                ->whereHas('pendaftaranprakerin_proses', function ($query) {
                    $query->where('tapel_id', Fungsi::app_tapel_aktif())->where('tempatpkl_id', $this->tempatpkl_id);
                })
                ->count();
            $item['terisi'] = $periksa;
            // $item['tersedia'] = 0;
            $item['tersedia'] = $item->kuota - $periksa;
            if ($tersedia == 'Tersedia') {
                if ($item['tersedia'] > 0) {
                    array_push($data, $item);
                }
            } else if ($tersedia == 'Tidak Tersedia') {
                if ($item['tersedia'] < 1) {
                    array_push($data, $item);
                }
            } else {
                array_push($data, $item);
            }
        }
        // get identitas tempat pkl dan teman yang berada di tempat pkl yang sama serta status pengajuan diacc/ditolak
        return response()->json([
            'success'    => true,
            'data'    => $data,
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

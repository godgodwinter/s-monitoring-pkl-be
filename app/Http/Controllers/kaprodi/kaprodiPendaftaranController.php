<?php

namespace App\Http\Controllers\kaprodi;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\jurusan;
use App\Models\pendaftaranprakerin;
use App\Models\pendaftaranprakerin_proses;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class kaprodiPendaftaranController extends Controller
{
    // constuct
    protected $siswa_id;
    protected $guru_id;
    protected $me;
    public function __construct()
    {
        $this->guru_id =  $this->guard()->user()->id;
        $this->me =  $this->me();
    }
    public function me()
    {
        $result = null;
        $periksaPenilai = false;
        $periksaKepalajurusan = false;
        $getJurusan = [];
        $me = $this->guard()->user();
        $periksa = jurusan::where('kepalajurusan_id', $me->id)
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->count();
        if ($periksa > 0) {
            $periksaKepalajurusan = true;
            $getJurusan = jurusan::where('kepalajurusan_id', $me->id)
                ->where('tapel_id', Fungsi::app_tapel_aktif())
                ->with('kelas')
                ->first();
        }
        $periksa = pendaftaranprakerin_proses::where('penilai_id', $me->id)
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->count();
        if ($periksa > 0) {
            $periksaPenilai = true;
        }
        $result = (object)[
            'me' => $this->guard()->user(),
            'status' => true,
            'kepalajurusan' => $periksaKepalajurusan,
            'penilai' => $periksaPenilai,
            'jurusan' => $getJurusan,
        ];
        return $result;
    }
    public function guard()
    {
        return Auth::guard('pembimbingsekolah');
    }

    public function list_belumdaftar(Request $request)
    {
        $result = collect([]);

        $result = Siswa::with('pendaftaranprakerin')
            ->whereHas('kelasdetail', function ($query) {
                $query->whereHas('kelas', function ($query) {
                    $query->where('kelas.tapel_id', Fungsi::app_tapel_aktif())->where('kelas.jurusan', $this->me->jurusan->id);
                });
            })
            ->whereDoesntHave('pendaftaranprakerin')
            ->get();
        $sorted = $result->sortBy('nama');
        return response()->json([
            'success'    => true,
            'data'    => $sorted,
        ], 200);
    }

    public function list_pengajuan(Request $request)
    {
        $result = collect([]);

        $sorted = $result;
        $getDataProses = pendaftaranprakerin::with('siswa')->where('status', 'Proses Pengajuan Tempat PKL')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        foreach ($getDataProses as $data) {
            $jurusan = $data->siswa ? $data->siswa->kelasdetail->kelas->jurusan_table : null;
            $jurusan_id = $jurusan ? $jurusan->id : null;
            if ($jurusan_id == $this->me->jurusan->id) {
                $result[] = $data;
            }
            $sorted = $result;
        }
        return response()->json([
            'success'    => true,
            'data'    => $sorted,
        ], 200);
    }

    public function list_penempatan(Request $request)
    {
        $result = collect([]);
        $sorted = $result;
        $getDataProses = pendaftaranprakerin::with('siswa')->where('status', 'Proses Penempatan PKL')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        foreach ($getDataProses as $data) {
            $jurusan = $data->siswa ? $data->siswa->kelasdetail->kelas->jurusan_table : null;
            $jurusan_id = $jurusan ? $jurusan->id : null;
            if ($jurusan_id == $this->me->jurusan->id) {
                $result[] = $data;
            }
            $sorted = $result;
        }
        return response()->json([
            'success'    => true,
            'data'    => $sorted,
        ], 200);
    }

    public function list_pemberkasan(Request $request)
    {
        $result = collect([]);
        $sorted = $result;
        $getDataProses = pendaftaranprakerin::with('siswa')->where('status', 'Proses Pemberkasan')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        foreach ($getDataProses as $data) {
            $jurusan = $data->siswa ? $data->siswa->kelasdetail->kelas->jurusan_table : null;
            $jurusan_id = $jurusan ? $jurusan->id : null;
            if ($jurusan_id == $this->me->jurusan->id) {
                $result[] = $data;
            }
            $sorted = $result;
        }
        return response()->json([
            'success'    => true,
            'data'    => $sorted,
        ], 200);
    }

    public function list_persetujuan(Request $request)
    {
        $result = collect([]);
        $sorted = $result;
        $getDataProses   = pendaftaranprakerin::with('siswa')->where('status', 'Proses Persetujuan')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        foreach ($getDataProses as $data) {
            $jurusan = $data->siswa ? $data->siswa->kelasdetail->kelas->jurusan_table : null;
            $jurusan_id = $jurusan ? $jurusan->id : null;
            if ($jurusan_id == $this->me->jurusan->id) {
                $result[] = $data;
            }
            $sorted = $result;
        }
        return response()->json([
            'success'    => true,
            'data'    => $sorted,
        ], 200);
    }

    public function list_disetujui(Request $request)
    {
        $result = collect([]);
        $sorted = $result;

        $getDataProses = pendaftaranprakerin::with('siswa')->where('status', 'Disetujui')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        foreach ($getDataProses as $item) {
            $this->siswa_id = $item->siswa_id;
            // $getpendaftaranprakerin_prosesdetailId = pendaftaranprakerin_prosesdetail::where('siswa_id', $item->siswa_id)->first();
            // $getpendaftaranprakerin_prosesId = pendaftaranprakerin_proses::first();
            $getpendaftaranprakerin_prosesId = pendaftaranprakerin_proses::with('pendaftaranprakerin_prosesdetail')
                ->with('pembimbingsekolah')
                ->with('penilai')
                ->whereHas('pendaftaranprakerin_prosesdetail', function ($query) {
                    $query->where('siswa_id', $this->siswa_id);
                })
                ->first();
            $item->pembimbingsekolah = $getpendaftaranprakerin_prosesId ? $getpendaftaranprakerin_prosesId->pembimbingsekolah : null;
            $item->pembimbingsekolah_nama = $item->pembimbingsekolah ? $item->pembimbingsekolah->nama : null;
            $item->pendaftaranprakerin_proses_id = $getpendaftaranprakerin_prosesId ? $getpendaftaranprakerin_prosesId->id : null;
            $item->penilai = $getpendaftaranprakerin_prosesId ? $getpendaftaranprakerin_prosesId->penilai : null;
            $item->penilai_nama = $item->penilai ? $item->penilai->nama : null;


            $jurusan = $item->siswa ? $item->siswa->kelasdetail->kelas->jurusan_table : null;
            $jurusan_id = $jurusan ? $jurusan->id : null;
            if ($jurusan_id == $this->me->jurusan->id) {
                $result[] = $item;
            }
            $sorted = $result;
        }
        return response()->json([
            'success'    => true,
            'data'    => $sorted,
        ], 200);
    }
}

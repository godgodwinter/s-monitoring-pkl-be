<?php

namespace App\Http\Controllers\kaprodi;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\jurusan;
use App\Models\kelasdetail;
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

    public function fn_belumdaftar()
    {
        $result = collect([]);

        $sorted = $result;
        $getDataProses = Siswa::with('pendaftaranprakerin')
            ->whereHas('kelasdetail', function ($query) {
                $query->whereHas('kelas', function ($query) {
                    $query->where('kelas.tapel_id', Fungsi::app_tapel_aktif());
                });
            })
            ->whereDoesntHave('pendaftaranprakerin')
            ->get();
        foreach ($getDataProses as $data) {
            $jurusan = $data ? $data->kelasdetail->kelas->jurusan_table : null;
            $jurusan_id = $jurusan ? $jurusan->id : null;
            if ($jurusan_id == $this->me->jurusan->id) {
                $result[] = $data;
            }
            $sorted = $result;
        }
        return $sorted;
    }
    public function list_belumdaftar(Request $request)
    {

        return response()->json([
            'success'    => true,
            'data'    => $this->fn_belumdaftar(),
        ], 200);
    }

    public function fn_pengajuan()
    {
        $result = collect([]);

        $sorted = $result;
        $getDataProses = pendaftaranprakerin::with('siswa')->where('status', 'Proses Pengajuan Tempat PKL')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        foreach ($getDataProses as $data) {
            $data->nama = $data->siswa ? $data->siswa->nama : null;
            $data->nomeridentitas = $data->siswa ? $data->siswa->nomeridentitas : null;
            $jurusan = $data->siswa ? $data->siswa->kelasdetail->kelas->jurusan_table : null;
            $jurusan_id = $jurusan ? $jurusan->id : null;
            if ($jurusan_id == $this->me->jurusan->id) {
                $result[] = $data;
            }
            $sorted = $result;
        }
        return $sorted;
    }
    public function list_pengajuan(Request $request)
    {
        return response()->json([
            'success'    => true,
            'data'    => $this->fn_pengajuan(),
        ], 200);
    }

    public function fn_penempatan()
    {
        $result = collect([]);
        $sorted = $result;
        $getDataProses = pendaftaranprakerin::with('siswa')->where('status', 'Proses Penempatan PKL')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        foreach ($getDataProses as $data) {
            $data->nama = $data->siswa ? $data->siswa->nama : null;
            $data->nomeridentitas = $data->siswa ? $data->siswa->nomeridentitas : null;
            $jurusan = $data->siswa ? $data->siswa->kelasdetail->kelas->jurusan_table : null;
            $jurusan_id = $jurusan ? $jurusan->id : null;
            if ($jurusan_id == $this->me->jurusan->id) {
                $result[] = $data;
            }
            $sorted = $result;
        }
        return $sorted;
    }
    public function list_penempatan(Request $request)
    {
        return response()->json([
            'success'    => true,
            'data'    => $this->fn_penempatan(),
        ], 200);
    }

    public function fn_pemberkasan()
    {
        $result = collect([]);
        $sorted = $result;
        $getDataProses = pendaftaranprakerin::with('siswa')->where('status', 'Proses Pemberkasan')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        foreach ($getDataProses as $data) {
            $data->nama = $data->siswa ? $data->siswa->nama : null;
            $data->nomeridentitas = $data->siswa ? $data->siswa->nomeridentitas : null;
            $jurusan = $data->siswa ? $data->siswa->kelasdetail->kelas->jurusan_table : null;
            $jurusan_id = $jurusan ? $jurusan->id : null;
            if ($jurusan_id == $this->me->jurusan->id) {
                $result[] = $data;
            }
            $sorted = $result;
        }
        return $sorted;
    }
    public function list_pemberkasan(Request $request)
    {
        return response()->json([
            'success'    => true,
            'data'    => $this->fn_pemberkasan(),
        ], 200);
    }

    public function fn_persetujuan()
    {
        $result = collect([]);
        $sorted = $result;
        $getDataProses   = pendaftaranprakerin::with('siswa')->where('status', 'Proses Persetujuan')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        foreach ($getDataProses as $data) {
            $data->nama = $data->siswa ? $data->siswa->nama : null;
            $data->nomeridentitas = $data->siswa ? $data->siswa->nomeridentitas : null;
            $jurusan = $data->siswa ? $data->siswa->kelasdetail->kelas->jurusan_table : null;
            $jurusan_id = $jurusan ? $jurusan->id : null;
            if ($jurusan_id == $this->me->jurusan->id) {
                $result[] = $data;
            }
            $sorted = $result;
        }
        return $sorted;
    }
    public function list_persetujuan(Request $request)
    {
        return response()->json([
            'success'    => true,
            'data'    => $this->fn_persetujuan(),
        ], 200);
    }

    public function fn_disetujui()
    {
        $result = collect([]);
        $sorted = $result;

        $getDataProses = pendaftaranprakerin::with('siswa')->where('status', 'Disetujui')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        foreach ($getDataProses as $item) {
            $this->siswa_id = $item->siswa_id;
            $item->nama = $item->siswa ? $item->siswa->nama : null;
            $item->nomeridentitas = $item->siswa ? $item->siswa->nomeridentitas : null;
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
        return $sorted;
    }
    public function list_disetujui(Request $request)
    {
        return response()->json([
            'success'    => true,
            'data'    => $this->fn_disetujui(),
        ], 200);
    }
    public function list_getall(Request $request)
    {
        $result = collect([]);
        $sorted = $result;
        $getDataProses = Siswa::with('pendaftaranprakerin')
            ->whereHas('kelasdetail', function ($query) {
                $query->whereHas('kelas', function ($query) {
                    $query->where('kelas.tapel_id', Fungsi::app_tapel_aktif());
                });
            })
            ->get();
        foreach ($getDataProses as $data) {
            $jurusan = $data ? $data->kelasdetail->kelas->jurusan_table : null;
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


    public function listSubsidebardata(Request $request)
    {

        $result = collect([]);
        $getSiswa = kelasdetail::with('kelas')
            ->with('siswa')
            ->whereHas('kelas', function ($query) {
                $query->where('kelas.jurusan', $this->me->jurusan->id)
                    ->where('tapel_id', Fungsi::app_tapel_aktif());
            })
            ->get();
        foreach ($getSiswa as $siswa) {
            $dataSiswa = $siswa->siswa ? $siswa->siswa : null;
            if ($dataSiswa) {
                $result[] = $dataSiswa;
            }
        }
        $sorted = $result->sortBy('nama');
        $items = [
            'siswa' => $sorted ? $sorted->count() : 0,
            'belumdaftar' => $this->fn_belumdaftar() ? ($this->fn_belumdaftar())->count() : 0,
            'pengajuan' => $this->fn_pengajuan() ? ($this->fn_pengajuan())->count() : 0,
            'penempatan' => $this->fn_penempatan() ? ($this->fn_penempatan())->count() : 0,
            'pemberkasan' => $this->fn_pemberkasan() ? ($this->fn_pemberkasan())->count() : 0,
            'persetujuan' => $this->fn_persetujuan() ? ($this->fn_persetujuan())->count() : 0,
            'disetujui' => $this->fn_disetujui() ? ($this->fn_disetujui())->count() : 0,
            'ditolak' => $this->fn_belumdaftar() ? ($this->fn_belumdaftar())->count() : 0,
            'sedangpkl' =>  0,
            'telahselesai' => 0,
        ];
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }
}

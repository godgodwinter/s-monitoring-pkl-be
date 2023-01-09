<?php

namespace App\Http\Controllers\kaprodi;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\jurusan;
use App\Models\kelasdetail;
use App\Models\pendaftaranprakerin_proses;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class kaprodiDataController extends Controller
{
    // constuct
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
    public function siswa(Request $request)
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
        return response()->json([
            'success'    => true,
            'data'    => $sorted,
        ], 200);
    }
    public function kelas(Request $request)
    {
        $result = collect([]);
        $getKelas = kelasdetail::with('kelas')
            ->with('siswa')
            ->whereHas('kelas', function ($query) {
                $query->where('kelas.jurusan', $this->me->jurusan->id)
                    ->where('tapel_id', Fungsi::app_tapel_aktif());
            })
            ->get();
        foreach ($getKelas as $kelas) {
            $kelas->kelas->kelas_nama = $kelas->kelas ? ($kelas->kelas->tingkatan . " " . $kelas->kelas->jurusan_table->nama . " " . $kelas->kelas->suffix) : null;
            // dd($kelas->kelas);
            $dataKelas = $kelas->kelas ? $kelas->kelas : null;
            if ($dataKelas) {
                $result[] = $dataKelas;
            }
        }
        $sorted = $result->sortBy('nama');
        return response()->json([
            'success'    => true,
            'data'    => $sorted,
        ], 200);
    }
}

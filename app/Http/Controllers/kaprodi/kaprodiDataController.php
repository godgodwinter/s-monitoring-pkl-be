<?php

namespace App\Http\Controllers\kaprodi;

use App\Exports\exportNilaiSiswaPerkelas;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\jurusan;
use App\Models\kelas;
use App\Models\kelasdetail;
use App\Models\pendaftaranprakerin_proses;
use App\Models\penilaian;
use App\Models\penilaian_absensi_dan_jurnal;
use App\Models\penilaian_guru_detail;
use App\Models\penilaian_pembimbinglapangan_detail;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class kaprodiDataController extends Controller
{
    // constuct
    protected $guru_id;
    protected $me;
    protected $kelas;
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
    public function getNilaiPerkelas(kelas $kelas, Request $request)
    {
        $this->kelas = $kelas;
        $result = collect([]);
        $getSiswa = Siswa::with('kelasdetail')
            ->whereHas('kelasdetail', function ($query) {
                $query->where('kelasdetail.kelas_id', $this->kelas->id);
                // ->where('kelasdetail.tapel_id', Fungsi::app_tapel_aktif());
            })
            ->get();
        foreach ($getSiswa as $siswa) {
            $siswa->nilai_pembimbingsekolah = 0;
            $siswa->nilai_pembimbinglapangan = 0;
            $siswa->nilai_absensi = 0;
            $siswa->nilai_jurnal = 0;
            $siswa->nilai_akhir = 0;
            $getSettingsPenilaian = penilaian::where('jurusan_id', $siswa->kelasdetail->kelas->jurusan)->first();


            if ($getSettingsPenilaian) {
                $siswa->nilai_pembimbingsekolah = penilaian_guru_detail::where('siswa_id', $siswa->id)->avg('nilai') ? number_format(penilaian_guru_detail::where('siswa_id', $siswa->id)->avg('nilai') * $getSettingsPenilaian->penilaian_guru / 100, 2) : 0;
                $siswa->nilai_pembimbinglapangan = penilaian_pembimbinglapangan_detail::where('siswa_id', $siswa->id)->avg('nilai') ? number_format(penilaian_pembimbinglapangan_detail::where('siswa_id', $siswa->id)->avg('nilai') * $getSettingsPenilaian->penilaian_pembimbinglapangan / 100, 2) : 0;
                $getAbsensi = penilaian_absensi_dan_jurnal::where('siswa_id', $siswa->id)->where('prefix', 'absensi')->first();
                $siswa->nilai_absensi = $getAbsensi ? number_format($getAbsensi->nilai * $getSettingsPenilaian->absensi / 100, 2) : 0;
                $getJurnal = penilaian_absensi_dan_jurnal::where('siswa_id', $siswa->id)->where('prefix', 'jurnal')->first();
                $siswa->nilai_jurnal = $getJurnal ? number_format($getJurnal->nilai * $getSettingsPenilaian->jurnal / 100, 2) : 0;
                $getNilaiAkhir = number_format($siswa->nilai_pembimbingsekolah + $siswa->nilai_pembimbinglapangan + $siswa->nilai_absensi + $siswa->nilai_jurnal, 2);
                $siswa->nilai_akhir = $getSettingsPenilaian ? number_format($getNilaiAkhir, 2) : 0;
            }
            // dd($siswa->id, $siswa->nilai_pembimbingsekolah);
        }
        // foreach ($getKelas as $kelas) {
        //     $kelas->kelas->kelas_nama = $kelas->kelas ? ($kelas->kelas->tingkatan . " " . $kelas->kelas->jurusan_table->nama . " " . $kelas->kelas->suffix) : null;
        //     // dd($kelas->kelas);
        //     $dataKelas = $kelas->kelas ? $kelas->kelas : null;
        //     if ($dataKelas) {
        //         $result[] = $dataKelas;
        //     }
        // }
        // $sorted = $result->sortBy('nama');
        return response()->json([
            'success'    => true,
            'data'    => $getSiswa,
        ], 200);
    }
    // public function exportDataSiswaPerkelas($kelas_id, Request $request)
    // {
    //     $req = $kelas_id;
    //     $datenow = base64_decode($request->token); //tanggal untuk random kode harian
    //     $current_time = Carbon::now()->toDayDateTimeString();
    //     // Carbon::parse($dateTime)->format('D, d M \'y, H:i')
    //     // dd($datenow, date('Y-m-d'), $current_time);
    //     $kelas = base64_decode($req);
    //     $getKelas = kelas::where('id', $kelas)->first();
    //     $items = null;
    //     if ($datenow == date('Y-m-d')) {
    //         // dd($kelas);
    //         // return Excel::download(new exportSiswaPerkelas, 'data-' . $datenow . '.xlsx');
    //         // $tgl = date("YmdHis");
    //         // return Excel::download(new exportSiswaPerkelas, 'data-' . $tgl . '.xlsx');
    //         $tgl = date("YmdHis");
    //         return Excel::download(new exportSiswaPerkelasBaru($kelas), 'dataKelas-' . $getKelas->nama . '-' . $tgl . '.xlsx');
    //     } else {
    //         echo 'Token Invalid!';
    //     }
    // }
}

<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\kelasdetail;
use App\Models\penilaian;
use App\Models\penilaian_guru;
use App\Models\penilaian_guru_detail;
use App\Models\Siswa;
use Illuminate\Http\Request;

class adminHasilController extends Controller
{
    protected $siswa_id;
    public function getHasil(Siswa $siswa, Request $request)
    {
        $this->siswa_id = $siswa->id;
        $data = 'Data tidak ditemukan';
        $getKelas = kelasdetail::with('kelas')->where('siswa_id', $this->siswa_id)
            ->whereHas('kelas', function ($query) {
                $query->where('tapel_id', Fungsi::app_tapel_aktif());
            })
            ->first();
        $jurusan_id = $getKelas->kelas ? $getKelas->kelas->jurusan : null;
        // dd($getKelas, $jurusan_id);
        $settingPenilaian = penilaian::where('tapel_id', Fungsi::app_tapel_aktif())
            ->where('jurusan_id', $jurusan_id);
        if ($settingPenilaian->count() < 0) {
            return response()->json([
                'success'    => true,
                'data'    => 'Setting penilaian belum diatur, Hubungi admin/ kepala prodi!',
            ], 200);
        }
        $getSettingPenilaian = $settingPenilaian->first();
        $penilaian_id = $getSettingPenilaian->id;
        $penilaian_guru = collect([]);
        $penilaian_guru_avg = 0;
        $penilaian_guru_rekap = 0;
        $penilaian_pembimbinglapangan = collect([]);
        $penilaian_absensi = collect([]);
        $penilaian_jurnal = collect([]);
        $nilaiakhir = collect([]);

        // penilaian_guru
        $getPenilaian_guru = penilaian_guru::where('penilaian_id', $penilaian_id)->get();
        foreach ($getPenilaian_guru as $pg) {
            $temp = (object)[
                'nama' => $pg->nama,
                'nilai' => 0,
            ];
            $getDetail = penilaian_guru_detail::where('penilaian_guru_id', $pg->id)->where('siswa_id', $this->siswa_id);
            if ($getDetail->count() > 0) {
                $getDetailData = $getDetail->first();
                $temp->nilai = $getDetailData->nilai ? $getDetailData->nilai : 0;
            }

            $penilaian_guru[] = $temp;
        }
        $penilaian_guru_avg = $penilaian_guru->avg('nilai');
        $penilaian_guru_rekap = number_format($penilaian_guru_avg * $getSettingPenilaian->penilaian_guru / 100, 2);
        // dd($penilaian_guru, $penilaian_guru_avg, $penilaian_guru_rekap);
        $result = (object)[
            'penilaian_guru' => $penilaian_guru,
            'penilaian_guru_avg' => $penilaian_guru_avg,
            'penilaian_guru_setting_persentase' => $getSettingPenilaian->penilaian_guru,
            'penilaian_guru_rekap' => $penilaian_guru_rekap,
            'penilaian_pembimbinglapangan' => $penilaian_pembimbinglapangan,
            'penilaian_absensi' => $penilaian_absensi,
            'penilaian_jurnal' => $penilaian_jurnal,
            'nilaiakhir' => $nilaiakhir,
        ];
        $data = $result;


        return response()->json([
            'success'    => true,
            'data'    => $data,
            'data'    => $data,
        ], 200);
    }
}

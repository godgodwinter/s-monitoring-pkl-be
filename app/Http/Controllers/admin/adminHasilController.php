<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\absensi;
use App\Models\kelasdetail;
use App\Models\penilaian;
use App\Models\penilaian_absensi_dan_jurnal;
use App\Models\penilaian_guru;
use App\Models\penilaian_guru_detail;
use App\Models\penilaian_pembimbinglapangan;
use App\Models\penilaian_pembimbinglapangan_detail;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Faker\Factory as Faker;
use PDF;

class adminHasilController extends Controller
{
    protected $siswa_id;
    public function getHasil(Siswa $siswa, Request $request)
    {
        $this->siswa_id = $siswa->id;
        $data = 'Data tidak ditemukan';


        // $getKelas = kelasdetail::with('kelas')->where('siswa_id', $this->siswa_id)
        //     ->whereHas('kelas', function ($query) {
        //         $query->where('tapel_id', Fungsi::app_tapel_aktif());
        //     })
        //     ->first();
        // $jurusan_id = $getKelas->kelas ? $getKelas->kelas->jurusan : null;
        // // dd($getKelas, $jurusan_id);
        // $settingPenilaian = penilaian::where('tapel_id', Fungsi::app_tapel_aktif())
        //     ->where('jurusan_id', $jurusan_id);
        // if ($settingPenilaian->count() < 0) {
        //     return response()->json([
        //         'success'    => true,
        //         'data'    => 'Setting penilaian belum diatur, Hubungi admin/ kepala prodi!',
        //     ], 200);
        // }
        // $getSettingPenilaian = $settingPenilaian->first();
        // $penilaian_id = $getSettingPenilaian->id;
        // $penilaian_guru =  collect([]);
        // $penilaian_guru_avg = 0;
        // $penilaian_guru_rekap = 0;
        // $penilaian_pembimbinglapangan = collect([]);
        // $penilaian_pembimbinglapangan_avg = 0;
        // $penilaian_pembimbinglapangan_rekap = 0;
        // $penilaian_absensi =  0;
        // $penilaian_absensi_rekap = 0;
        // $penilaian_jurnal =  0;
        // $penilaian_jurnal_rekap = 0;
        // $nilaiakhir_setting_persentase = 0;
        // $nilaiakhir = 0;
        // $faker = Faker::create('id_ID');
        // // penilaian_guru
        // $getPenilaian_guru = penilaian_guru::where('penilaian_id', $penilaian_id)->get();
        // foreach ($getPenilaian_guru as $pg) {
        //     $temp = (object)[
        //         'id' =>  $faker->uuid,
        //         'nama' => $pg->nama,
        //         'nilai' => 0,
        //     ];
        //     $getDetail = penilaian_guru_detail::where('penilaian_guru_id', $pg->id)->where('siswa_id', $this->siswa_id);
        //     if ($getDetail->count() > 0) {
        //         $getDetailData = $getDetail->first();
        //         $temp->nilai = $getDetailData->nilai ? $getDetailData->nilai : 0;
        //     }

        //     $penilaian_guru[] = $temp;
        // }
        // $penilaian_guru_avg = $penilaian_guru->avg('nilai');
        // $penilaian_guru_rekap = number_format($penilaian_guru_avg * $getSettingPenilaian->penilaian_guru / 100, 2);
        // // dd($penilaian_guru, $penilaian_guru_avg, $penilaian_guru_rekap);


        // // penilaian_pembimbinglapangan
        // $getPenilaian_pembimbinglapangan = penilaian_pembimbinglapangan::where('penilaian_id', $penilaian_id)->get();
        // foreach ($getPenilaian_pembimbinglapangan as $pg) {
        //     $temp = (object)[
        //         'id' =>  $faker->uuid,
        //         'nama' => $pg->nama,
        //         'nilai' => 0,
        //     ];
        //     $getDetail = penilaian_pembimbinglapangan_detail::where('penilaian_pembimbinglapangan_id', $pg->id)->where('siswa_id', $this->siswa_id);
        //     if ($getDetail->count() > 0) {
        //         $getDetailData = $getDetail->first();
        //         $temp->nilai = $getDetailData->nilai ? $getDetailData->nilai : 0;
        //     }

        //     $penilaian_pembimbinglapangan[] = $temp;
        // }
        // $penilaian_pembimbinglapangan_avg = $penilaian_pembimbinglapangan->avg('nilai');
        // $penilaian_pembimbinglapangan_rekap = number_format($penilaian_pembimbinglapangan_avg * $getSettingPenilaian->penilaian_pembimbinglapangan / 100, 2);
        // // dd($penilaian_pembimbinglapangan, $penilaian_pembimbinglapangan_avg, $penilaian_pembimbinglapangan_rekap);



        // //penilaian absensi
        // $getPenilaianAbsensi = penilaian_absensi_dan_jurnal::where('tapel_id', Fungsi::app_tapel_aktif())->where('siswa_id', $this->siswa_id)->where('prefix', 'absensi');
        // if ($getPenilaianAbsensi->count() > 0) {
        //     $penilaian_absensi_only = $getPenilaianAbsensi->first();
        //     $penilaian_absensi = $penilaian_absensi_only->nilai;
        //     $penilaian_absensi_rekap = number_format($penilaian_absensi * $getSettingPenilaian->absensi / 100, 2);
        // }


        // //penilaian absensi
        // $getPenilaianJurnal = penilaian_absensi_dan_jurnal::where('tapel_id', Fungsi::app_tapel_aktif())->where('siswa_id', $this->siswa_id)->where('prefix', 'jurnal');
        // if ($getPenilaianJurnal->count() > 0) {
        //     $penilaian_jurnal_only = $getPenilaianJurnal->first();
        //     $penilaian_jurnal = $penilaian_jurnal_only->nilai;
        //     $penilaian_jurnal_rekap = number_format($penilaian_jurnal * $getSettingPenilaian->jurnal / 100, 2);
        // }

        // $nilaiakhir = $penilaian_guru_rekap + $penilaian_pembimbinglapangan_rekap + $penilaian_absensi_rekap + $penilaian_jurnal_rekap;

        // $result = (object)[
        //     'penilaian_guru' => $penilaian_guru,
        //     'penilaian_guru_avg' => $penilaian_guru_avg,
        //     'penilaian_guru_rekap' => $penilaian_guru_rekap,
        //     'penilaian_guru_setting_persentase' => $getSettingPenilaian->penilaian_guru,
        //     'penilaian_pembimbinglapangan' => $penilaian_pembimbinglapangan,
        //     'penilaian_pembimbinglapangan_avg' => $penilaian_pembimbinglapangan_avg,
        //     'penilaian_pembimbinglapangan_rekap' => $penilaian_pembimbinglapangan_rekap,
        //     'penilaian_pembimbinglapangan_setting_persentase' => $getSettingPenilaian->penilaian_pembimbinglapangan,
        //     'penilaian_absensi' => $penilaian_absensi,
        //     'penilaian_absensi_rekap' => $penilaian_absensi_rekap,
        //     'penilaian_absensi_setting_persentase' => $getSettingPenilaian->absensi,
        //     'penilaian_jurnal' => $penilaian_jurnal,
        //     'penilaian_jurnal_rekap' => $penilaian_jurnal_rekap,
        //     'penilaian_jurnal_setting_persentase' => $getSettingPenilaian->jurnal,
        //     'penilaian_nilaiakhir_setting_persentase' =>  $getSettingPenilaian->penilaian_guru + $getSettingPenilaian->penilaian_pembimbinglapangan + $getSettingPenilaian->absensi + $getSettingPenilaian->jurnal,
        //     'nilaiakhir' => number_format($nilaiakhir, 2),
        // ];
        // $data = $result;
        $data = $this->fnGetHasilSiswa($siswa->id);

        return response()->json([
            'success'    => true,
            'data'    => $data,
            'data'    => $data,
        ], 200);
    }

    public function fnGetHasilSiswa($siswa_id)
    {
        $data =  (object)[];

        $this->siswa_id = $siswa_id;
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
        $penilaian_guru =  collect([]);
        $penilaian_guru_avg = 0;
        $penilaian_guru_rekap = 0;
        $penilaian_pembimbinglapangan = collect([]);
        $penilaian_pembimbinglapangan_avg = 0;
        $penilaian_pembimbinglapangan_rekap = 0;
        $penilaian_absensi =  0;
        $penilaian_absensi_rekap = 0;
        $penilaian_jurnal =  0;
        $penilaian_jurnal_rekap = 0;
        $nilaiakhir_setting_persentase = 0;
        $nilaiakhir = 0;
        $faker = Faker::create('id_ID');
        // penilaian_guru
        $getPenilaian_guru = penilaian_guru::where('penilaian_id', $penilaian_id)->get();
        foreach ($getPenilaian_guru as $pg) {
            $temp = (object)[
                'id' =>  $faker->uuid,
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


        // penilaian_pembimbinglapangan
        $getPenilaian_pembimbinglapangan = penilaian_pembimbinglapangan::where('penilaian_id', $penilaian_id)->get();
        foreach ($getPenilaian_pembimbinglapangan as $pg) {
            $temp = (object)[
                'id' =>  $faker->uuid,
                'nama' => $pg->nama,
                'nilai' => 0,
            ];
            $getDetail = penilaian_pembimbinglapangan_detail::where('penilaian_pembimbinglapangan_id', $pg->id)->where('siswa_id', $this->siswa_id);
            if ($getDetail->count() > 0) {
                $getDetailData = $getDetail->first();
                $temp->nilai = $getDetailData->nilai ? $getDetailData->nilai : 0;
            }

            $penilaian_pembimbinglapangan[] = $temp;
        }
        $penilaian_pembimbinglapangan_avg = $penilaian_pembimbinglapangan->avg('nilai');
        $penilaian_pembimbinglapangan_rekap = number_format($penilaian_pembimbinglapangan_avg * $getSettingPenilaian->penilaian_pembimbinglapangan / 100, 2);
        // dd($penilaian_pembimbinglapangan, $penilaian_pembimbinglapangan_avg, $penilaian_pembimbinglapangan_rekap);



        //penilaian absensi
        $getPenilaianAbsensi = penilaian_absensi_dan_jurnal::where('tapel_id', Fungsi::app_tapel_aktif())->where('siswa_id', $this->siswa_id)->where('prefix', 'absensi');
        if ($getPenilaianAbsensi->count() > 0) {
            $penilaian_absensi_only = $getPenilaianAbsensi->first();
            $penilaian_absensi = $penilaian_absensi_only->nilai;
            $penilaian_absensi_rekap = number_format($penilaian_absensi * $getSettingPenilaian->absensi / 100, 2);
        }


        //penilaian absensi
        $getPenilaianJurnal = penilaian_absensi_dan_jurnal::where('tapel_id', Fungsi::app_tapel_aktif())->where('siswa_id', $this->siswa_id)->where('prefix', 'jurnal');
        if ($getPenilaianJurnal->count() > 0) {
            $penilaian_jurnal_only = $getPenilaianJurnal->first();
            $penilaian_jurnal = $penilaian_jurnal_only->nilai;
            $penilaian_jurnal_rekap = number_format($penilaian_jurnal * $getSettingPenilaian->jurnal / 100, 2);
        }

        $nilaiakhir = $penilaian_guru_rekap + $penilaian_pembimbinglapangan_rekap + $penilaian_absensi_rekap + $penilaian_jurnal_rekap;

        $result = (object)[
            'penilaian_guru' => $penilaian_guru,
            'penilaian_guru_avg' => $penilaian_guru_avg,
            'penilaian_guru_rekap' => $penilaian_guru_rekap,
            'penilaian_guru_setting_persentase' => $getSettingPenilaian->penilaian_guru,
            'penilaian_pembimbinglapangan' => $penilaian_pembimbinglapangan,
            'penilaian_pembimbinglapangan_avg' => $penilaian_pembimbinglapangan_avg,
            'penilaian_pembimbinglapangan_rekap' => $penilaian_pembimbinglapangan_rekap,
            'penilaian_pembimbinglapangan_setting_persentase' => $getSettingPenilaian->penilaian_pembimbinglapangan,
            'penilaian_absensi' => $penilaian_absensi,
            'penilaian_absensi_rekap' => $penilaian_absensi_rekap,
            'penilaian_absensi_setting_persentase' => $getSettingPenilaian->absensi,
            'penilaian_jurnal' => $penilaian_jurnal,
            'penilaian_jurnal_rekap' => $penilaian_jurnal_rekap,
            'penilaian_jurnal_setting_persentase' => $getSettingPenilaian->jurnal,
            'penilaian_nilaiakhir_setting_persentase' =>  $getSettingPenilaian->penilaian_guru + $getSettingPenilaian->penilaian_pembimbinglapangan + $getSettingPenilaian->absensi + $getSettingPenilaian->jurnal,
            'nilaiakhir' => number_format($nilaiakhir, 2),
        ];
        $data = $result;


        return $data;
    }

    public function getHasilCetak(Siswa $siswa, Request $request)
    {
        $siswa_id = $siswa->id;
        $siswa = Siswa::where('id', $siswa->id)->with('kelasdetail')->first();
        $tgl = date("YmdHis");
        $dataCetak = collect([]);
        $hasil = $this->fnGetHasilSiswa($siswa->id);
        $tempData = (object)[
            'dataSiswa' => $siswa,
            'hasil' => $hasil,
        ];
        $dataCetak[] = $tempData;
        // dd($dataCetak);
        $pdf = PDF::loadview('dev.cetak.cetakHasil', compact('dataCetak'))->setPaper('legal', 'potrait');
        return $pdf->stream('data' . $tgl . '.pdf');
    }
}

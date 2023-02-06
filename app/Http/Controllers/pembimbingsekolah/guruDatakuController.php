<?php

namespace App\Http\Controllers\pembimbingsekolah;

use App\Http\Controllers\Controller;
use App\Models\pendaftaranprakerin_proses;
use App\Models\Siswa;
use App\Models\tempatpkl;
use Illuminate\Http\Request;
use App\Http\Controllers\pembimbingsekolah\guruController;
use App\Models\pendaftaranprakerin_prosesdetail;
use App\Models\penilaian;
use App\Models\penilaian_absensi_dan_jurnal;
use App\Models\penilaian_guru_detail;
use App\Models\penilaian_pembimbinglapangan_detail;

class guruDatakuController extends guruController
{
    public function tempatpkl(Request $request)
    {
        // $this->periksaAuth();
        $getProsesPkl = pendaftaranprakerin_proses::with('tempatpkl')->with('pendaftaranprakerin_prosesdetail')->where('pembimbingsekolah_id', $this->me->id)->get();
        $items = collect([]);
        foreach ($getProsesPkl as $proses) {
            if ($proses->tempatpkl) {
                $proses->tempatpkl->jml_siswa = count($proses->pendaftaranprakerin_prosesdetail);
                $items[] = $proses->tempatpkl;
            }
        }
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    protected $tempatpkl_id;
    public function tempatpkl_detail(tempatpkl $item, Request $request)
    {
        // $this->periksaAuth();
        // $getProsesPkl = pendaftaranprakerin_proses::with('tempatpkl')->with('pendaftaranprakerin_prosesdetail')->where('pembimbingsekolah_id', $this->me->id)->get();
        // $items = collect([]);
        // foreach ($getProsesPkl as $proses) {
        //     if ($proses->tempatpkl) {
        //         $proses->tempatpkl->jml_siswa = count($proses->pendaftaranprakerin_prosesdetail);
        //         $items[] = $proses->tempatpkl;
        //     }
        // }
        $this->tempatpkl_id = $item->id;
        $getSiswaDetail = pendaftaranprakerin_prosesdetail::with('pendaftaranprakerin_proses')->with('siswa')->whereHas('pendaftaranprakerin_proses', function ($query) {
            $query->where('tempatpkl_id', $this->tempatpkl_id);
        })->get();
        $items = (object)[];
        $items->tempatpkl = tempatpkl::with('pembimbinglapangan')->where('id', $item->id)->first();
        $items->penanggungjawab_id = $items->tempatpkl ? $items->tempatpkl->pembimbinglapangan->id : null;
        $items->penanggungjawab_nama = $items->tempatpkl ? $items->tempatpkl->pembimbinglapangan->nama : null;
        // $items->siswa = $this->tempatpkl_id;
        $dataSiswa = collect([]);
        foreach ($getSiswaDetail as $s) {

            $siswa = Siswa::where('id',  $s->id)->first();
            $siswa['kelas_nama'] = "{$siswa->kelasdetail->kelas->tingkatan} {$siswa->kelasdetail->kelas->jurusan_table->nama} {$siswa->kelasdetail->kelas->suffix}";
            $dataSiswa[] = $siswa;
        }
        $items->siswa = $dataSiswa;
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function siswa(Request $request)
    {
        // $this->periksaAuth();
        // $items = Siswa::orderBy('id', 'asc')
        //     // ->where('tapel_id', Fungsi::app_tapel_aktif())
        //     // ->where('jurusan_id', $this->jurusan->id)
        //     ->get();

        $getProsesPkl = pendaftaranprakerin_proses::with('pendaftaranprakerin_prosesdetail')->with('tempatpkl')->where('pembimbingsekolah_id', $this->me->id)->get();
        $items = collect([]);

        foreach ($getProsesPkl as $proses) {
            foreach ($proses->pendaftaranprakerin_prosesdetail as $detail) {
                if ($detail->siswa) {
                    $detail->siswa->tempatpkl_id = $proses->id;
                    $detail->siswa->tempatpkl_nama = $proses->tempatpkl ? $proses->tempatpkl->nama : null;
                    $items[] = $detail->siswa;
                }
            }
        }
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function index(Request $request)
    {
        // $this->periksaAuth();
        $items = tempatpkl::orderBy('id', 'asc')
            // ->where('tapel_id', Fungsi::app_tapel_aktif())
            // ->where('jurusan_id', $this->jurusan->id)
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }


    public function penilai_tempatpkl(Request $request)
    {
        // $this->periksaAuth();
        $getProsesPkl = pendaftaranprakerin_proses::with('tempatpkl')->with('pendaftaranprakerin_prosesdetail')->where('penilai_id', $this->me->id)->get();
        $items = collect([]);
        foreach ($getProsesPkl as $proses) {
            if ($proses->tempatpkl) {
                $proses->tempatpkl->jml_siswa = count($proses->pendaftaranprakerin_prosesdetail);
                $items[] = $proses->tempatpkl;
            }
        }
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function penilai_siswa(Request $request)
    {
        // $this->periksaAuth();
        // $items = Siswa::orderBy('id', 'asc')
        //     // ->where('tapel_id', Fungsi::app_tapel_aktif())
        //     // ->where('jurusan_id', $this->jurusan->id)
        //     ->get();

        $getProsesPkl = pendaftaranprakerin_proses::with('pendaftaranprakerin_prosesdetail')->with('tempatpkl')->where('penilai_id', $this->me->id)->get();
        $items = collect([]);

        foreach ($getProsesPkl as $proses) {
            foreach ($proses->pendaftaranprakerin_prosesdetail as $detail) {
                if ($detail->siswa) {
                    $detail->siswa->tempatpkl_id = $proses->id;
                    $detail->siswa->tempatpkl_nama = $proses->tempatpkl ? $proses->tempatpkl->nama : null;
                    $items[] = $detail->siswa;
                }
            }
        }
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function penilai_siswa_detailnilai(Request $request)
    {
        // $this->periksaAuth();
        // $items = Siswa::orderBy('id', 'asc')
        //     // ->where('tapel_id', Fungsi::app_tapel_aktif())
        //     // ->where('jurusan_id', $this->jurusan->id)
        //     ->get();

        $getProsesPkl = pendaftaranprakerin_proses::with('pendaftaranprakerin_prosesdetail')->with('tempatpkl')->where('penilai_id', $this->me->id)->get();
        $items = collect([]);

        foreach ($getProsesPkl as $proses) {
            foreach ($proses->pendaftaranprakerin_prosesdetail as $detail) {
                if ($detail->siswa) {
                    $detail->siswa->tempatpkl_id = $proses->id;
                    $detail->siswa->tempatpkl_nama = $proses->tempatpkl ? $proses->tempatpkl->nama : null;

                    $detail->siswa->nilai_pembimbingsekolah = 0;
                    $detail->siswa->nilai_pembimbinglapangan = 0;
                    $detail->siswa->nilai_absensi = 0;
                    $detail->siswa->nilai_jurnal = 0;
                    $detail->siswa->nilai_akhir = 0;
                    $getSettingsPenilaian = penilaian::where('jurusan_id',  $detail->siswa->kelasdetail->kelas->jurusan)->first();


                    if ($getSettingsPenilaian) {
                        $detail->siswa->nilai_pembimbingsekolah = penilaian_guru_detail::where('siswa_id',  $detail->siswa->id)->avg('nilai') ? number_format(penilaian_guru_detail::where('siswa_id',  $detail->siswa->id)->avg('nilai') * $getSettingsPenilaian->penilaian_guru / 100, 2) : 0;
                        $detail->siswa->nilai_pembimbinglapangan = penilaian_pembimbinglapangan_detail::where('siswa_id',  $detail->siswa->id)->avg('nilai') ? number_format(penilaian_pembimbinglapangan_detail::where('siswa_id',  $detail->siswa->id)->avg('nilai') * $getSettingsPenilaian->penilaian_pembimbinglapangan / 100, 2) : 0;
                        $getAbsensi = penilaian_absensi_dan_jurnal::where('siswa_id',  $detail->siswa->id)->where('prefix', 'absensi')->first();
                        $detail->siswa->nilai_absensi = $getAbsensi ? number_format($getAbsensi->nilai * $getSettingsPenilaian->absensi / 100, 2) : 0;
                        $getJurnal = penilaian_absensi_dan_jurnal::where('siswa_id',  $detail->siswa->id)->where('prefix', 'jurnal')->first();
                        $detail->siswa->nilai_jurnal = $getJurnal ? number_format($getJurnal->nilai * $getSettingsPenilaian->jurnal / 100, 2) : 0;
                        $getNilaiAkhir = number_format($detail->siswa->nilai_pembimbingsekolah +  $detail->siswa->nilai_pembimbinglapangan +  $detail->siswa->nilai_absensi +  $detail->siswa->nilai_jurnal, 2);
                        $detail->siswa->nilai_akhir = $getSettingsPenilaian ? number_format($getNilaiAkhir, 2) : 0;
                    }
                    $items[] = $detail->siswa;
                }
            }
        }
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }


    // public function getNilaiPerkelas(kelas $kelas, Request $request)
    // {
    //     $this->kelas = $kelas;
    //     $result = collect([]);
    //     $getSiswa = Siswa::with('kelasdetail')
    //         ->whereHas('kelasdetail', function ($query) {
    //             $query->where('kelasdetail.kelas_id', $this->kelas->id);
    //             // ->where('kelasdetail.tapel_id', Fungsi::app_tapel_aktif());
    //         })
    //         ->get();
    //     foreach ($getSiswa as $siswa) {
    //         $siswa->nilai_pembimbingsekolah = 0;
    //         $siswa->nilai_pembimbinglapangan = 0;
    //         $siswa->nilai_absensi = 0;
    //         $siswa->nilai_jurnal = 0;
    //         $siswa->nilai_akhir = 0;
    //         $getSettingsPenilaian = penilaian::where('jurusan_id', $siswa->kelasdetail->kelas->jurusan)->first();


    //         if ($getSettingsPenilaian) {
    //             $siswa->nilai_pembimbingsekolah = penilaian_guru_detail::where('siswa_id', $siswa->id)->avg('nilai') ? number_format(penilaian_guru_detail::where('siswa_id', $siswa->id)->avg('nilai') * $getSettingsPenilaian->penilaian_guru / 100, 2) : 0;
    //             $siswa->nilai_pembimbinglapangan = penilaian_pembimbinglapangan_detail::where('siswa_id', $siswa->id)->avg('nilai') ? number_format(penilaian_pembimbinglapangan_detail::where('siswa_id', $siswa->id)->avg('nilai') * $getSettingsPenilaian->penilaian_pembimbinglapangan / 100, 2) : 0;
    //             $getAbsensi = penilaian_absensi_dan_jurnal::where('siswa_id', $siswa->id)->where('prefix', 'absensi')->first();
    //             $siswa->nilai_absensi = $getAbsensi ? number_format($getAbsensi->nilai * $getSettingsPenilaian->absensi / 100, 2) : 0;
    //             $getJurnal = penilaian_absensi_dan_jurnal::where('siswa_id', $siswa->id)->where('prefix', 'jurnal')->first();
    //             $siswa->nilai_jurnal = $getJurnal ? number_format($getJurnal->nilai * $getSettingsPenilaian->jurnal / 100, 2) : 0;
    //             $getNilaiAkhir = number_format($siswa->nilai_pembimbingsekolah + $siswa->nilai_pembimbinglapangan + $siswa->nilai_absensi + $siswa->nilai_jurnal, 2);
    //             $siswa->nilai_akhir = $getSettingsPenilaian ? number_format($getNilaiAkhir, 2) : 0;
    //         }
    //         // dd($siswa->id, $siswa->nilai_pembimbingsekolah);
    //     }
    //     // foreach ($getKelas as $kelas) {
    //     //     $kelas->kelas->kelas_nama = $kelas->kelas ? ($kelas->kelas->tingkatan . " " . $kelas->kelas->jurusan_table->nama . " " . $kelas->kelas->suffix) : null;
    //     //     // dd($kelas->kelas);
    //     //     $dataKelas = $kelas->kelas ? $kelas->kelas : null;
    //     //     if ($dataKelas) {
    //     //         $result[] = $dataKelas;
    //     //     }
    //     // }
    //     // $sorted = $result->sortBy('nama');
    //     return response()->json([
    //         'success'    => true,
    //         'data'    => $getSiswa,
    //     ], 200);
    // }


    public function pembimbing_siswa_detailnilai(Request $request)
    {
        // $this->periksaAuth();
        // $items = Siswa::orderBy('id', 'asc')
        //     // ->where('tapel_id', Fungsi::app_tapel_aktif())
        //     // ->where('jurusan_id', $this->jurusan->id)
        //     ->get();
        // dd($this->me->id);
        $getProsesPkl = pendaftaranprakerin_proses::with('pendaftaranprakerin_prosesdetail')->with('tempatpkl')->where('pembimbingsekolah_id', $this->me->id)->get();
        $items = collect([]);

        foreach ($getProsesPkl as $proses) {
            foreach ($proses->pendaftaranprakerin_prosesdetail as $detail) {
                if ($detail->siswa) {
                    $detail->siswa->tempatpkl_id = $proses->id;
                    $detail->siswa->tempatpkl_nama = $proses->tempatpkl ? $proses->tempatpkl->nama : null;

                    $detail->siswa->nilai_pembimbingsekolah = 0;
                    $detail->siswa->nilai_pembimbinglapangan = 0;
                    $detail->siswa->nilai_absensi = 0;
                    $detail->siswa->nilai_jurnal = 0;
                    $detail->siswa->nilai_akhir = 0;
                    $getSettingsPenilaian = penilaian::where('jurusan_id',  $detail->siswa->kelasdetail->kelas->jurusan)->first();


                    if ($getSettingsPenilaian) {
                        $detail->siswa->nilai_pembimbingsekolah = penilaian_guru_detail::where('siswa_id',  $detail->siswa->id)->avg('nilai') ? number_format(penilaian_guru_detail::where('siswa_id',  $detail->siswa->id)->avg('nilai') * $getSettingsPenilaian->penilaian_guru / 100, 2) : 0;
                        $detail->siswa->nilai_pembimbinglapangan = penilaian_pembimbinglapangan_detail::where('siswa_id',  $detail->siswa->id)->avg('nilai') ? number_format(penilaian_pembimbinglapangan_detail::where('siswa_id',  $detail->siswa->id)->avg('nilai') * $getSettingsPenilaian->penilaian_pembimbinglapangan / 100, 2) : 0;
                        $getAbsensi = penilaian_absensi_dan_jurnal::where('siswa_id',  $detail->siswa->id)->where('prefix', 'absensi')->first();
                        $detail->siswa->nilai_absensi = $getAbsensi ? number_format($getAbsensi->nilai * $getSettingsPenilaian->absensi / 100, 2) : 0;
                        $getJurnal = penilaian_absensi_dan_jurnal::where('siswa_id',  $detail->siswa->id)->where('prefix', 'jurnal')->first();
                        $detail->siswa->nilai_jurnal = $getJurnal ? number_format($getJurnal->nilai * $getSettingsPenilaian->jurnal / 100, 2) : 0;
                        $getNilaiAkhir = number_format($detail->siswa->nilai_pembimbingsekolah +  $detail->siswa->nilai_pembimbinglapangan +  $detail->siswa->nilai_absensi +  $detail->siswa->nilai_jurnal, 2);
                        $detail->siswa->nilai_akhir = $getSettingsPenilaian ? number_format($getNilaiAkhir, 2) : 0;
                    }
                    $items[] = $detail->siswa;
                }
            }
        }
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
}

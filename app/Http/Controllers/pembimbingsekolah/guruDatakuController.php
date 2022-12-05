<?php

namespace App\Http\Controllers\pembimbingsekolah;

use App\Http\Controllers\Controller;
use App\Models\pendaftaranprakerin_proses;
use App\Models\Siswa;
use App\Models\tempatpkl;
use Illuminate\Http\Request;
use App\Http\Controllers\pembimbingsekolah\guruController;
use App\Models\pendaftaranprakerin_prosesdetail;

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
}

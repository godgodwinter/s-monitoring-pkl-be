<?php

namespace App\Http\Controllers\pembimbinglapangan;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pembimbinglapangan;
use App\Models\pembimbingsekolah;
use App\Models\pendaftaranprakerin;
use App\Models\pendaftaranprakerin_proses;
use App\Models\pendaftaranprakerin_prosesdetail;
use App\Models\penilaian_absensi_dan_jurnal;
use App\Models\penilaian_guru;
use App\Models\penilaian_guru_detail;
use App\Models\penilaian_pembimbinglapangan;
use App\Models\penilaian_pembimbinglapangan_detail;
use App\Models\Siswa;
use App\Models\tempatpkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class pembimbinglapanganSiswaController extends Controller
{
    public function siswa(Request $request)
    {
        $getTempatpkl = tempatpkl::where('penanggungjawab', $this->guard()->user()->id)->first();
        $getProsesPkl = pendaftaranprakerin_proses::with('pendaftaranprakerin_prosesdetail')->with('tempatpkl')->where('tempatpkl_id', $getTempatpkl->id)->get();
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


    protected $siswa_id;
    protected $tempatpkl_id;
    protected $proses_id;
    protected $jurusan_id;
    public function penilaian_index(siswa $item)
    {
        $this->siswa_id = $item->id;
        $siswa = [];
        $tempatpkl = [];
        $pembimbinglapangan = [];
        $pembimbingsekolah = [];
        $anggota = [];
        $kepalaprodi = [];
        $status = null;

        // data siswa
        $siswa = Siswa::where('id', $item->id)->first();
        $siswa['kelas_nama'] = "{$siswa->kelasdetail->kelas->tingkatan} {$siswa->kelasdetail->kelas->jurusan_table->nama} {$siswa->kelasdetail->kelas->suffix}";

        // tempat pkl
        $getPendaftaranProsesDetail = pendaftaranprakerin_prosesdetail::with('pendaftaranprakerin_proses')->where('siswa_id', $this->siswa_id)
            ->whereHas('pendaftaranprakerin_proses', function ($query) {
                $query->where('pendaftaranprakerin_proses.status', '!=', 'Ditolak')->where('tapel_id', Fungsi::app_tapel_aktif());
            })->first();
        $getPendaftaranProses_id = $getPendaftaranProsesDetail ? $getPendaftaranProsesDetail->pendaftaranprakerin_proses->id : null;
        $Getstatus = pendaftaranprakerin::where('siswa_id', $this->siswa_id)->where('tapel_id', Fungsi::app_tapel_aktif())->first();
        $status = $Getstatus ? $Getstatus->status : 'Belum Daftar';

        $tempatpkl = $getPendaftaranProsesDetail ? $getPendaftaranProsesDetail->pendaftaranprakerin_proses->tempatpkl : null;
        // pembimbing
        if ($getPendaftaranProsesDetail) {
            $getPendaftaranProses = pendaftaranprakerin_prosesdetail::with('siswa')
                ->where('pendaftaranprakerin_proses_id', $getPendaftaranProses_id)
                ->get();
            foreach ($getPendaftaranProses as $gp) {
                $anggota[] = $gp ? $gp->siswa : null;
            }
            // $anggota[] = $getPendaftaranProses ? $getPendaftaranProses->pendaftaranprakerin_proses->siswa : null;
        }

        if ($tempatpkl) {
            $pembimbinglapangan = pembimbinglapangan::where('id', $tempatpkl->penanggungjawab)->first();
            $getPembimbingSekolah = pendaftaranprakerin_proses::where('id', $getPendaftaranProses_id)->first();
            $pembimbingsekolah = $getPembimbingSekolah ? pembimbingsekolah::where('id', $getPembimbingSekolah->pembimbingsekolah_id)->first() : null;
            // $pembimbingsekolah = $getPembimbingSekolah->pembimbingsekolah_id;
        }
        $kepalajurusan = pembimbingsekolah::where('id', $siswa->kelasdetail->kelas->jurusan_table->kepalajurusan_id)->first();
        // kelas_id
        $item = Siswa::with('kelasdetail')->find($item->id);
        $absensi = null;
        $jurnal = null;
        $penilaian_guru = collect([]);
        $penilaian_pembimbinglapangan = collect([]);
        $this->jurusan_id = $item->kelasdetail ? $item->kelasdetail->kelas->jurusan : null;

        $getAbsensi = penilaian_absensi_dan_jurnal::where('siswa_id', $item->id)->where('tapel_id', Fungsi::app_tapel_aktif())
            ->where('prefix', 'absensi')->first();
        $absensi = $getAbsensi ? $getAbsensi->nilai : null;
        $getJurnal = penilaian_absensi_dan_jurnal::where('siswa_id', $item->id)->where('tapel_id', Fungsi::app_tapel_aktif())
            ->where('prefix', 'jurnal')->first();
        $jurnal = $getJurnal ? $getJurnal->nilai : null;


        $getPenilaian_guru = penilaian_guru::with('penilaian')
            ->whereHas('penilaian', function ($query) {
                $query->where('tapel_id', Fungsi::app_tapel_aktif())->where('jurusan_id', $this->jurusan_id);
            })
            ->where('status', 'Aktif')->get();
        // dd($getPenilaian_guru);

        foreach ($getPenilaian_guru as $n) {
            $tempData = (object)[];
            $tempData->id = $n->id;
            $tempData->penilaian_id = $n->id;
            $tempData->penilaian_nama = $n->nama;
            $getNilai = penilaian_guru_detail::where('penilaian_guru_id', $n->id)->where('siswa_id', $item->id)->first();
            $tempData->nilai = $getNilai ? $getNilai->nilai : null;
            $penilaian_guru[] = $tempData;
        }

        $getPenilaianLapangan = penilaian_pembimbinglapangan::with('penilaian')
            ->whereHas('penilaian', function ($query) {
                $query->where('tapel_id', Fungsi::app_tapel_aktif())->where('jurusan_id', $this->jurusan_id);
            })
            ->where('status', 'Aktif')->get();

        foreach ($getPenilaianLapangan as $l) {
            $tempData = (object)[];
            $tempData->id = $l->id;
            $tempData->penilaian_id = $l->id;
            $tempData->penilaian_nama = $l->nama;
            $getNilai = penilaian_pembimbinglapangan_detail::where('penilaian_pembimbinglapangan_id', $l->id)->where('siswa_id', $item->id)->first();
            $tempData->nilai = $getNilai ? $getNilai->nilai : null;
            $penilaian_pembimbinglapangan[] = $tempData;
        }

        return response()->json([
            'success'    => true,
            'data'    => ([
                'absensi' => $absensi,
                'jurnal' => $jurnal,
                'penilaian_pembimbinglapangan' => $penilaian_pembimbinglapangan,
                'penilaian_guru' => $penilaian_guru,
                'siswa' => $siswa,
                'status' => $status,
                'tempatpkl' => $tempatpkl,
                'anggota' => $anggota,
                'pembimbinglapangan' => $pembimbinglapangan,
                'pembimbingsekolah' => $pembimbingsekolah,
                'kepalajurusan' => $kepalajurusan,
            ]),
        ], 200);
    }
    public function penilaian_store(Siswa $item, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nilai'   => 'required|numeric',
            'penilaian_pembimbinglapangan_id'   => 'required|numeric',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $periksa = penilaian_pembimbinglapangan_detail::where('siswa_id', $item->id)
            ->where('penilaian_pembimbinglapangan_id', $request->penilaian_pembimbinglapangan_id)
            ->count();
        if ($periksa) {

            penilaian_pembimbinglapangan_detail::where('siswa_id', $item->id)
                ->where('penilaian_pembimbinglapangan_id', $request->penilaian_pembimbinglapangan_id)
                ->update([
                    'nilai'     =>   $request->nilai,
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
        } else {
            DB::table('penilaian_pembimbinglapangan_detail')->insert(
                array(
                    'nilai'     =>   $request->nilai,
                    'penilaian_pembimbinglapangan_id'     =>   $request->penilaian_pembimbinglapangan_id,
                    'siswa_id'     =>   $item->id,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                )
            );
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil ditambahkan!',
        ], 200);
    }
    public function guard()
    {
        return Auth::guard('pembimbinglapangan');
    }
}

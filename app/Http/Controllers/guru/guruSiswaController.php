<?php

namespace App\Http\Controllers\guru;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pembimbinglapangan;
use App\Models\pembimbingsekolah;
use App\Models\pendaftaranprakerin;
use App\Models\pendaftaranprakerin_proses;
use App\Models\pendaftaranprakerin_prosesdetail;
use App\Models\Siswa;
use App\Models\penilaian_absensi_dan_jurnal;
use App\Models\penilaian_guru;
use App\Models\penilaian_guru_detail;
use Illuminate\Http\Request;

class guruSiswaController extends Controller
{
    protected $siswa_id;
    protected $tempatpkl_id;
    protected $proses_id;
    public function siswadetail(siswa $item)
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

        $getAbsensi = penilaian_absensi_dan_jurnal::where('siswa_id', $item->id)->where('tapel_id', Fungsi::app_tapel_aktif())
            ->where('prefix', 'absensi')->first();
        $absensi = $getAbsensi ? $getAbsensi->nilai : null;
        $getJurnal = penilaian_absensi_dan_jurnal::where('siswa_id', $item->id)->where('tapel_id', Fungsi::app_tapel_aktif())
            ->where('prefix', 'jurnal')->first();
        $jurnal = $getJurnal ? $getJurnal->nilai : null;


        $getPenilaian_guru = penilaian_guru::with('penilaian')
            ->whereHas('penilaian', function ($query) {
                $query->where('tapel_id', Fungsi::app_tapel_aktif());
            })
            ->where('status', 'Aktif')->get();
        // dd($getPenilaian_guru);
        foreach ($getPenilaian_guru as $n) {
            $tempData = (object)[];
            $tempData->id = $n->id;
            $tempData->penilaian_id = $n->id;
            $tempData->penilaian_nama = $n->nama;
            $tempData->nilai = null;
            $penilaian_guru[] = $tempData;
        }

        return response()->json([
            'success'    => true,
            'data'    => ([
                'absensi' => $absensi,
                'jurnal' => $jurnal,
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
}

<?php

namespace App\Http\Controllers\admin;

use App\Exports\exportNilaiSiswaPerkelas;
use App\Http\Controllers\Controller;
use App\Imports\importGuru;
use App\Imports\importPembimbinglapangan;
use App\Imports\importSiswa;
use App\Imports\importTempatpkl;
use App\Models\kelas;
use App\Models\penilaian;
use App\Models\penilaian_absensi_dan_jurnal;
use App\Models\penilaian_guru_detail;
use App\Models\penilaian_pembimbinglapangan_detail;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class adminProsesController extends Controller
{
    public function clearTemp()
    {
        $path = public_path('/file_temp/');
        $files = glob($path . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                unlink($file); // delete file
        }

        return response()->json([
            'success'    => true,
            'data'    => 'Data Temporary sudah di hapus',
        ], 200);
    }


    public function importSiswa(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        $nama_file = rand() . $file->getClientOriginalName();

        $file->move('file_temp', $nama_file);

        Excel::import(new importSiswa, public_path('/file_temp/' . $nama_file));

        return response()->json([
            'success'    => true,
            'data'    => 'Data berhasil diImport',
        ], 200);
    }


    public function importTempatpkl(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        $nama_file = rand() . $file->getClientOriginalName();

        $file->move('file_temp', $nama_file);

        Excel::import(new importTempatpkl, public_path('/file_temp/' . $nama_file));

        return response()->json([
            'success'    => true,
            'data'    => 'Data berhasil diImport',
        ], 200);
    }


    public function importGuru(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        $nama_file = rand() . $file->getClientOriginalName();

        $file->move('file_temp', $nama_file);

        Excel::import(new importGuru, public_path('/file_temp/' . $nama_file));

        return response()->json([
            'success'    => true,
            'data'    => 'Data berhasil diImport',
        ], 200);
    }
    public function importPembimbinglapangan(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        $nama_file = rand() . $file->getClientOriginalName();

        $file->move('file_temp', $nama_file);

        Excel::import(new importPembimbinglapangan, public_path('/file_temp/' . $nama_file));

        return response()->json([
            'success'    => true,
            'data'    => 'Data berhasil diImport',
        ], 200);
    }


    public function getNilaiPerkelasExport(kelas $kelas, Request $request)
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
        // return response()->json([
        //     'success'    => true,
        //     'data'    => $getSiswa,
        // ], 200);
        $tgl = date("YmdHis");
        return Excel::download(new exportNilaiSiswaPerkelas($getSiswa), 'dataKelas-' . $tgl . '.xlsx');
    }
}

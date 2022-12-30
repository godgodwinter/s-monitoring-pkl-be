<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\absensi;
use App\Models\jurnal;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class adminAbsensiController extends Controller
{
    protected $siswa_id;
    public function getDataAbsensi(Siswa $siswa, Request $request)
    {
        $this->siswa_id = $siswa->id;
        // dd($request->blnthn ? $request->blnthn : date('Y-m'));
        $blnthn = $request->blnthn ? $request->blnthn : date('Y-m');
        $data = [];

        // $data = absensi::where('siswa_id', $this->siswa_id)->where('tgl', 'like', $blnthn . '%')
        //     ->orderBy('tgl', 'asc')
        //     ->get();
        $daysInMonth = Carbon::now()->daysInMonth;
        $tglNow = date('d');
        $tempData = (object)[];
        for ($i = 1; $i < $daysInMonth + 1; $i++) {
            $tempData = (object)[];
            $tempData->libur = null;
            $tempData->id = null;
            $tglTemp = 0;
            if ($i < 10) {
                $tglTemp = '0' . $i;
            } else {
                $tglTemp = $i;
            }

            $tempData->tanggal = $blnthn . '-' . $tglTemp;
            $kehadiran = null;
            $kehadiranCatatan = null;
            $kehadiranStatus = null;
            $absensi_id = null;
            // if ($i <= $tglNow) {
            $getKehadiran = absensi::where('siswa_id', $this->siswa_id)->where('tgl', 'like', $blnthn . "-" . $tglTemp . '%')
                ->orderBy('tgl', 'asc')
                ->first();
            $absensi_id = $getKehadiran ? $getKehadiran->id : null;
            $kehadiran = $getKehadiran ? $getKehadiran->label : null;
            $kehadiranCatatan = $getKehadiran ? $getKehadiran->alasan : null;
            // $tempData->id = $getKehadiran ? $getKehadiran->id : null;
            $tempData->id = $i;
            $kehadiranStatus = $getKehadiran ? $getKehadiran->status : null;

            $kehadiranBukti = $getKehadiran ? url('/') . '/' . $getKehadiran->bukti : null;
            // }
            $tempData->absensi_id = $absensi_id;
            $tempData->kehadiran = $kehadiran;
            $tempData->kehadiranCatatan = $kehadiranCatatan;
            $tempData->kehadiranStatus = $kehadiranStatus;
            $tempData->kehadiranBukti = $kehadiranBukti;

            $jurnal = null;
            $jurnal_id = null;
            $jurnalCatatan = null;
            $jurnalStatus = null;
            $jurnalFile = null;
            $getJurnal = jurnal::where('siswa_id', $this->siswa_id)->where('tgl', 'like', $blnthn . "-" . $tglTemp . '%')
                ->orderBy('tgl', 'asc')
                ->first();
            $jurnal_id = $getJurnal ? $getJurnal->id : null;
            $jurnal = $getJurnal ? $getJurnal->label : null;
            $jurnalCatatan = $getJurnal ? $getJurnal->alasan : null;
            $jurnalStatus = $getJurnal ? $getJurnal->status : null;
            $jurnalFile = $getJurnal ? url('/') . '/' . $getJurnal->file : null;
            $tempData->jurnal_id = $jurnal_id;
            $tempData->jurnal = $jurnal;
            $tempData->jurnalCatatan = $jurnalCatatan;
            $tempData->jurnalStatus = $jurnalStatus;
            $tempData->jurnalFile = $jurnalFile;
            // push
            array_push($data, $tempData);
        }


        return response()->json([
            'success'    => true,
            'data'    => $data,
            'blnthn' => $request->blnthn,
            // 'siswa' => $this->siswa_id,
            // 'file' => $request->bukti,
        ], 200);
    }
    public function doKonfirmasiAbsen(absensi $absensi, Request $request)
    {

        $data = [];
        $periksa = absensi::where('id', $absensi->id);
        if ($periksa->count() > 0) {
            $periksa
                ->update([
                    'status'     =>   $request->status,
                    'catatan_pembimbing'     =>   $request->catatan_pembimbing,
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
            $data = "Data berhasil diupdate!";
        } else {
            $data = "Data tidak ditemukan";
        }
        return response()->json([
            'success'    => true,
            'data'    => $data,
            // 'siswa' => $this->siswa_id,
            // 'file' => $request->bukti,
        ], 200);
    }
    public function doKonfirmasiAbsenDelete(absensi $absensi, Request $request)
    {

        $data = [];
        $periksa = absensi::where('id', $absensi->id);
        if ($periksa->count() > 0) {
            $periksa
                ->update([
                    'status'     =>   'menunggu konfirmasi',
                    'catatan_pembimbing'     =>   null,
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
            $data = "Data berhasil diupdate!";
        } else {
            $data = "Data tidak ditemukan";
        }
        return response()->json([
            'success'    => true,
            'data'    => $data,
            // 'siswa' => $this->siswa_id,
            // 'file' => $request->bukti,
        ], 200);
    }


    public function doKonfirmasiJurnal(jurnal $jurnal, Request $request)
    {

        $data = [];
        $periksa = jurnal::where('id', $jurnal->id);
        if ($periksa->count() > 0) {
            $periksa
                ->update([
                    'status'     =>   $request->status,
                    'catatan_pembimbing'     =>   $request->catatan_pembimbing,
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
            $data = "Data berhasil diupdate!";
        } else {
            $data = "Data tidak ditemukan";
        }
        return response()->json([
            'success'    => true,
            'data'    => $data,
            // 'siswa' => $this->siswa_id,
            // 'file' => $request->bukti,
        ], 200);
    }
    public function doKonfirmasiJurnalDelete(jurnal $jurnal, Request $request)
    {

        $data = [];
        $periksa = jurnal::where('id', $jurnal->id);
        if ($periksa->count() > 0) {
            $periksa
                ->update([
                    'status'     =>   'menunggu konfirmasi',
                    'catatan_pembimbing'     =>   null,
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
            $data = "Data berhasil diupdate!";
        } else {
            $data = "Data tidak ditemukan";
        }
        return response()->json([
            'success'    => true,
            'data'    => $data,
            // 'siswa' => $this->siswa_id,
            // 'file' => $request->bukti,
        ], 200);
    }
}

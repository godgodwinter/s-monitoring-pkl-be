<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use App\Models\absensi;
use App\Models\jurnal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class siswaAbsensiController extends Controller
{
    // constuct
    protected $siswa_id;
    public function __construct()
    {
        $this->siswa_id =  $this->guard()->user()->id;
    }
    public function index(Request $request)
    {
        $siswa = $this->me();
        $getDataAbsensi = absensi::get();
        $getDataJurnal = jurnal::get();
    }

    public function getDataAbsensi(Request $request)
    {
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
            // if ($i <= $tglNow) {
            $getKehadiran = absensi::where('siswa_id', $this->siswa_id)->where('tgl', 'like', $blnthn . "-" . $tglTemp . '%')
                ->orderBy('tgl', 'asc')
                ->first();
            $kehadiran = $getKehadiran ? $getKehadiran->label : null;
            $kehadiranCatatan = $getKehadiran ? $getKehadiran->alasan : 0;
            $tempData->id = $getKehadiran ? $getKehadiran->id : 0;
            $kehadiranStatus = $getKehadiran ? $getKehadiran->status : null;
            // }
            $tempData->kehadiran = $kehadiran;
            $tempData->kehadiranCatatan = $kehadiranCatatan;
            $tempData->kehadiranStatus = $kehadiranStatus;
            $tempData->jurnal = '-';
            $tempData->jurnalCatatan = '-';
            // push
            array_push($data, $tempData);
        }


        return response()->json([
            'success'    => true,
            'data'    => $data,
            'siswa' => $this->siswa_id,
            // 'file' => $request->bukti,
        ], 200);
    }

    public function doAbsen(Request $request)
    {
        // label
        // bukti=file
        // alasan
        $file = $request->file('bukti');
        $tglNow = date('Y-m-d');
        // $tglNow = "2022-07-07";
        //periksa
        $periksa = absensi::where('siswa_id', $this->siswa_id)->where('tgl', $tglNow);
        if ($periksa->count() > 0) {
            return response()->json([
                'success'    => false,
                'data'    => 'Anda sudah absen!',
                'siswa' => $this->siswa_id,
            ], 200);
        }

        // insert and get id first
        $absen_id = absensi::insertGetId([
            'siswa_id' => $this->siswa_id,
            'label' => $request->label,
            'tgl' => $tglNow,
            'status' => 'menunggu konfirmasi',
            'alasan' => $request->alasan,
            // 'alasan' => $request->alasan,
            // 'bukti' => $file->getClientOriginalName(),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // then upload file to storage
        if ($request->label != 'Hadir') {
            if ($file) {
                $path = 'uploads/absensi';
                $ext = $file->getClientOriginalExtension();
                $nama_file = $this->siswa_id . '-' . $tglNow . '.' . $ext;
                $file->move($path, $nama_file);
                $data['photo'] = $path . '/' . $nama_file;

                // update
                absensi::where('id', $absen_id)->update([
                    'bukti' => $path . '/' . $nama_file,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        $kode = 200;
        return response()->json([
            'success'    => true,
            'data'    => $request->label,
            'siswa' => $this->siswa_id,
            // 'file' => $request->bukti,
        ], $kode);
    }

    public function doBatalkan(Request $request)
    {
        $tglNow = date('Y-m-d');
        $periksa = absensi::where('siswa_id', $this->siswa_id)->where('tgl', $tglNow);

        // delete
        $delAbsensi = $periksa->delete();

        $kode = 200;
        return response()->json([
            'success'    => true,
            'data'    => 'Berhasil dibatalkan',
            'siswa' => $this->siswa_id,
            // 'file' => $request->bukti,
        ], $kode);
    }
    public function me()
    {
        return response()->json($this->guard()->user());
    }
    public function guard()
    {
        return Auth::guard('siswa');
    }
}

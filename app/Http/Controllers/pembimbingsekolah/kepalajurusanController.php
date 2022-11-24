<?php

namespace App\Http\Controllers\pembimbingsekolah;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\jurusan;
// use App\Http\Controllers\pembimbingsekolah\guruController;
use App\Models\penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class kepalajurusanController extends Controller
{
    public $me;
    public $kepalajurusan;
    public $jurusan;
    public $defaultResponse;
    public function __construct()
    {
        $this->me = $this->guard()->user();
        $this->fnGetMyJurusan();
        $this->defaultResponse = response()->json([
            'success'    => true,
            'message'    => 'Tidak memiliki akses data ini',
        ], 200);
    }
    public function guard()
    {
        return Auth::guard('pembimbingsekolah');
    }

    public function fnGetMyJurusan()
    {
        // dd($this->me);
        $periksa = jurusan::where('kepalajurusan_id', $this->me->id)
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->count();
        // dd($periksa);
        if ($periksa > 0) {
            $this->kepalajurusan  = true;
            $this->jurusan = jurusan::where('kepalajurusan_id', $this->me->id)
                ->where('tapel_id', Fungsi::app_tapel_aktif())
                ->with('kelas')
                ->first();
        } else {
            $this->kepalajurusan  = false;
            // return response()->json([
            //     'success'    => true,
            //     'data'    => "Bukan Kepala Jurusan",
            //     // 'tapel_id'    => Fungsi::app_tapel_aktif(),
            // ], 200);
            dd("bukan kepala jurusan");
        }
    }
}

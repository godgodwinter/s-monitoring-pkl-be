<?php

namespace App\Http\Controllers\pembimbinglapangan;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pembimbingsekolah;
use App\Models\pendaftaranprakerin_proses;
use App\Models\pendaftaranprakerin_prosesdetail;
use App\Models\Siswa;
use App\Models\tempatpkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class pembimbingLapanganSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:pembimbinglapangan', ['except' => []]);
    }
    public function index(Request $request)
    {
        $me_id = $this->guard()->user()->id;

        $items = (object)[];
        $dataAuth = $this->guard()->user();
        $items->me = $dataAuth;
        $getPendaftaranProses = pendaftaranprakerin_proses::where('pembimbinglapangan_id', $me_id)->where('status', 'Disetujui')->first();
        $getTempatPkl = $getPendaftaranProses ? tempatpkl::where('id', $getPendaftaranProses->tempatpkl_id)->first() : 0;
        $items->tempatpkl = $getTempatPkl;
        $getPembimbingLapangan = $dataAuth;
        $items->pembimbinglapangan = $getPembimbingLapangan;
        $getPembimbingSekolah = pembimbingsekolah::where('id', $getPendaftaranProses->pembimbingsekolah_id)->get();
        $items->pembimbingsekolah = $getPembimbingSekolah;
        $getSiswa = pendaftaranprakerin_prosesdetail::with('siswa')->where('pendaftaranprakerin_proses_id', $getPendaftaranProses->id)->get();
        // $items->siswaList = $getSiswa;
        $items->siswa = [];
        foreach ($getSiswa as $sl) {
            $tempSiswa = $sl->siswa;
            // push siswa ke array
            array_push($items->siswa, $tempSiswa);
        }

        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'dataAuth'    => $dataAuth,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }

    public function guard()
    {
        return Auth::guard('pembimbinglapangan');
    }
    public function me()
    {
        return response()->json($this->guard()->user());
    }
}

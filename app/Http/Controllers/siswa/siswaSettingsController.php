<?php

namespace App\Http\Controllers\siswa;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pendaftaranprakerin;
use App\Models\pendaftaranprakerin_detail;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class siswaSettingsController extends Controller
{
    public function index(Request $request)
    {
        $items = [
            'app_nama' =>  Fungsi::app_nama(),
            'app_namapendek' =>  Fungsi::app_namapendek(),
            'app_tapel_aktif' => Fungsi::app_tapel_aktif(),
            'app_tapel_aktif_nama' => Fungsi::app_tapel_aktif_nama(),
            'pendaftaranpkl' => Fungsi::pendaftaranpkl(),
            'login_siswa' => Fungsi::login_siswa(),
            'login_pembimbingsekolah' => Fungsi::login_pembimbingsekolah(),
            'login_pembimbinglapangan' => Fungsi::login_pembimbinglapangan(),
        ];
        $dataAuth = Siswa::with('kelasdetail')
            ->where('id', $this->guard()->user()->id)
            ->whereHas('kelasdetail', function ($query) {
                $query->whereHas('kelas', function ($query) {
                    $query->where('kelas.tapel_id', Fungsi::app_tapel_aktif());
                });
            })
            ->orderBy('updated_at', 'desc')
            ->first();
        // dd($dataAuth);
        return response()->json([
            'success'    => true,
            'data'    => $items,
            'dataAuth'    => $dataAuth,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }

    public function guard()
    {
        return Auth::guard('siswa');
    }
    public function me()
    {
        return response()->json($this->guard()->user());
    }
}

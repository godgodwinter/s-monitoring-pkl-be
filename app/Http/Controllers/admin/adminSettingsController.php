<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class adminSettingsController extends Controller
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
        return response()->json([
            'success'    => true,
            'data'    => $items,
            'dataAuth'    => $this->guard()->user(),
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function guard()
    {
        return Auth::guard();
    }
    public function me()
    {
        return response()->json($this->guard()->user());
    }
}

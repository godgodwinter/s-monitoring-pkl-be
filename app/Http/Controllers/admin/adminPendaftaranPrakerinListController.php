<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\pendaftaranprakerin;
use App\Models\Siswa;
use Illuminate\Http\Request;

class adminPendaftaranPrakerinListController extends Controller
{
    public function getall(Request $request)
    {
        $items = Siswa::with('pendaftaranprakerin')
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function menunggu(Request $request)
    {
        $items = pendaftaranprakerin::where('status', 'Proses Daftar')
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function disetujui(Request $request)
    {
        $items = pendaftaranprakerin::where('status', 'Disetujui')
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
}

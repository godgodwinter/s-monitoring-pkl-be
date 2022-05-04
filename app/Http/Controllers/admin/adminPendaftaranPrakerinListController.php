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
    public function belumdaftar(Request $request)
    {
        $items = Siswa::with('pendaftaranprakerin')
            ->whereDoesntHave('pendaftaranprakerin')
            ->get();

        // $datas = catatanpengembangandirisiswa::with('siswa')
        // ->where('sekolah_id', $sekolah_id)
        // ->whereHas('siswa', function ($query) {
        //     global $request;
        //     $query->where('siswa.nama', 'like', "%" . $request->cari . "%");
        // })
        // ->orWhereHas('kelas', function ($query) {
        //     global $request;
        //     $query->where('kelas.nama', 'like', "%" . $request->cari . "%");
        // })
        // ->where('sekolah_id', $sekolah_id)
        // ->orWhere('idedanimajinasi', 'like', "%" . $request->cari . "%")
        // ->where('sekolah_id', $sekolah_id)
        // ->paginate(Fungsi::paginationjml());
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function menunggu(Request $request)
    {
        $items = pendaftaranprakerin::with('siswa')->where('status', 'Proses Daftar')
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function disetujui(Request $request)
    {
        $items = pendaftaranprakerin::with('siswa')->where('status', 'Disetujui')
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function subsidebardata(Request $request)
    {
        $items = [
            'siswa' => siswa::count(),
            'belumdaftar' => Siswa::with('pendaftaranprakerin')
                ->whereDoesntHave('pendaftaranprakerin')
                ->count(),
            'menunggu' => pendaftaranprakerin::where('status', 'Proses Daftar')->count(),
            'disetujui' => pendaftaranprakerin::where('status', 'Disetujui')->count(),
            'sedangpkl' => 0,
            'telahselesai' => 0,
        ];
        // $items['siswa'] = siswa::count();
        // $items['belumdaftar'] = 1;
        // $items['menunggu'] =  pendaftaranprakerin::where('status', 'Proses Daftar')->count();
        // $items['disetujui'] = pendaftaranprakerin::where('status', 'Disetujui')->count();
        // $items['sedangpkl'] = 0;
        // $items['telahselesai'] = 0;
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
}

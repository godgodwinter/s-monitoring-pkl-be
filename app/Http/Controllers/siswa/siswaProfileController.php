<?php

namespace App\Http\Controllers\siswa;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pendaftaranprakerin;
use App\Models\pendaftaranprakerin_detail;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class siswaProfileController extends Controller
{
    public function index(Request $request)
    {
        $items = Siswa::with('kelasdetail')
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
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }

    public function pendaftaranpkl()
    {
        $id = $this->guard()->user()->id;
        $periksa = "Belum Daftar";
        $jmlData = pendaftaranprakerin::with('pendaftaranprakerin_detail')->where('tapel_id', Fungsi::app_tapel_aktif())->where('siswa_id', $id)->count();
        $tgl_pengajuan = null;
        if ($jmlData > 0) {
            $getData = pendaftaranprakerin::where('siswa_id', $id)->where('tapel_id', Fungsi::app_tapel_aktif())->first();
            $periksa = $getData->status;
            $tgl_pengajuan = count($getData->pendaftaranprakerin_detail) > 0 ? $getData->pendaftaranprakerin_detail[0]->tgl_pengajuan : '';
        }
        $getTempatpkl = null;
        $getPembimbinglapangan = null;
        $getPembimbingSekolah = null;
        $getDataDetail = null;
        if ($periksa == 'Menunggu' or $periksa == 'Disetujui' or $periksa == 'Ditolak') {
            $getDataDetail = pendaftaranprakerin_detail::where('pendaftaranprakerin_id', $getData->id)->orderBy('updated_at', 'desc')->first();
            $getTempatpkl = $getDataDetail->tempatpkl ? $getDataDetail->tempatpkl : null;
            $getPembimbinglapangan = $getDataDetail->pembimbinglapangan ? $getDataDetail->pembimbinglapangan : null;
            $getPembimbingSekolah = $getDataDetail->pembimbingsekolah ? $getDataDetail->pembimbingsekolah : null;
        }
        return response()->json([
            'success'    => true,
            'id'    => $id,
            'data'    => $periksa,
            'tgl_pengajuan'    => $tgl_pengajuan,
            // 'detail' => $getDataDetail,
            'tempatpkl' => $getTempatpkl,
            'pembimbinglapangan' => $getPembimbinglapangan,
            'pembimbingsekolah' => $getPembimbingSekolah,
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

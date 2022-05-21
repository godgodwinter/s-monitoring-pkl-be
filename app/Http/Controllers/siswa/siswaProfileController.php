<?php

namespace App\Http\Controllers\siswa;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pendaftaranprakerin;
use App\Models\pendaftaranprakerin_detail;
use App\Models\pendaftaranprakerin_proses;
use App\Models\pendaftaranprakerin_prosesdetail;
use App\Models\Siswa;
use App\Models\tempatpkl;
use Carbon\Carbon;
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
    protected $siswa_id;
    public function pendaftaranpkl()
    {
        $this->siswa_id = $this->guard()->user()->id;
        $id = $this->guard()->user()->id;
        $periksa = "Belum Daftar";
        $jmlData = pendaftaranprakerin::with('pendaftaranprakerin_detail')->where('tapel_id', Fungsi::app_tapel_aktif())->where('siswa_id', $id)->count();
        $tgl_penempatan = null;
        if ($jmlData > 0) {
            $getData = pendaftaranprakerin::where('siswa_id', $id)->where('tapel_id', Fungsi::app_tapel_aktif())->first();
            $periksa = $getData->status;
        }
        $getTempatpkl = null;
        $getPembimbinglapangan = null;
        $getPembimbingSekolah = null;
        $getDataDetail = null;
        $tempatpkl = null;
        if ($periksa == 'Proses Pengajuan Tempat PKL' or $periksa == 'Proses Penempatan PKL') {
        } elseif (
            $periksa == 'Proses Pemberkasan'
            or $periksa == 'Proses Persetujuan' or $periksa == 'Disetujui'  or $periksa == 'Ditolak'
        ) {
            $getPendaftaranPrakerinProsesDetail = pendaftaranprakerin_prosesdetail::with('pendaftaranprakerin_proses')
                ->where('siswa_id', $this->siswa_id)->whereHas('pendaftaranprakerin_proses', function ($query) {
                    $query->where('tapel_id', Fungsi::app_tapel_aktif());
                })
                ->first();
            $tgl_penempatan = $getPendaftaranPrakerinProsesDetail->pendaftaranprakerin_proses ?
                Fungsi::carbonCreatedAt($getPendaftaranPrakerinProsesDetail->pendaftaranprakerin_proses->created_at) : null;
            $tempatpkl = $getPendaftaranPrakerinProsesDetail->pendaftaranprakerin_proses->tempatpkl ? $getPendaftaranPrakerinProsesDetail->pendaftaranprakerin_proses->tempatpkl : null;
        }
        return response()->json([
            'success'    => true,
            'id'    => $id,
            'data'    => $periksa,
            'tgl_penempatan'    => $tgl_penempatan,
            'tempatpkl' => $tempatpkl,
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

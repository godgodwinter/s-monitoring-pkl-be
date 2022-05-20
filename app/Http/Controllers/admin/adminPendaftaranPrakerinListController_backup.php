<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pembimbinglapangan;
use App\Models\pembimbingsekolah;
use App\Models\pendaftaranprakerin;
use App\Models\pendaftaranprakerin_detail;
use App\Models\pendaftaranprakerin_proses;
use App\Models\Siswa;
use App\Models\tempatpkl;
use Illuminate\Http\Request;

class adminPendaftaranPrakerinListController extends Controller
{
    protected $siswa_id = null;
    public function getall(Request $request)
    {
        $items = Siswa::with('pendaftaranprakerin')
            ->whereHas('kelasdetail', function ($query) {
                $query->whereHas('kelas', function ($query) {
                    $query->where('kelas.tapel_id', Fungsi::app_tapel_aktif());
                });
            })
            // ->whereHas('pendaftaranprakerin', function ($query) {
            //     $query->where('tapel_id', Fungsi::app_tapel_aktif());
            // })
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }

    public function getSiswaTelahDaftar(Request $request)
    {
        $items = Siswa::with('pendaftaranprakerin')
            ->whereHas('kelasdetail', function ($query) {
                $query->whereHas('kelas', function ($query) {
                    $query->where('kelas.tapel_id', Fungsi::app_tapel_aktif());
                });
            })
            ->whereHas('pendaftaranprakerin')
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function getSiswaBelumDaftar(Request $request)
    {
        $items = Siswa::with('pendaftaranprakerin')
            ->whereHas('kelasdetail', function ($query) {
                $query->whereHas('kelas', function ($query) {
                    $query->where('kelas.tapel_id', Fungsi::app_tapel_aktif());
                });
            })
            ->whereDoesntHave('pendaftaranprakerin')
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function getpilihanlankah2(Request $request)
    {
        $getTempatpkl = tempatpkl::where('tapel_id', Fungsi::app_tapel_aktif())->get();
        $getPembimbinglapangan = pembimbinglapangan::get();
        $getPembimbingSekolah = pembimbingsekolah::get();
        $items = [
            'tempatpkl' => $getTempatpkl,
            'pembimbinglapangan' => $getPembimbinglapangan,
            'pembimbingsekolah' => $getPembimbingSekolah,
        ];
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function belumdaftar(Request $request)
    {
        $items = Siswa::with('pendaftaranprakerin')
            ->with('kelasdetail')
            ->whereDoesntHave('pendaftaranprakerin')
            ->whereHas('kelasdetail', function ($query) {
                $query->whereHas('kelas', function ($query) {
                    $query->where('kelas.tapel_id', Fungsi::app_tapel_aktif());
                });
            })
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
    public function prosesdaftar(Request $request)
    {
        $items = pendaftaranprakerin::with('siswa')->where('status', 'Proses Daftar')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
    public function menunggu(Request $request)
    {
        $items = pendaftaranprakerin::with('siswa')->where('status', 'Menunggu')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
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
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
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
            'siswa' => siswa::with('kelasdetail')
                ->whereHas('kelasdetail', function ($query) {
                    $query->whereHas('kelas', function ($query) {
                        $query->where('kelas.tapel_id', Fungsi::app_tapel_aktif());
                    });
                })->count(),
            'belumdaftar' => Siswa::with('pendaftaranprakerin')
                ->whereHas('kelasdetail', function ($query) {
                    $query->whereHas('kelas', function ($query) {
                        $query->where('kelas.tapel_id', Fungsi::app_tapel_aktif());
                    });
                })
                ->whereDoesntHave('pendaftaranprakerin')
                ->count(),
            'prosesPengajuanTempatPKL' => pendaftaranprakerin::where('status', 'Proses Pengajuan Tempat PKL')
                ->where('tapel_id', Fungsi::app_tapel_aktif())->count(),
            'prosesPenempatanPKL' => pendaftaranprakerin::where('status', 'Proses Penempatan PKL')
                ->where('tapel_id', Fungsi::app_tapel_aktif())->count(),
            'prosesPemberkasan' => pendaftaranprakerin::where('status', 'Proses Pemberkasan')
                ->where('tapel_id', Fungsi::app_tapel_aktif())->count(),
            'prosesPersetujuan' => pendaftaranprakerin::where('status', 'Proses Persetujuan')
                ->where('tapel_id', Fungsi::app_tapel_aktif())->count(),
            'disetujui' => pendaftaranprakerin::where('status', 'Disetujui')
                ->where('tapel_id', Fungsi::app_tapel_aktif())->count(),
            'ditolak' => pendaftaranprakerin::where('status', 'Ditolak')
                ->where('tapel_id', Fungsi::app_tapel_aktif())->count(),
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
    public function periksaid($id)
    {

        $this->siswa_id = $id;
        $siswa_id = $id;
        $id = $id;

        // dd($this->siswa_id);
        // insert data siswa
        $items = [];
        $getTempatpkl = null;
        $getPembimbinglapangan = null;
        $getPembimbingSekolah = null;
        $getDataDetail = null;
        $periksa = "Belum Daftar";
        $kode = 500;
        $tgl_daftar = date('Y-m-d');
        $tapel_id = Fungsi::app_tapel_aktif();
        $getData = [];
        $jmlData = pendaftaranprakerin::with('pendaftaranprakerin_detail')->where('tapel_id', Fungsi::app_tapel_aktif())->where('siswa_id', $id)->count();
        $tgl_pengajuan = null;
        if ($jmlData > 0) {
            //get pendaftaranprakerin_proses where tapel_id sekarang and get siswa
            $get_pendaftaranprakerin = pendaftaranprakerin::where('tapel_id', Fungsi::app_tapel_aktif())->where('siswa_id', $id)->first();
            $periksa = $get_pendaftaranprakerin->status;
        }
        if ($periksa != 'Belum Daftar') {

            $kode = 200;
            //periksa penempatan di tabel pendaftaranprakerin_proses dan pendaftaranprakerin_prosesdetail
            $getData = pendaftaranprakerin_proses::with('pendaftaranprakerin_prosesdetail')
                ->where('tapel_id', Fungsi::app_tapel_aktif())
                ->whereHas('pendaftaranprakerin_prosesdetail', function ($query) {
                    $query->where('siswa_id', $this->siswa_id);
                })
                ->first();

            //periksa pembimbing di table pendaftaranprakerin_prosesdetail_detail
            $get_jml_pendaftaranprakerin_detail = pendaftaranprakerin_detail::where('pendaftaranprakerin_id', $get_pendaftaranprakerin->id)->count();
            if ($get_jml_pendaftaranprakerin_detail > 0) {
                $get_pendaftaranprakerin_detail = pendaftaranprakerin_detail::with('pembimbingsekolah')->with('pembimbinglapangan')->where('pendaftaranprakerin_id', $get_pendaftaranprakerin->id)->first();
                $getPembimbinglapangan = $get_pendaftaranprakerin_detail->pembimbinglapangan ? $get_pendaftaranprakerin_detail->pembimbinglapangan->nama : null;
                $getPembimbingSekolah = $get_pendaftaranprakerin_detail->pembimbingsekolah ? $get_pendaftaranprakerin_detail->pembimbingsekolah->nama : null;
            }
        }
        return response()->json([
            'success'    => true,
            'data'    => $getData,
            'status'    => $periksa,
            'tgl_pengajuan'    => $tgl_pengajuan,
            // 'detail' => $getDataDetail,
            'tempatpkl' => $getTempatpkl,
            'pembimbinglapangan' => $getPembimbinglapangan,
            'pembimbingsekolah' => $getPembimbingSekolah,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], $kode);
        // $periksa = "Belum Daftar";
        // $jmlData = pendaftaranprakerin::with('pendaftaranprakerin_detail')->where('tapel_id', Fungsi::app_tapel_aktif())->where('siswa_id', $id)->count();
        // $tgl_pengajuan = null;
        // if ($jmlData > 0) {
        //     $getData = pendaftaranprakerin::where('siswa_id', $id)->where('tapel_id', Fungsi::app_tapel_aktif())->first();
        //     $periksa = $getData->status;
        //     $tgl_pengajuan = count($getData->pendaftaranprakerin_detail) > 0 ? $getData->pendaftaranprakerin_detail[0]->tgl_pengajuan : '';
        // }
        // $getTempatpkl = null;
        // $getPembimbinglapangan = null;
        // $getPembimbingSekolah = null;
        // $getDataDetail = null;
        // if ($periksa == 'Menunggu' or $periksa == 'Disetujui' or $periksa == 'Ditolak') {
        //     $getDataDetail = pendaftaranprakerin_detail::where('pendaftaranprakerin_id', $getData->id)->orderBy('updated_at', 'desc')->first();
        //     $getTempatpkl = $getDataDetail->tempatpkl ? $getDataDetail->tempatpkl : null;
        //     $getPembimbinglapangan = $getDataDetail->pembimbinglapangan ? $getDataDetail->pembimbinglapangan : null;
        //     $getPembimbingSekolah = $getDataDetail->pembimbingsekolah ? $getDataDetail->pembimbingsekolah : null;
        // }
        // return response()->json([
        //     'success'    => true,
        //     'id'    => $id,
        //     'data'    => $periksa,
        //     'tgl_pengajuan'    => $tgl_pengajuan,
        //     // 'detail' => $getDataDetail,
        //     'tempatpkl' => $getTempatpkl,
        //     'pembimbinglapangan' => $getPembimbinglapangan,
        //     'pembimbingsekolah' => $getPembimbingSekolah,
        //     // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        // ], 200);
    }
}

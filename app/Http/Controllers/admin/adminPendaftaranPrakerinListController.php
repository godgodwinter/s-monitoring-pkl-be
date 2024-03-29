<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pembimbinglapangan;
use App\Models\pembimbingsekolah;
use App\Models\pendaftaranprakerin;
use App\Models\pendaftaranprakerin_detail;
use App\Models\pendaftaranprakerin_pengajuansiswa;
use App\Models\pendaftaranprakerin_proses;
use App\Models\pendaftaranprakerin_prosesdetail;
use App\Models\Siswa;
use App\Models\tempatpkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
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
        ], 200);
    }
    public function getProsesPengajuanTempatPKL(Request $request)
    {
        $items = pendaftaranprakerin::with('siswa')->where('status', 'Proses Pengajuan Tempat PKL')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }
    public function getProsesPenempatanPKL(Request $request)
    {
        $items = pendaftaranprakerin::with('siswa')->where('status', 'Proses Penempatan PKL')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }

    public function getProsesPemberkasan(Request $request)
    {
        $items = pendaftaranprakerin::with('siswa')->where('status', 'Proses Pemberkasan')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }

    public function getProsesPersetujuan(Request $request)
    {
        $items = pendaftaranprakerin::with('siswa')->where('status', 'Proses Persetujuan')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }
    // protected $siswa_id;
    public function addpembimbingsekolah(pendaftaranprakerin_proses $pendaftaranprakerin_proses, Request $request)
    {
        $pembimbingsekolah_id = $request->pembimbingsekolah_id;
        $items = '';

        pendaftaranprakerin_proses::where('id', $pendaftaranprakerin_proses->id)
            ->update([
                'pembimbingsekolah_id'     =>   $request->pembimbingsekolah_id,
                'penilai_id'     =>   $request->penilai_id,
                'updated_at' => date("Y-m-d H:i:s")
            ]);

        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }
    public function getDisetujui(Request $request)
    {
        $items = pendaftaranprakerin::with('siswa')->where('status', 'Disetujui')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        foreach ($items as $item) {
            $this->siswa_id = $item->siswa_id;
            // $getpendaftaranprakerin_prosesdetailId = pendaftaranprakerin_prosesdetail::where('siswa_id', $item->siswa_id)->first();
            // $getpendaftaranprakerin_prosesId = pendaftaranprakerin_proses::first();
            $getpendaftaranprakerin_prosesId = pendaftaranprakerin_proses::with('pendaftaranprakerin_prosesdetail')
                ->with('pembimbingsekolah')
                ->with('penilai')
                ->whereHas('pendaftaranprakerin_prosesdetail', function ($query) {
                    $query->where('siswa_id', $this->siswa_id);
                })
                ->first();
            $item->pembimbingsekolah = $getpendaftaranprakerin_prosesId ? $getpendaftaranprakerin_prosesId->pembimbingsekolah : null;
            $item->pembimbingsekolah_nama = $item->pembimbingsekolah ? $item->pembimbingsekolah->nama : null;
            $item->pendaftaranprakerin_proses_id = $getpendaftaranprakerin_prosesId ? $getpendaftaranprakerin_prosesId->id : null;
            $item->penilai = $getpendaftaranprakerin_prosesId ? $getpendaftaranprakerin_prosesId->penilai : null;
            $item->penilai_nama = $item->penilai ? $item->penilai->nama : null;
        }
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }
    public function getDitolak(Request $request)
    {
        $items = pendaftaranprakerin::with('siswa')->where('status', 'Ditolak')
            ->orderBy('created_at', 'desc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }
    public function getSiswaPilihTempat(tempatpkl $tempatpkl, Request $request)
    {
        $this->tempatpkl_id = $tempatpkl->id;
        $items = Siswa::with('pendaftaranprakerin')
            ->whereHas('pendaftaranprakerin', function ($query) {
                $query->whereHas('pendaftaranprakerin_pengajuansiswa', function ($query) {
                    $query->where('pendaftaranprakerin_pengajuansiswa.tempatpkl_id', $this->tempatpkl_id);
                });
            })
            // ->whereDoesntHave('pendaftaranprakerin')
            ->get();

        return response()->json([
            'success'    => true,
            'data'    => $items,
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
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }
    public function periksaid($id)
    {

        $this->siswa_id = $id;
        $siswa_id = $id;
        $id = $id;

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
    }
    protected $tempatpkl_id;
    public function getDataTempatPKL(Request $request)
    {
        $cari = $request->cari;
        $tersedia = $request->tersedia; //Tersedia atau Tidak Tersedia atau Semua Data
        $items = tempatpkl::where('nama', 'like', "%" . $cari . "%")->get();
        $data = [];
        foreach ($items as $item) {
            $this->tempatpkl_id = $item->id;
            $periksa = pendaftaranprakerin_prosesdetail::with('pendaftaranprakerin_proses')
                ->whereHas('pendaftaranprakerin_proses', function ($query) {
                    $query->where('tapel_id', Fungsi::app_tapel_aktif())
                        ->where('status', null)
                        ->where('tempatpkl_id', $this->tempatpkl_id);
                })
                ->count();
            $item['terisi'] = $periksa;
            // $item['tersedia'] = 0;
            $item['tersedia'] = $item->kuota - $periksa;
            if ($tersedia == 'Tersedia') {
                // if ($item['tersedia'] > 0) {
                if ($item['status'] == 'Tersedia') {
                    array_push($data, $item);
                };
                // }
            } else if ($tersedia == 'Tidak Tersedia') {
                if ($item['status'] != 'Tersedia') {
                    array_push($data, $item);
                }
            } else {
                array_push($data, $item);
            }
        }
        // get identitas tempat pkl dan teman yang berada di tempat pkl yang sama serta status pengajuan diacc/ditolak
        return response()->json([
            'success'    => true,
            'data'    => $data,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }

    // protected $siswa_id;
    public function getDataSiswa(Request $request)
    {
        $cari = $request->cari;
        $this->tempatpkl_id = $request->tempatpkl_id;
        $tersedia = $request->tersedia; //Semua Data atau Tersedia atau Tidak Tersedia
        $items = siswa::with('kelasdetail')->where('nama', 'like', "%" . $cari . "%")
            ->get();
        $data = [];
        foreach ($items as $item) {
            $this->siswa_id = $item->id;
            $periksa = pendaftaranprakerin_pengajuansiswa::with('pendaftaranprakerin')
                ->whereHas('pendaftaranprakerin', function ($query) {
                    $query->where('tapel_id', Fungsi::app_tapel_aktif())->where('siswa_id', $this->siswa_id);
                })
                ->where('tempatpkl_id', $this->tempatpkl_id)
                ->count();
            if ($tersedia == 'Memilih Tempat Ini') {
                // $data = 'Memilih Tempat Ini';
                if ($periksa > 0) {
                    // periksa apakah sudah terdaftar di tempat pkl lain jika sudah maka skip
                    $periksaTerdaftar = pendaftaranprakerin_prosesdetail::with('pendaftaranprakerin_proses')->whereHas('pendaftaranprakerin_proses', function ($query) {
                        $query->where('tapel_id', Fungsi::app_tapel_aktif())->whereNot('status', 'Ditolak');
                        // $query->where('status', NULL);
                    })
                        ->where('siswa_id', $this->siswa_id)->count();
                    if ($periksaTerdaftar == 0) {
                        $periksaPemberkasanSetuju = pendaftaranprakerin::where('siswa_id', $item->id)->where('tapel_id', Fungsi::app_tapel_aktif())->where('status', 'Disetujui')
                            ->orWhere('siswa_id', $item->id)->where('tapel_id', Fungsi::app_tapel_aktif())->where('status', 'Proses Pemberkasan')
                            ->orWhere('siswa_id', $item->id)->where('tapel_id', Fungsi::app_tapel_aktif())->where('status', 'Proses Persetujuan');
                        if ($periksaPemberkasanSetuju->count() == 0) {
                            array_push($data, $item);
                        }
                    }
                }
            } else {
                // periksa apakah sudah terdaftar di tempat pkl lain jika sudah maka skip
                $periksaTerdaftar = pendaftaranprakerin_prosesdetail::with('pendaftaranprakerin_proses')->whereHas('pendaftaranprakerin_proses', function ($query) {
                    $query->where('tapel_id', Fungsi::app_tapel_aktif())->whereNot('status', 'Ditolak');
                    // $query->where('status', NULL);
                })
                    ->where('siswa_id', $this->siswa_id)->count();
                if ($periksaTerdaftar == 0) {
                    // array_push($data, $item);
                    $periksaPemberkasanSetuju = pendaftaranprakerin::where('siswa_id', $item->id)->where('tapel_id', Fungsi::app_tapel_aktif())->where('status', 'Disetujui')
                        ->orWhere('siswa_id', $item->id)->where('tapel_id', Fungsi::app_tapel_aktif())->where('status', 'Proses Pemberkasan')
                        ->orWhere('siswa_id', $item->id)->where('tapel_id', Fungsi::app_tapel_aktif())->where('status', 'Proses Persetujuan');
                    if ($periksaPemberkasanSetuju->count() == 0) {
                        array_push($data, $item);
                    }
                }
            }
        }
        // get identitas tempat pkl dan teman yang berada di tempat pkl yang sama serta status pengajuan diacc/ditolak
        return response()->json([
            'success'    => true,
            'data'    => $data,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }


    public function periksaidbaru($siswa_id)
    {
        $UploadDir = URL::to('/') . '/fileupload/suratbalasan/';
        $this->siswa_id = $siswa_id;
        $id = $this->siswa_id;
        $periksa = "Belum Daftar";
        $file = null;
        $anggota = [];
        $periksaPendaftaranPrakerin = pendaftaranprakerin::with('pendaftaranprakerin_detail')->where('tapel_id', Fungsi::app_tapel_aktif())->where('siswa_id', $this->siswa_id);
        $tgl_penempatan = null;
        if ($periksaPendaftaranPrakerin->count() > 0) {
            $periksa = $periksaPendaftaranPrakerin->first()->status;
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


            // $anggota = $getPendaftaranPrakerinProsesDetail->id;
            $getAnggota = pendaftaranprakerin_proses::with('pendaftaranprakerin_prosesdetail')->where('status', null)->where('tempatpkl_id', $tempatpkl->id)->where('tapel_id', Fungsi::app_tapel_aktif());
            if ($getAnggota->first()->file) {
                $file = $UploadDir . $getAnggota->first()->file;
            }
            if ($getAnggota->count() > 0) {
                $getAnggotaFirst = $getAnggota->first();
                $objAnggota = [];
                foreach ($getAnggotaFirst->pendaftaranprakerin_prosesdetail as $ga) {
                    $objAnggota['id'] = $ga->siswa_id;
                    $objAnggota['nomeridentitas'] = $ga->siswa->nomeridentitas;
                    $objAnggota['nama'] = $ga->siswa->nama;
                    $objAnggota['jk'] = $ga->siswa->jk;
                    $objAnggota['alamat'] = $ga->siswa->alamat;
                    $objAnggota['jurusan'] = "{$ga->siswa->kelasdetail->kelas->jurusan}";
                    $objAnggota['kelas'] = "{$ga->siswa->kelasdetail->kelas->tingkatan} {$ga->siswa->kelasdetail->kelas->jurusan} {$ga->siswa->kelasdetail->kelas->suffix}";
                    // $objAnggota['kelas'] = $ga->siswa->kelas->tingkatan + ' ' + $ga->siswa->kelas->jurusan + ' ' + $ga->siswa->kelas->suffix;
                    // $objAnggota['nama'] = $ga->pendaftaranprakerin_prosesdetail->siswa;
                    // array push
                    array_push($anggota, $objAnggota);
                }
            }
        }

        return response()->json([
            'success'    => true,
            'id'    => $id,
            'data'    => $periksa,
            'tgl_penempatan'    => $tgl_penempatan,
            'tempatpkl' => $tempatpkl,
            'anggota' => $anggota,
            'file' => $file,
            'pembimbinglapangan' => $getPembimbinglapangan,
            'pembimbingsekolah' => $getPembimbingSekolah,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }
}

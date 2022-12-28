<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\kelas;
use App\Models\kelasdetail;
use App\Models\pembimbinglapangan;
use App\Models\pembimbingsekolah;
use App\Models\pendaftaranprakerin;
use App\Models\pendaftaranprakerin_proses;
use App\Models\pendaftaranprakerin_prosesdetail;
use App\Models\Siswa;
use App\Models\tempatpkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class adminSiswaController extends Controller
{
    public function index(Request $request)
    {
        $items = Siswa::with('kelasdetail')->orderBy('id', 'desc')
            ->whereHas('kelasdetail', function ($query) {
                $query->whereHas('kelas', function ($query) {
                    $query->where('kelas.tapel_id', Fungsi::app_tapel_aktif());
                })->with('jurusan_table');
            })
            ->with('tagihan')
            ->get();

        foreach ($items as $result) {
            $result->total_tagihan = $result->tagihan ? $result->tagihan->total_tagihan : 0;
            if ($result->tagihan) {
                $result->pembayaran_jml = $result->tagihan->pembayaran ? $result->tagihan->pembayaran->count() : 0;
                $result->pembayaran_total = $result->tagihan->pembayaran ? $result->tagihan->pembayaran->sum('nominal_bayar') : 0;
                $result->pembayaran_persen = $result->tagihan->pembayaran->sum('nominal_bayar') < $result->tagihan->total_tagihan  ? ($result->tagihan->pembayaran ? number_format($result->tagihan->pembayaran->sum('nominal_bayar') / $result->tagihan->total_tagihan * 100) : 0) : 100;
                $result->pembayaran_persen_kurang = number_format(100 - $result->pembayaran_persen, 2);
            }
        }
        // ->whereHas('siswa', function ($query) {
        //     global $request;
        //     $query->where('siswa.nama', 'like', "%" . $request->cari . "%");
        // })
        foreach ($items as $item) {
            $item->kelas_nama = $item->kelasdetail ? $item->kelasdetail->kelas->tingkatan . " " . $item->kelasdetail->kelas->jurusan_table->nama . " " . $item->kelasdetail->kelas->suffix : null;
        }

        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }

    public function getall(Request $request)
    {
        $items = Siswa::with('kelasdetail')->orderBy('nama', 'asc')
            ->get();

        // ->whereHas('siswa', function ($query) {
        //     global $request;
        //     $query->where('siswa.nama', 'like', "%" . $request->cari . "%");
        // })

        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }

    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
            // 'email'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $siswa_id = DB::table('siswa')->insertGetId(
            array(
                'nama'     =>   $request->nama,
                // 'email'     =>   $request->email,
                // 'username'     =>   $request->username,
                'nomeridentitas'     =>   $request->nomeridentitas,
                'password'     =>   Hash::make(123),
                'agama'     =>   $request->agama,
                'tempatlahir'     =>   $request->tempatlahir,
                'tgllahir'     =>   $request->tgllahir,
                'alamat'     =>   $request->alamat,
                'jk'     =>   $request->jk,
                'telp'     =>   $request->telp,
                // 'kelas_id'     =>   $request->kelas_id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        kelasdetail::insert(
            array(
                'siswa_id'     =>   $siswa_id,
                'kelas_id'     =>   $request->kelas_id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil ditambahkan!',
        ], 200);
    }

    public function edit(siswa $item)
    {
        $item = Siswa::with('kelasdetail')
            ->with('tagihan')->find($item->id);
        $item->total_tagihan = $item->tagihan ? $item->tagihan->total_tagihan : 0;
        if ($item->tagihan) {
            $item->pembayaran_jml = $item->tagihan->pembayaran ? $item->tagihan->pembayaran->count() : 0;
            $item->pembayaran_total = $item->tagihan->pembayaran ? $item->tagihan->pembayaran->sum('nominal_bayar') : 0;
            $item->pembayaran_persen = $item->tagihan->pembayaran->sum('nominal_bayar') < $item->tagihan->total_tagihan  ? ($item->tagihan->pembayaran ? number_format($item->tagihan->pembayaran->sum('nominal_bayar') / $item->tagihan->total_tagihan * 100) : 0) : 100;
            $item->pembayaran_persen_kurang = number_format(100 - $item->pembayaran_persen, 2);
        }
        $item->jurusan_nama = $item->kelasdetail ? $item->kelasdetail->kelas->jurusan_table->nama : null;
        $item->kelas_nama = $item->kelasdetail ? $item->kelasdetail->kelas->tingkatan . " " . $item->kelasdetail->kelas->jurusan_table->nama . " " . $item->kelasdetail->kelas->suffix : null;
        return response()->json([
            'success'    => true,
            'data'    => $item,
        ], 200);
    }
    public function update(siswa $item, Request $request)
    {

        //set validation
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
            // 'email'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        siswa::where('id', $item->id)
            ->update([
                'nama'     =>   $request->nama,
                // 'email'     =>   $request->email,
                // 'username'     =>   $request->username,
                'nomeridentitas'     =>   $request->nomeridentitas,
                // 'password'     =>   $request->password,
                'agama'     =>   $request->agama,
                'tempatlahir'     =>   $request->tempatlahir,
                'tgllahir'     =>   $request->tgllahir,
                'alamat'     =>   $request->alamat,
                'jk'     =>   $request->jk,
                'telp'     =>   $request->telp,
                // 'kelas_id'     =>   $request->kelas_id,
                'updated_at' => date("Y-m-d H:i:s")
            ]);

        $periksa = kelasdetail::where('siswa_id', $item->id)
            ->where('kelas_id', $request->kelas_id)
            ->count();
        // dd($periksa);
        if ($periksa < 1) {
            //select kelas where tapel id
            $getKelasNow = kelas::where('tapel_id', Fungsi::app_tapel_aktif())->get();

            foreach ($getKelasNow as $k) {
                // $feed = kelasdetail::where('siswa_id', $item->id)
                //     ->where('kelas_id', $k->id)
                //     ->first();
                // $feed->forceDelete();
                // kelasdetail::destroy($k->id);
                kelasdetail::where('siswa_id', $item->id)
                    ->where('kelas_id', $k->id)->forceDelete();
            }

            // remove
            kelasdetail::insert(
                array(
                    'siswa_id'     =>   $item->id,
                    'kelas_id'     =>   $request->kelas_id,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                )
            );
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
        ], 200);
    }
    public function destroy(siswa $item)
    {
        kelasdetail::where('siswa_id', $item->id)->forceDelete();
        siswa::where('id', $item->id)->forceDelete();
        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di hapus!',
        ], 200);
    }
    public function generatepassword(siswa $item, Request $request)
    {
        // $faker = Faker::create('id_ID');


        // $pass = $faker->password(6, 6, $requireNumeric = true, $requireLowercase = true, $requireUppercase = true, $requireSpecial = false, $customCharacters = '');
        // $pass = $faker->regexify('[A-Za-z0-9]{6}');
        $pass = 123;
        siswa::where('id', $item->id)
            ->update([
                'password' => Hash::make($pass),
                'updated_at' => date("Y-m-d H:i:s")
            ]);


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
            'password' => $pass,
        ], 200);
    }

    public function generatepasswordall(Request $request)
    {
        $faker = Faker::create('id_ID');


        $pass = 123;
        $getSiswa = siswa::get();
        foreach ($getSiswa as $item) {
            siswa::where('id', $item->id)
                ->update([
                    'password' => Hash::make($pass),
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
        }


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
            'password' => $pass,
        ], 200);
    }
    public function addtotapelaktif(siswa $item, Request $request)
    {
        // dd($request->kelas_id);
        // 1. periksa apakah siswa_id sudah ada di tapel aktif
        $periksaSiswa = Siswa::with('kelasdetail')->orderBy('nama', 'asc')
            ->whereHas('kelasdetail', function ($query) {
                $query->whereHas('kelas', function ($query) {
                    $query->where('kelas.tapel_id', Fungsi::app_tapel_aktif());
                });
            })
            ->where('id', $item->id)
            ->count();
        // jika sudah maka skip
        // jika belum maka insert
        if ($periksaSiswa < 1) {

            $periksa = kelasdetail::where('siswa_id', $item->id)
                ->where('kelas_id', $request->kelas_id)
                ->count();
            if ($periksa < 1) {
                //select kelas where tapel id
                $getKelasNow = kelas::where('tapel_id', Fungsi::app_tapel_aktif())->get();

                foreach ($getKelasNow as $k) {
                    // $feed = kelasdetail::where('siswa_id', $item->id)
                    //     ->where('kelas_id', $k->id)
                    //     ->first();
                    // $feed->forceDelete();
                    // kelasdetail::destroy($k->id);
                    kelasdetail::where('siswa_id', $item->id)
                        ->where('kelas_id', $k->id)->forceDelete();
                }

                // remove
                kelasdetail::insert(
                    array(
                        'siswa_id'     =>   $item->id,
                        'kelas_id'     =>   $request->kelas_id,
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s")
                    )
                );
            }
        }
        $item = Siswa::with('kelasdetail')->find($item->id);
        return response()->json([
            'success'    => true,
            'data'    => 'Data Berhasil ditambahkan',
            'siswa'    => $item,
        ], 200);
    }
    protected $siswa_id;
    protected $tempatpkl_id;
    protected $proses_id;
    public function profile(siswa $item)
    {
        $this->siswa_id = $item->id;
        $siswa = [];
        $tempatpkl = [];
        $pembimbinglapangan = [];
        $pembimbingsekolah = [];
        $anggota = [];
        $kepalaprodi = [];
        $status = null;

        // data siswa
        $siswa = Siswa::where('id', $item->id)->with('tagihan')->first();

        $siswa->min_pembayaran = Fungsi::app_min_pembayaran();
        $siswa->total_tagihan = $siswa->tagihan ? $siswa->tagihan->total_tagihan : 0;
        if ($siswa->tagihan) {
            $siswa->pembayaran_jml = $siswa->tagihan->pembayaran ? $siswa->tagihan->pembayaran->count() : 0;
            $siswa->pembayaran_total = $siswa->tagihan->pembayaran ? $siswa->tagihan->pembayaran->sum('nominal_bayar') : 0;
            $siswa->pembayaran_persen = $siswa->tagihan->pembayaran->sum('nominal_bayar') < $siswa->tagihan->total_tagihan  ? ($siswa->tagihan->pembayaran ? number_format($siswa->tagihan->pembayaran->sum('nominal_bayar') / $siswa->tagihan->total_tagihan * 100) : 0) : 100;
            $siswa->pembayaran_persen_kurang = number_format(100 - $siswa->pembayaran_persen, 2);
        }
        $siswa['kelas_nama'] = "{$siswa->kelasdetail->kelas->tingkatan} {$siswa->kelasdetail->kelas->jurusan_table->nama} {$siswa->kelasdetail->kelas->suffix}";

        // tempat pkl
        $getPendaftaranProsesDetail = pendaftaranprakerin_prosesdetail::with('pendaftaranprakerin_proses')->where('siswa_id', $this->siswa_id)
            ->whereHas('pendaftaranprakerin_proses', function ($query) {
                $query->where('pendaftaranprakerin_proses.status', '!=', 'Ditolak')->where('tapel_id', Fungsi::app_tapel_aktif());
            })->first();
        $getPendaftaranProses_id = $getPendaftaranProsesDetail ? $getPendaftaranProsesDetail->pendaftaranprakerin_proses->id : null;
        $Getstatus = pendaftaranprakerin::where('siswa_id', $this->siswa_id)->where('tapel_id', Fungsi::app_tapel_aktif())->first();
        $status = $Getstatus ? $Getstatus->status : 'Belum Daftar';

        $tempatpkl = $getPendaftaranProsesDetail ? $getPendaftaranProsesDetail->pendaftaranprakerin_proses->tempatpkl : null;
        // pembimbing
        if ($getPendaftaranProsesDetail) {
            $getPendaftaranProses = pendaftaranprakerin_prosesdetail::with('siswa')
                ->where('pendaftaranprakerin_proses_id', $getPendaftaranProses_id)
                ->get();
            foreach ($getPendaftaranProses as $gp) {
                $anggota[] = $gp ? $gp->siswa : null;
            }
            // $anggota[] = $getPendaftaranProses ? $getPendaftaranProses->pendaftaranprakerin_proses->siswa : null;
        }

        if ($tempatpkl) {
            $pembimbinglapangan = pembimbinglapangan::where('id', $tempatpkl->penanggungjawab)->first();
            $getPembimbingSekolah = pendaftaranprakerin_proses::where('id', $getPendaftaranProses_id)->first();
            $pembimbingsekolah = $getPembimbingSekolah ? pembimbingsekolah::where('id', $getPembimbingSekolah->pembimbingsekolah_id)->first() : null;
            // $pembimbingsekolah = $getPembimbingSekolah->pembimbingsekolah_id;
        }
        $kepalajurusan = pembimbingsekolah::where('id', $siswa->kelasdetail->kelas->jurusan_table->kepalajurusan_id)->first();
        // kelas_id
        $item = Siswa::with('kelasdetail')->find($item->id);
        return response()->json([
            'success'    => true,
            'data'    => ([
                'siswa' => $siswa,
                'status' => $status,
                'tempatpkl' => $tempatpkl,
                'anggota' => $anggota,
                'pembimbinglapangan' => $pembimbinglapangan,
                'pembimbingsekolah' => $pembimbingsekolah,
                'kepalajurusan' => $kepalajurusan,
            ]),
        ], 200);
    }
}

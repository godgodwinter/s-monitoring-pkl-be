<?php

namespace App\Services\Impl\Tagihan;

use App\Helpers\Fungsi;
use App\Models\kelasdetail;
use App\Models\pembayaran;
use App\Models\tagihan;
use Faker\Factory as Faker;

class TagihanClass
{
    protected $faker;
    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
    }

    public function tagihan_get()
    {
        $result = tagihan::with('pembayaran')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->with('tapel')
            ->with('siswa')
            ->get();

        foreach ($result as $rs) {
            $rs->pembayaran_jml = $rs->pembayaran ? $rs->pembayaran->count() : 0;
            $rs->pembayaran_total = $rs->pembayaran ? $rs->pembayaran->sum('nominal_bayar') : 0;
            $rs->pembayaran_persen = $rs->pembayaran->sum('nominal_bayar') < $rs->total_tagihan ? $rs->pembayaran ? number_format($rs->pembayaran->sum('nominal_bayar') / $rs->total_tagihan * 100) : 0 : 100;
            $rs->pembayaran_persen_kurang = number_format(100 - $rs->pembayaran_persen, 2);
        }
        return $result;
    }

    public function tagihan_store(object $dataForm)
    {
        $data_id = tagihan::insertGetId(
            array(
                'siswa_id'     =>   $dataForm->siswa_id,
                'tgl'     =>   $dataForm->tgl,
                'total_tagihan'     =>   $dataForm->total_tagihan,
                'tapel_id'     =>   $dataForm->tapel_id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );
        return 'Data berhasil ditambahkan';
    }
    public function tagihan_store_kelas($kelas_id, object $dataForm)
    {
        $getSiswaId = kelasdetail::where('kelas_id', $kelas_id)->get();
        foreach ($getSiswaId as $dataGetSiswaId) {
            $periksa = tagihan::where('siswa_id', $dataGetSiswaId->siswa_id)
                ->where('tapel_id', Fungsi::app_tapel_aktif());
            $dataForm->siswa_id = $dataGetSiswaId->siswa_id;
            $dataForm->tapel_id = Fungsi::app_tapel_aktif();
            // dd($dataForm, $dataForm->siswa_id, $dataForm->tapel_id, $dataGetSiswaId->siswa_id, $periksa->count());
            if ($periksa->count() > 0) {

                // dd($dataForm, $dataForm->siswa_id, $periksa->count());
                // update
                $this->tagihan_update_where_siswa($dataGetSiswaId->siswa_id, Fungsi::app_tapel_aktif(), $dataForm);
            } else {
                // dd($dataForm, $dataForm->siswa_id, $periksa->count());
                //insert
                $this->tagihan_store($dataForm);
            }
        }
        // dd($getSiswaId);

        return 'Data berhasil ditambahkan';
    }

    public function tagihan_edit(int $tagihan_id)
    {
        $result = tagihan::where('id', $tagihan_id)
            ->with('pembayaran')
            ->with('siswa')
            ->with('tapel')
            ->first();
        $result->pembayaran_jml = $result->pembayaran ? $result->pembayaran->count() : 0;
        $result->pembayaran_total = $result->pembayaran ? $result->pembayaran->sum('nominal_bayar') : 0;
        $result->pembayaran_persen = $result->pembayaran->sum('nominal_bayar') < $result->total_tagihan  ? $result->pembayaran ? number_format($result->pembayaran->sum('nominal_bayar') / $result->total_tagihan * 100) : 0 : 100;
        $result->pembayaran_persen_kurang = number_format(100 - $result->pembayaran_persen, 2);
        return $result;
    }

    public function tagihan_update_where_siswa(int $siswa_id, int $tapel_id, object $dataForm)
    {

        tagihan::where('siswa_id', $siswa_id)
            ->where('tapel_id', $tapel_id)
            ->update([
                'siswa_id'     =>   $dataForm->siswa_id,
                'tgl'     =>   $dataForm->tgl,
                'total_tagihan'     =>   $dataForm->total_tagihan,
                // 'tapel_id'     =>   $dataForm->tapel_id,
                'updated_at' => date("Y-m-d H:i:s")
            ]);

        return 'Data berhasil diupdate!';
    }
    public function tagihan_update(int $tagihan_id, object $dataForm)
    {

        tagihan::where('id', $tagihan_id)
            ->update([
                'siswa_id'     =>   $dataForm->siswa_id,
                'tgl'     =>   $dataForm->tgl,
                'total_tagihan'     =>   $dataForm->total_tagihan,
                // 'tapel_id'     =>   $dataForm->tapel_id,
                'updated_at' => date("Y-m-d H:i:s")
            ]);

        return 'Data berhasil diupdate!';
    }

    public function tagihan_destroy(int $tagihan_id)
    {
        // tagihan::destroy($id);

        pembayaran::where('tagihan_id', $tagihan_id)->forceDelete();
        tagihan::where('id', $tagihan_id)->forceDelete();
        return "Data berhasil dihapus";
    }


    // !---------------------------
    // !tagihan //aksi
    // !---------------------------

    public function tagihan_bayar(int $tagihan_id, object $dataForm)
    {

        $data_id = pembayaran::insertGetId(
            array(
                'tgl'     =>   $dataForm->tgl,
                'nominal_bayar'     =>   $dataForm->nominal_bayar,
                'tagihan_id'     =>   $tagihan_id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        return 'Data berhasil ditambahkan!';
    }

    public function tagihan_bayar_destroy(int $pembayaran_id)
    {
        // tagihan::destroy($id);
        pembayaran::where('id', $pembayaran_id)->forceDelete();
        return "Data berhasil dihapus";
    }


    public function tagihan_bayar_siswa(int $siswa_id, object $dataForm)
    {
        $tagihan_id = null;
        $getTagihan = tagihan::where('tapel_id', Fungsi::app_tapel_aktif())
            ->where('siswa_id', $siswa_id)->first();

        if ($getTagihan) {
            $data_id = pembayaran::insertGetId(
                array(
                    'tgl'     =>   $dataForm->tgl,
                    'nominal_bayar'     =>   $dataForm->nominal_bayar,
                    'tagihan_id'     =>   $getTagihan->id,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                )
            );
            return 'Data berhasil ditambahkan!';
        } else {
            return 'tagihan tidak ditemukan!';
        }
    }
}

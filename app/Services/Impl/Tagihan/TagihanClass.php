<?php

namespace App\Services\Impl\Tagihan;

use App\Helpers\Fungsi;
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
            // $rs->sub_aspek_jml = $rs->sub_aspek->count();
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

    public function tagihan_edit(int $tagihan_id)
    {
        return tagihan::where('id', $tagihan_id)
            ->with('pembayaran')
            ->with('siswa')
            ->with('tapel')
            ->first();
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
        tagihan::where('id', $tagihan_id)->forceDelete();
        return "Data berhasil dihapus";
    }
}

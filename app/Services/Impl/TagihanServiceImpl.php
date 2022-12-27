<?php

namespace App\Services\Impl;

use App\Services\tagihanService;
use App\Services\Impl\Tagihan\TagihanClass;

class TagihanServiceImpl implements tagihanService
{
    protected $tagihanClass;
    public function __construct()
    {
        $this->tagihanClass = new TagihanClass;
    }
    //
    public function tagihan_get()
    {
        $items = $this->tagihanClass->tagihan_get();

        return $items;
    }
    public function tagihan_store(object $dataForm)
    {
        $items = $this->tagihanClass->tagihan_store($dataForm);

        return $items;
    }
    public function tagihan_store_kelas($kelas_id, object $dataForm)
    {
        $items = $this->tagihanClass->tagihan_store_kelas($kelas_id, $dataForm);

        return $items;
    }
    public function tagihan_edit(int $tagihan_id)
    {
        $items = $this->tagihanClass->tagihan_edit($tagihan_id);

        return $items;
    }
    public function tagihan_update(int $tagihan_id, object $dataForm)
    {
        $items = $this->tagihanClass->tagihan_update($tagihan_id, $dataForm);

        return $items;
    }
    public function tagihan_destroy(int $tagihan_id)
    {
        $items = $this->tagihanClass->tagihan_destroy($tagihan_id);

        return $items;
    }

    // !---------------------------
    // !tagihan //aksi
    // !---------------------------
    public function tagihan_bayar(int $tagihan_id, object $dataForm)
    {
        $items = $this->tagihanClass->tagihan_bayar($tagihan_id, $dataForm);

        return $items;
    }
    public function tagihan_bayar_siswa(int $siswa_id, object $dataForm)
    {
        $items = $this->tagihanClass->tagihan_bayar_siswa($siswa_id, $dataForm);

        return $items;
    }
    public function tagihan_bayar_destroy(int $pembayaran_id)
    {
        $items = $this->tagihanClass->tagihan_bayar_destroy($pembayaran_id);

        return $items;
    }
}

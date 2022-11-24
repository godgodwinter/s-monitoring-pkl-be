<?php

namespace App\Http\Controllers\pembimbingsekolah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\pembimbingsekolah\kepalajurusanController;
use App\Models\penilaian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Fungsi;
use App\Models\penilaian_guru;

class guruPenilaianGuruController extends kepalajurusanController
{
    public function index(penilaian $penilaian, Request $request)
    {
        $periksa = $this->fnPeriksaData($penilaian->id);;
        if ($periksa) {
            // $this->periksaAuth();
            $items = penilaian_guru::orderBy('id', 'asc')
                ->where('penilaian_id', $penilaian->id)
                ->get();
            return response()->json([
                'success'    => true,
                'data'    => $items,
                // 'tapel_id'    => Fungsi::app_tapel_aktif(),
            ], 200);
        }
        return $this->defaultResponse;
    }

    public function store(penilaian $penilaian, Request $request)
    {
        $periksa = $this->fnPeriksaData($penilaian->id);;
        if ($periksa) {

            DB::table('penilaian_guru')->insert(
                array(
                    'nama'     =>   $request->nama,
                    'penilaian_id'     =>   $penilaian->id,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                )
            );

            return response()->json([
                'success'    => true,
                'message'    => 'Data berhasil ditambahkan!',
            ], 200);
        }
        return $this->defaultResponse;
    }

    public function edit(penilaian $penilaian, penilaian_guru $penilaian_guru)
    {
        $periksa = $this->fnPeriksaData($penilaian->id);;
        if ($periksa) {
            // $this->periksaAuth();
            $items = penilaian_guru::orderBy('id', 'asc')
                ->where('id', $penilaian_guru->id)
                ->first();
            return response()->json([
                'success'    => true,
                'data'    => $items,
                // 'tapel_id'    => Fungsi::app_tapel_aktif(),
            ], 200);
        }
        return $this->defaultResponse;
    }
    public function update(penilaian $penilaian, penilaian_guru $penilaian_guru, Request $request)
    {
        $periksa = $this->fnPeriksaData($penilaian->id);;
        if ($periksa) {
            $validator = Validator::make($request->all(), [
                'nama'   => 'required',
            ]);
            //response error validation
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            penilaian_guru::where('id', $penilaian_guru->id)
                ->update([
                    'nama'     =>   $request->nama,
                    'updated_at' => date("Y-m-d H:i:s")
                ]);

            return response()->json([
                'success'    => true,
                'message'    => 'Data berhasil di update!',
            ], 200);
        }
        return $this->defaultResponse;
    }
    public function destroy(penilaian $penilaian, penilaian_guru $penilaian_guru,)
    {
        $periksa = $this->fnPeriksaData($penilaian->id);;
        if ($periksa) {
            penilaian_guru::destroy($penilaian_guru->id);
            return response()->json([
                'success'    => true,
                'message'    => 'Data berhasil di hapus!',
            ], 200);
        }
        return $this->defaultResponse;
    }
    public function fnPeriksaData($item_id)
    {
        $result = false;
        $periksa = penilaian::where('jurusan_id', $this->jurusan->id)
            ->where('id', $item_id)->count();
        if ($periksa) {
            $result = true;
        }
        return $result;
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $items = penilaian::orderBy('id', 'asc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        // foreach ($items as $item) {
        //     $item->jurusan_nama = $item->jurusan_table ? $item->jurusan_table->nama : null;
        // }
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }

    public function store(Request $request)
    {

        DB::table('penilaian')->insert(
            array(
                'penilaian_guru'     =>   $request->penilaian_guru,
                'penilaian_pembimbinglapangan'     =>   $request->penilaian_pembimbinglapangan,
                'absensi'     =>   $request->absensi,
                'jurnal'     =>   $request->jurnal,
                'tapel_id'     =>   Fungsi::app_tapel_aktif(),
                'jurusan_id'     =>   $request->jurusan_id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil ditambahkan!',
        ], 200);
    }

    public function edit(penilaian $item)
    {
        $penilaian = penilaian::where('id', $item->id)
            ->first();
        // $penilaian->jurusan_nama = $item->jurusan_table ? $item->jurusan_table->nama : null;
        return response()->json([
            'success'    => true,
            'data'    => $penilaian,
        ], 200);
    }
    public function update(penilaian $item, Request $request)
    {

        //set validation
        $validator = Validator::make($request->all(), [
            'penilaian_guru'   => 'required',
            'penilaian_pembimbinglapangan'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        penilaian::where('id', $item->id)
            ->update([
                'penilaian_guru'     =>   $request->penilaian_guru,
                'penilaian_pembimbinglapangan'     =>   $request->penilaian_pembimbinglapangan,
                'absensi'     =>   $request->absensi,
                'jurnal'     =>   $request->jurnal,
                'jurusan_id'     =>   $request->jurusan_id,
                'updated_at' => date("Y-m-d H:i:s")
            ]);


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
        ], 200);
    }
    public function destroy(penilaian $item)
    {

        penilaian::destroy($item->id);
        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di hapus!',
        ], 200);
    }
}

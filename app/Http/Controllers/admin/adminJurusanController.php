<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\jurusan;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class adminJurusanController extends Controller
{
    public function index(Request $request)
    {
        $items = jurusan::orderBy('nama', 'asc')
            ->with('guru')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }

    public function store(Request $request)
    {

        DB::table('jurusan')->insert(
            array(
                'nama'     =>   $request->nama,
                'kepalajurusan_id'     =>   $request->kepalajurusan_id,
                'tapel_id'     =>   Fungsi::app_tapel_aktif(),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil ditambahkan!',
        ], 200);
    }

    public function edit(jurusan $item)
    {
        $jurusan = jurusan::where('id', $item->id)
            ->with('guru')->first();
        return response()->json([
            'success'    => true,
            'data'    => $jurusan,
        ], 200);
    }
    public function update(jurusan $item, Request $request)
    {

        jurusan::where('id', $item->id)
            ->update([
                'nama'     =>   $request->nama,
                'kepalajurusan_id'     =>   $request->kepalajurusan_id,
                'updated_at' => date("Y-m-d H:i:s")
            ]);


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
        ], 200);
    }
    public function destroy(jurusan $item)
    {

        jurusan::destroy($item->id);
        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di hapus!',
        ], 200);
    }
}

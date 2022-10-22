<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\jurusan;
use App\Models\kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class adminKelasController extends Controller
{
    public function index(Request $request)
    {
        $items = kelas::orderBy('tingkatan', 'asc')
            ->orderBy('jurusan', 'asc')
            ->orderBy('suffix', 'asc')
            ->where('tapel_id', Fungsi::app_tapel_aktif())
            ->with('jurusan_table')
            ->get();
        foreach ($items as $item) {
            $item->jurusan_nama = $item->jurusan_table ? $item->jurusan_table->nama : null;
        }
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }

    public function store(Request $request)
    {

        DB::table('kelas')->insert(
            array(
                'tingkatan'     =>   $request->tingkatan,
                'jurusan'     =>   $request->jurusan,
                'suffix'     =>   $request->suffix,
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

    public function edit(kelas $item)
    {
        $kelas = kelas::with('jurusan_table')
            ->where('id', $item->id)
            ->first();
        return response()->json([
            'success'    => true,
            'data'    => $kelas,
        ], 200);
    }
    public function update(kelas $item, Request $request)
    {

        //set validation
        $validator = Validator::make($request->all(), [
            'tingkatan'   => 'required',
            'jurusan'   => 'required',
            'suffix'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        kelas::where('id', $item->id)
            ->update([
                'tingkatan'     =>   $request->tingkatan,
                'jurusan'     =>   $request->jurusan,
                'suffix'     =>   $request->suffix,
                'updated_at' => date("Y-m-d H:i:s")
            ]);


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
        ], 200);
    }
    public function destroy(kelas $item)
    {

        kelas::destroy($item->id);
        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di hapus!',
        ], 200);
    }
}

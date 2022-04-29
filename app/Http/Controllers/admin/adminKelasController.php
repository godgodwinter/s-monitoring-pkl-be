<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\kelas;
use Illuminate\Http\Request;

class adminKelasController extends Controller
{
    public function index(Request $request)
    {
        $items = kelas::orderBy('tingkatan', 'asc')
            ->orderBy('jurusan', 'asc')
            ->orderBy('suffix', 'asc')
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }

    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'tingkatan'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::table('kelas')->insert(
            array(
                'tingkatan'     =>   $request->tingkatan,
                'jurusan'     =>   $request->jurusan,
                'suffix'     =>   $request->suffix,
                'tapel_id'     =>   $request->suffix,
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
        return response()->json([
            'success'    => true,
            'data'    => $item,
        ], 200);
    }
    public function update(kelas $item, Request $request)
    {

        //set validation
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        kelas::where('id', $item->id)
            ->update([
                'nama'     =>   $request->nama,
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

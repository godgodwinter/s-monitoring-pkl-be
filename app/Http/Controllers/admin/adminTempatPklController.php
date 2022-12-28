<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\tempatpkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class adminTempatPklController extends Controller
{
    public function index(Request $request)
    {
        $items = tempatpkl::where('tapel_id', Fungsi::app_tapel_aktif())->orderBy('id', 'desc')
            ->with('pembimbinglapangan')
            ->get();
        foreach ($items as $item) {
            $item->pembimbinglapangan_nama = null;
            $item->pembimbinglapangan_nama = $item->pembimbinglapangan ? $item->pembimbinglapangan->nama : null;
        }
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }

    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::table('tempatpkl')->insert(
            array(
                'nama'     =>   $request->nama,
                'alamat'     =>   $request->alamat,
                'telp'     =>   $request->telp,
                'penanggungjawab'     =>   $request->penanggungjawab,
                'kuota'     =>   $request->kuota,
                'tgl_mulai'     =>   $request->tgl_mulai,
                'tgl_selesai'     =>   $request->tgl_selesai,
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

    public function edit(tempatpkl $item)
    {
        $tempatpkl = tempatpkl::with('pembimbinglapangan',)
            ->where('id', $item->id)
            ->first();
        return response()->json([
            'success'    => true,
            'data'    => $tempatpkl,
        ], 200);
    }
    public function update(tempatpkl $item, Request $request)
    {

        //set validation
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        tempatpkl::where('id', $item->id)
            ->update([
                'nama'     =>   $request->nama,
                'alamat'     =>   $request->alamat,
                'telp'     =>   $request->telp,
                'penanggungjawab'     =>   $request->penanggungjawab,
                'kuota'     =>   $request->kuota,
                'tgl_mulai'     =>   $request->tgl_mulai,
                'tgl_selesai'     =>   $request->tgl_selesai,
                'updated_at' => date("Y-m-d H:i:s")
            ]);


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
        ], 200);
    }
    public function destroy(tempatpkl $item)
    {

        tempatpkl::where('id', $item->id)->forceDelete();
        // tempatpkl::destroy($item->id);
        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di hapus!',
        ], 200);
    }
}

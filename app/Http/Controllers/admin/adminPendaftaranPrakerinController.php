<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pendaftaranprakerin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class adminPendaftaranPrakerinController extends Controller
{
    public function index(Request $request)
    {
        $items = pendaftaranprakerin::where('tapel_id', Fungsi::app_tapel_aktif())
            ->get();
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
            'siswa_id'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::table('pendaftaranprakerin')->insert(
            array(
                'siswa_id'     =>   $request->siswa_id,
                'tgl_daftar'     =>   $request->tgl_daftar,
                'status'     =>   'Proses Daftar',
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

    public function edit(pendaftaranprakerin $item)
    {
        return response()->json([
            'success'    => true,
            'data'    => $item,
        ], 200);
    }
    public function update(pendaftaranprakerin $item, Request $request)
    {

        //set validation
        $validator = Validator::make($request->all(), [
            'siswa_id'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        pendaftaranprakerin::where('id', $item->id)
            ->update([
                'siswa_id'     =>   $request->siswa_id,
                'tgl_daftar'     =>   $request->tgl_daftar,
                'updated_at' => date("Y-m-d H:i:s")
            ]);


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
        ], 200);
    }
    public function destroy(pendaftaranprakerin $item)
    {

        pendaftaranprakerin::destroy($item->id);
        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di hapus!',
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pendaftaranprakerin;
use App\Models\pendaftaranprakerin_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class adminPendaftaranPrakerinDetailController extends Controller
{
    public function index(pendaftaranprakerin $data, Request $request)
    {
        $items = pendaftaranprakerin_detail::where('pendaftaranprakerin_id', $data->id)
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }

    public function store(pendaftaranprakerin $data, Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'tempatpkl_id'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::table('pendaftaranprakerin_detail')->insert(
            array(
                'tempatpkl_id'     =>   $request->tempatpkl_id,
                'keterangan'     =>   $request->keterangan,
                'tgl_pengajuan'     =>   $request->tgl_pengajuan,
                'pendaftaranprakerin_id'     =>   $data->id,
                'status'     =>   'Menunggu',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil ditambahkan!',
        ], 200);
    }

    public function edit(pendaftaranprakerin_detail $item)
    {
        return response()->json([
            'success'    => true,
            'data'    => $item,
        ], 200);
    }
    public function update(pendaftaranprakerin_detail $item, Request $request)
    {

        //set validation
        $validator = Validator::make($request->all(), [
            'tempatpkl_id'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        pendaftaranprakerin_detail::where('id', $item->id)
            ->update([
                'tempatpkl_id'     =>   $request->tempatpkl_id,
                'keterangan'     =>   $request->keterangan,
                'tgl_pengajuan'     =>   $request->tgl_pengajuan,
                'updated_at' => date("Y-m-d H:i:s")
            ]);


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
        ], 200);
    }
    public function destroy(pendaftaranprakerin_detail $item)
    {

        pendaftaranprakerin_detail::destroy($item->id);
        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di hapus!',
        ], 200);
    }

    public function ubahstatus(pendaftaranprakerin $data, pendaftaranprakerin_detail $item, Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'status'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        pendaftaranprakerin_detail::where('id', $item->id)
            ->update([
                'status'     =>   $request->status, //Disetujui / Ditolak / Menunggu
                'tgl_konfirmasi'     =>   date('Y-m-d'),
                'updated_at' => date("Y-m-d H:i:s")
            ]);

        if ($request->status == 'Disetujui') {
            pendaftaranprakerin::where('id', $data->id)
                ->update([
                    'status'     =>   $request->status, //Belum Daftar/ Proses Daftar / Sedang Prakerin / Telah Selesai
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Status berhasil diubah!',
        ], 200);
    }
}

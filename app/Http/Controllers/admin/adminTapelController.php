<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\tapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class adminTapelController extends Controller
{
    public function index(Request $request)
    {
        $items = tapel::orderBy('nama', 'asc')
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
            'nama'   => 'required',
            'status'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        if ($request->status == 'Aktif') {
            $getTapel = tapel::get();
            foreach ($getTapel as $d) {
                tapel::where('id', $d->id)
                    ->update([
                        'status'     =>   'Nonaktif',
                        'updated_at' => date("Y-m-d H:i:s")
                    ]);
            }
        }

        $data_id = DB::table('tapel')->insertGetId(
            array(
                'nama'     =>   $request->nama,
                'status'     =>   $request->status,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil ditambahkan!',
            'id' => $data_id,
        ], 200);
    }

    public function edit(tapel $item)
    {
        return response()->json([
            'success'    => true,
            'data'    => $item,
        ], 200);
    }
    public function update(tapel $item, Request $request)
    {

        //set validation
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
            'status'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if ($request->status == 'Aktif') {
            $getTapel = tapel::get();
            foreach ($getTapel as $d) {
                tapel::where('id', $d->id)
                    ->update([
                        'status'     =>   'Nonaktif',
                        'updated_at' => date("Y-m-d H:i:s")
                    ]);
            }
        }

        tapel::where('id', $item->id)
            ->update([
                'nama'     =>   $request->nama,
                'status'     =>   $request->status,
                'updated_at' => date("Y-m-d H:i:s")
            ]);


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
        ], 200);
    }
    public function destroy(tapel $item)
    {

        tapel::destroy($item->id);
        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di hapus!',
        ], 200);
    }
}

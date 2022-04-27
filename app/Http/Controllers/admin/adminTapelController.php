<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\tapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class adminTapelController extends Controller
{
    public function index(Request $request)
    {
        $items=tapel::orderBy('nama','asc')
        ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }

    public function store(Request $request)
    {
            $request->validate([
                'nama'=>'required|unique:tapel,nama',
            ],
            [
                'nama.required'=>'Nama harus diisi',
            ]);
            DB::table('tapel')->insert(
                array(
                       'nama'     =>   $request->nama,
                       'created_at'=>date("Y-m-d H:i:s"),
                       'updated_at'=>date("Y-m-d H:i:s")
                ));

                return response()->json([
                    'success'    => true,
                    'message'    => 'Data berhasil ditambahkan!',
                ], 200);
    }

    public function edit(tapel $item)
    {
        return response()->json([
            'success'    => true,
            'data'    => $item,
        ], 200);
    }
    public function update(tapel $item,Request $request)
    {

        $request->validate([
            'nama'=>'required',
        ],
        [
            'nama.required'=>'nama harus diisi',
        ]);
            tapel::where('id',$item->id)
            ->update([
                'nama'     =>   $request->nama,
               'updated_at'=>date("Y-m-d H:i:s")
            ]);


            return response()->json([
                'success'    => true,
                'message'    => 'Data berhasil di update!',
            ], 200);
    }
    public function destroy(tapel $item){

        tapel::destroy($item->id);
        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di hapus!',
        ], 200);

    }
}

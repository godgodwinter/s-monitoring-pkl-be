<?php

namespace App\Http\Controllers\admin;

use App\Helpers\Fungsi;
use App\Http\Controllers\Controller;
use App\Models\pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class adminPengumumanController extends Controller
{
    public function index(Request $request)
    {
        $items = pengumuman::orderBy('created_at', 'desc')
            ->with('label')
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'content'   => 'required',
            // 'desc'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $slug = Str::slug($request->title, '-');
        DB::table('pengumuman')->insert(
            array(
                'title'     =>   $request->title,
                'content'     =>   $request->content,
                'desc'     =>   $request->desc,
                'slug' => $slug . "-" . date('YmdHis'),
                'user_id' => $this->guard()->user()->id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil ditambahkan!',
        ], 200);
    }

    public function edit(pengumuman $item)
    {
        $pengumuman = pengumuman::where('id', $item->id)
            ->with('label')->first();
        return response()->json([
            'success'    => true,
            'data'    => $pengumuman,
        ], 200);
    }
    public function update(pengumuman $item, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'content'   => 'required',
            // 'desc'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        pengumuman::where('id', $item->id)
            ->update([
                'title'     =>   $request->title,
                'content'     =>   $request->content,
                'desc'     =>   $request->desc,
                'updated_at' => date("Y-m-d H:i:s")
            ]);


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
        ], 200);
    }
    public function destroy(pengumuman $item)
    {

        // pengumuman::destroy($item->id);
        pengumuman::where('id', $item->id)->forceDelete();
        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di hapus!',
        ], 200);
    }

    public function guest_index(Request $request)
    {
        $items = pengumuman::orderBy('created_at', 'desc')
            ->with('label')
            ->get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }

    public function guard()
    {
        return Auth::guard();
    }
}

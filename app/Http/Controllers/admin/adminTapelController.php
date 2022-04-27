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
        $items=tapel::where('prefix','label')
        ->orderBy('nama','asc')
        ->get();
        // return view('pages.admin.label.index',compact('items','request','pages'));
    }

    public function store(Request $request)
    {
            $request->validate([
                'nama'=>'required',
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
    // return redirect()->route('admin.label')->with('status','Data berhasil tambahkan!')->with('tipe','success')->with('icon','fas fa-feather');
    }

    public function edit(tapel $item)
    {
        // return view('pages.admin.label.edit',compact('pages','item'));
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



    // return redirect()->route('admin.label')->with('status','Data berhasil diubah!')->with('tipe','success')->with('icon','fas fa-feather');
    }
    public function destroy(tapel $item){

        tapel::destroy($item->id);
        // return redirect()->route('admin.label')->with('status','Data berhasil dihapus!')->with('tipe','warning')->with('icon','fas fa-feather');

    }
}

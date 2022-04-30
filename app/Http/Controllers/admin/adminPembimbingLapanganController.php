<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\pembimbinglapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Faker\Factory as Faker;

class adminPembimbingLapanganController extends Controller
{
    public function index(Request $request)
    {
        $items = pembimbinglapangan::orderBy('nama', 'asc')
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
            // 'email'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::table('pembimbinglapangan')->insert(
            array(
                'nama'     =>   $request->nama,
                // 'email'     =>   $request->email,
                // 'username'     =>   $request->username,
                'nomeridentitas'     =>   $request->nomeridentitas,
                'password'     =>   Hash::make(123),
                'agama'     =>   $request->agama,
                'tempatlahir'     =>   $request->tempatlahir,
                'tgllahir'     =>   $request->tgllahir,
                'alamat'     =>   $request->alamat,
                'jk'     =>   $request->jk,
                'telp'     =>   $request->telp,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            )
        );

        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil ditambahkan!',
        ], 200);
    }

    public function edit(pembimbinglapangan $item)
    {
        return response()->json([
            'success'    => true,
            'data'    => $item,
        ], 200);
    }
    public function update(pembimbinglapangan $item, Request $request)
    {

        //set validation
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
            // 'email'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        pembimbinglapangan::where('id', $item->id)
            ->update([
                'nama'     =>   $request->nama,
                // 'email'     =>   $request->email,
                // 'username'     =>   $request->username,
                'nomeridentitas'     =>   $request->nomeridentitas,
                // 'password'     =>   $request->password,
                'agama'     =>   $request->agama,
                'tempatlahir'     =>   $request->tempatlahir,
                'tgllahir'     =>   $request->tgllahir,
                'alamat'     =>   $request->alamat,
                'jk'     =>   $request->jk,
                'telp'     =>   $request->telp,
                'updated_at' => date("Y-m-d H:i:s")
            ]);


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
        ], 200);
    }
    public function destroy(pembimbinglapangan $item)
    {

        pembimbinglapangan::destroy($item->id);
        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di hapus!',
        ], 200);
    }
    public function generatepassword(pembimbinglapangan $item, Request $request)
    {
        $faker = Faker::create('id_ID');


        // $pass = $faker->password(6, 6, $requireNumeric = true, $requireLowercase = true, $requireUppercase = true, $requireSpecial = false, $customCharacters = '');
        $pass = $faker->regexify('[A-Za-z0-9]{6}');
        pembimbinglapangan::where('id', $item->id)
            ->update([
                'password' => Hash::make($pass),
                'updated_at' => date("Y-m-d H:i:s")
            ]);


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
            'password' => $pass,
        ], 200);
    }

    public function generatepasswordall(Request $request)
    {
        $faker = Faker::create('id_ID');


        $pass = 123;
        $getpembimbinglapangan = pembimbinglapangan::get();
        foreach ($getpembimbinglapangan as $item) {
            pembimbinglapangan::where('id', $item->id)
                ->update([
                    'password' => Hash::make($pass),
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
        }


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di update!',
            'password' => $pass,
        ], 200);
    }
}

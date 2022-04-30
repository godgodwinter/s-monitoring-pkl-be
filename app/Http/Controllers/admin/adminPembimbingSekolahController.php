<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\pembimbingsekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Faker\Factory as Faker;

class adminPembimbingSekolahController extends Controller
{
    public function index(Request $request)
    {
        $items = pembimbingsekolah::orderBy('nama', 'asc')
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

        DB::table('pembimbingsekolah')->insert(
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

    public function edit(pembimbingsekolah $item)
    {
        return response()->json([
            'success'    => true,
            'data'    => $item,
        ], 200);
    }
    public function update(pembimbingsekolah $item, Request $request)
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
        pembimbingsekolah::where('id', $item->id)
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
    public function destroy(pembimbingsekolah $item)
    {

        pembimbingsekolah::destroy($item->id);
        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil di hapus!',
        ], 200);
    }
    public function generatepassword(pembimbingsekolah $item, Request $request)
    {
        $faker = Faker::create('id_ID');


        // $pass = $faker->password(6, 6, $requireNumeric = true, $requireLowercase = true, $requireUppercase = true, $requireSpecial = false, $customCharacters = '');
        $pass = $faker->regexify('[A-Za-z0-9]{6}');
        pembimbingsekolah::where('id', $item->id)
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
        $getpembimbingsekolah = pembimbingsekolah::get();
        foreach ($getpembimbingsekolah as $item) {
            pembimbingsekolah::where('id', $item->id)
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

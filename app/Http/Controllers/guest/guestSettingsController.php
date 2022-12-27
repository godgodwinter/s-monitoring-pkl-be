<?php

namespace App\Http\Controllers\guest;

use App\Http\Controllers\Controller;
use App\Models\settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class guestSettingsController extends Controller
{
    public function index(Request $request)
    {
        $items = settings::orderBy('created_at', 'desc')
            ->first();
        if ($items) {
            $items->jam = $items->bataswaktu ? Carbon::parse($items->bataswaktu)->format('H') : "14";
            $items->menit = $items->bataswaktu ? Carbon::parse($items->bataswaktu)->format('i') : "14";
            $items->detik = $items->bataswaktu ? Carbon::parse($items->bataswaktu)->format('s') : "14";
        }
        return response()->json([
            'success'    => true,
            'data'    => $items,
            // 'tapel_id'    => Fungsi::app_tapel_aktif(),
        ], 200);
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bataswaktu'   => 'required',
            // 'desc'   => 'required',
        ]);
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // DB::table('settings')->insert(
        //     array(
        //         'bataswaktu'     =>   $request->bataswaktu,
        //         'created_at' => date("Y-m-d H:i:s"),
        //         'updated_at' => date("Y-m-d H:i:s")
        //     )
        // );

        settings::where('id', 1)
            ->update([
                'bataswaktu'     =>   date("Y-m-d") . " " . $request->bataswaktu,
                'min_pembayaran' => $request->min_pembayaran,
                'updated_at' => date("Y-m-d H:i:s")
            ]);


        return response()->json([
            'success'    => true,
            'message'    => 'Data berhasil diubah!',
        ], 200);
    }
}

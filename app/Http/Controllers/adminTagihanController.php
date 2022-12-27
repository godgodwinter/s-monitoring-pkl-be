<?php

namespace App\Http\Controllers;

use App\Helpers\Fungsi;
use App\Models\tagihan;
use App\Services\tagihanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class adminTagihanController extends Controller
{
    public function __construct(tagihanService $TagihanService)
    {
        $this->tagihanService = $TagihanService;
    }


    public function index(Request $request)
    {
        $items = $this->tagihanService->tagihan_get();
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total_tagihan'   => 'required',
            'siswa_id'   => 'required',
        ]);

        $dataForm = (object)[];
        $dataForm->total_tagihan = $request->total_tagihan;
        $dataForm->tgl = $request->tgl;
        $dataForm->siswa_id = $request->siswa_id;
        $dataForm->tapel_id = Fungsi::app_tapel_aktif();
        $items = $this->tagihanService->tagihan_store($dataForm);
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }

    public function edit(Request $request, tagihan $tagihan)
    {
        $items = $this->SkolastikService->tagihan_edit($tagihan->id);
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }

    public function update(Request $request, tagihan $tagihan)
    {

        $validator = Validator::make($request->all(), [
            'total_tagihan'   => 'required',
            'siswa_id'   => 'required',
        ]);

        $dataForm = (object)[];
        $dataForm->total_tagihan = $request->total_tagihan;
        $dataForm->tgl = $request->tgl;
        $dataForm->siswa_id = $request->siswa_id;
        $items = $this->SkolastikService->tagihan_update($tagihan->id, $dataForm);
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }

    public function destroy(Request $request, tagihan $tagihan)
    {
        $items = $this->tagihanService->tagihan_destroy($tagihan->id);
        return response()->json([
            'success'    => true,
            'data'    => $items,
        ], 200);
    }
}

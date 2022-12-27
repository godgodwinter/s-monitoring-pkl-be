<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Imports\importSiswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class adminProsesController extends Controller
{
    public function clearTemp()
    {
        $path = public_path('/file_temp/');
        $files = glob($path . '/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                unlink($file); // delete file
        }

        return response()->json([
            'success'    => true,
            'data'    => 'Data Temporary sudah di hapus',
        ], 200);
    }


    public function importSiswa(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        $nama_file = rand() . $file->getClientOriginalName();

        $file->move('file_temp', $nama_file);

        Excel::import(new importSiswa, public_path('/file_temp/' . $nama_file));

        return response()->json([
            'success'    => true,
            'data'    => 'Data berhasil diImport',
        ], 200);
    }
}

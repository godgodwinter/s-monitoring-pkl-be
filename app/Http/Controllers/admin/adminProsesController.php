<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Imports\importGuru;
use App\Imports\importPembimbinglapangan;
use App\Imports\importSiswa;
use App\Imports\importTempatpkl;
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


    public function importTempatpkl(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        $nama_file = rand() . $file->getClientOriginalName();

        $file->move('file_temp', $nama_file);

        Excel::import(new importTempatpkl, public_path('/file_temp/' . $nama_file));

        return response()->json([
            'success'    => true,
            'data'    => 'Data berhasil diImport',
        ], 200);
    }


    public function importGuru(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        $nama_file = rand() . $file->getClientOriginalName();

        $file->move('file_temp', $nama_file);

        Excel::import(new importGuru, public_path('/file_temp/' . $nama_file));

        return response()->json([
            'success'    => true,
            'data'    => 'Data berhasil diImport',
        ], 200);
    }
    public function importPembimbinglapangan(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        $nama_file = rand() . $file->getClientOriginalName();

        $file->move('file_temp', $nama_file);

        Excel::import(new importPembimbinglapangan, public_path('/file_temp/' . $nama_file));

        return response()->json([
            'success'    => true,
            'data'    => 'Data berhasil diImport',
        ], 200);
    }
}

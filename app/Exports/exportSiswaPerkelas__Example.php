<?php

namespace App\Exports;

use App\Http\Resources\bukudetailresource;
use App\Http\Resources\bukurakresource;
use App\Http\Resources\bukuresource;
use App\Http\Resources\kelasresource;
use App\Http\Resources\peralatanresource;
use App\Http\Resources\sekolahresource;
use App\Http\Resources\siswaresource;
use App\Http\Resources\tapelresource;
use App\Http\Resources\usersresource;
use App\Models\siswa;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;

class exportSiswaPerkelas implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */

    // public function styles(Worksheet $sheet)
    // {
    //     return [
    //         // Style the first row as bold text.
    //         1    => ['font' => ['bold' => true]],


    //     ];
    // }

    // protected $kelas;

    // function __construct($kelas)
    // {
    //     $this->kelas = $kelas;
    // }
    public function headings(): array
    {
        return [
            'id',
            'NIS',
            'nama',
            'Nilai Pembimbing Sekolah',
            'Nilai Pembimbing Lapangan',
            'Nilai Absensi',
            'Nilai Jurnal',
            'Nilai Akhir',
        ];
    public function collection()
    {
        $getData = DB::table('siswa')
            // ->where('kelas_id', $this->kelas)
            ->whereNull('deleted_at')
            ->get();
        $datas = [];

        $i = 0;
        foreach ($getData as $data) {
            $tempSiswa = (object)[];
            $i++;
            $tempSiswa->id = $i;
            $tempSiswa->nomeridentitas = $data->nomeridentitas;
            $tempSiswa->nama = $data->nama;
            $tempSiswa->username = $data->username;
            $tempSiswa->passworddefault = $data->passworddefault;
        }

        return $datas;
    }
}

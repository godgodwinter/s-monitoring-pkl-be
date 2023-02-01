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

class exportNilaiSiswaPerkelas implements FromCollection, WithHeadings, ShouldAutoSize
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

    protected $getData;

    function __construct($data)
    {
        $this->getData = $data;
    }
    public function headings(): array
    {
        return [
            'no',
            'NIS',
            'nama',
            'kelas',
            'Penilaian Guru',
            'Penilaian DUDI',
            'Nilai Absensi',
            'Nilai Jurnal',
            'Nilai Akhir',
        ];
    }
    public function collection()
    {
        // $getData = DB::table('siswa')
        //     // ->where('kelas_id', $this->kelas)
        //     ->whereNull('deleted_at')
        //     ->get();
        $datas = collect([]);
        // dd($this->getData);
        $i = 0;
        foreach ($this->getData as $data) {
            $tempSiswa = (object)[];
            $i++;
            $tempSiswa->id = $i;
            $tempSiswa->nomeridentitas = $data->nomeridentitas;
            $tempSiswa->nama = $data->nama;
            $tempSiswa->nama_kelas = $data->kelasdetail->kelas ? $data->kelasdetail->kelas->tingkatan . " " . $data->kelasdetail->kelas->jurusan_table->nama . " " . $data->kelasdetail->kelas->suffix : "-";
            $tempSiswa->nilai_pembimbingsekolah = $data->nilai_pembimbingsekolah;
            $tempSiswa->nilai_pembimbinglapangan = $data->nilai_pembimbinglapangan;
            $tempSiswa->nilai_absensi = $data->nilai_absensi;
            $tempSiswa->nilai_jurnal = $data->nilai_jurnal;
            $tempSiswa->nilai_akhir = $data->nilai_akhir;
            $datas[] = $tempSiswa;
        }

        return $datas;
    }
}

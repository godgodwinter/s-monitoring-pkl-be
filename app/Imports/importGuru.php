<?php

namespace App\Imports;

use App\Helpers\Fungsi;
use App\Models\apiprobk;
use App\Models\buletinpsikologi;
use App\Models\kelasdetail;
use App\Models\klasifikasiakademis;
use App\Models\pembimbingsekolah;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class importGuru implements ToCollection, WithCalculatedFormulas
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $id;

    public function collection(Collection $rows, $calculateFormulas = false)
    {
        $jmlTerUpload = 0;
        $jmlDiSkip = 0;
        // $rows->calculate(false);
        ini_set('max_execution_time', 3000);
        $no = 0;
        $tapel_id = Fungsi::app_tapel_aktif();
        foreach ($rows as $row) {
            if ($no > 0) {
                if (($row[1] != null) and ($row[1] != '')) {
                    $periksa = pembimbingsekolah::where('nama', $row[1])->where('nomeridentitas', $row[4]);
                    if ($periksa->count() > 0) {
                        $periksa->update([
                            'nama' => $row[1],
                            'email' => $row[2],
                            'username' => $row[3],
                            'nomeridentitas' => $row[4],
                            'agama' => $row[5],
                            'tempatlahir' => $row[6],
                            'tgllahir' => $row[7],
                            'alamat' => $row[8],
                            'jk' => $row[9],
                            'telp' => $row[10],
                            'updated_at' => date("Y-m-d H:i:s")
                        ]);
                        $jmlDiSkip++;
                    } else {
                        $pembimbingsekolah = pembimbingsekolah::insertGetId(
                            array(
                                'nama' => $row[1],
                                'email' => $row[2],
                                'username' => $row[3],
                                'nomeridentitas' => $row[4],
                                'agama' => $row[5],
                                'tempatlahir' => $row[6],
                                'tgllahir' => $row[7],
                                'alamat' => $row[8],
                                'jk' => $row[9],
                                'telp' => $row[10],
                                'password' => Hash::make(123),
                                'deleted_at' => null,
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s")
                            )
                        );
                        $jmlTerUpload++;
                    }
                }
            }
            $no++;
        }
    }
}

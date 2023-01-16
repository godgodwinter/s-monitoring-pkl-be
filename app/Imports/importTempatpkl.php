<?php

namespace App\Imports;

use App\Helpers\Fungsi;
use App\Models\apiprobk;
use App\Models\buletinpsikologi;
use App\Models\kelasdetail;
use App\Models\klasifikasiakademis;
use App\Models\Siswa;
use App\Models\tempatpkl;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class importTempatpkl implements ToCollection, WithCalculatedFormulas
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
                    $periksa = tempatpkl::where('nama', $row[1])->where('tapel_id', $tapel_id);
                    if ($periksa->count() > 0) {
                        $periksa->update([
                            'nama' => $row[1],
                            'alamat' => $row[2],
                            'telp' => $row[3],
                            'penanggungjawab' => $row[4],
                            'nama_pimpinan' => $row[5],
                            'kuota' => $row[6],
                            'tgl_mulai' => $row[7],
                            'tgl_selesai' => $row[8],
                            'kuota' => $row[6],
                            'desc' => $row[9],
                            'updated_at' => date("Y-m-d H:i:s")
                        ]);
                        $jmlDiSkip++;
                    } else {
                        $tempatpkl = tempatpkl::insertGetId(
                            array(
                                'nama' => $row[1],
                                'alamat' => $row[2],
                                'telp' => $row[3],
                                'penanggungjawab' => $row[4],
                                'nama_pimpinan' => $row[5],
                                'kuota' => $row[6],
                                'tgl_mulai' => $row[7],
                                'tgl_selesai' => $row[8],
                                'desc' => $row[9],
                                'tapel_id' => $tapel_id,
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

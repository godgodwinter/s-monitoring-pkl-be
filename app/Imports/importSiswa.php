<?php

namespace App\Imports;

use App\Helpers\Fungsi;
use App\Models\apiprobk;
use App\Models\buletinpsikologi;
use App\Models\kelasdetail;
use App\Models\klasifikasiakademis;
use App\Models\pembayaran;
use App\Models\Siswa;
use App\Models\tagihan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class importSiswa implements ToCollection, WithCalculatedFormulas
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $id;


    public function fnInsertTagihan(int $siswa_id, int $total_tagihan, int $pembayaran_pertama)
    {
        # code...
        $tapel_id = Fungsi::app_tapel_aktif();

        $periksa = tagihan::where('siswa_id', $siswa_id)->where('tapel_id', $tapel_id);
        if ($periksa->count() > 0) {
            //update
            $periksa->update([
                'siswa_id' => $siswa_id,
                'total_tagihan' => $total_tagihan,
                'updated_at' => date("Y-m-d H:i:s")
            ]);
            $getTagihan = $periksa->first();
            $tagihan_id = $getTagihan->id;
        } else {
            $tagihan_id = tagihan::insertGetId(
                array(
                    'siswa_id' => $siswa_id,
                    'total_tagihan' => $total_tagihan,
                    'tgl' => date("Y-m-d H:i:s"),
                    'tapel_id' => $tapel_id,
                    'deleted_at' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                )
            );
        }


        $periksaPembayaran = pembayaran::where('tagihan_id', $tagihan_id);
        if ($periksaPembayaran->count() > 0) {
            //update
            $periksaPembayaran->update([
                'nominal_bayar' => $pembayaran_pertama,
                'tgl' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        } else {
            $pembayaran_id = pembayaran::insertGetId(
                array(
                    'tagihan_id' => $tagihan_id,
                    'nominal_bayar' => $pembayaran_pertama,
                    'tgl' => date("Y-m-d H:i:s"),
                    'deleted_at' => null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                )
            );
        }
    }
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
                    $kelas_id = $row[11];
                    $periksa = Siswa::where('nama', $row[1])->where('nomeridentitas', $row[2]);
                    if ($periksa->count() > 0) {
                        $periksa->update([
                            'nama' => $row[1],
                            'nomeridentitas' => $row[2],
                            'username' => $row[3],
                            'email' => $row[4],
                            'agama' => $row[5],
                            'tempatlahir' => $row[6],
                            'tgllahir' => $row[7],
                            'alamat' => $row[8],
                            'jk' => $row[9],
                            'telp' => $row[10],
                            'updated_at' => date("Y-m-d H:i:s")
                        ]);
                        if ($row[12]) {
                            $siswa = $periksa->first();
                            $this->fnInsertTagihan($siswa->id, $row[12], $row[13]);
                        }
                        $jmlDiSkip++;
                    } else {
                        $siswa = Siswa::insertGetId(
                            array(
                                'nama' => $row[1],
                                'nomeridentitas' => $row[2],
                                'username' => $row[3],
                                'email' => $row[4],
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
                        $kelasdetail = kelasdetail::insertGetId(
                            array(
                                'kelas_id' => $kelas_id,
                                // 'tapel_id' => $tapel_id,
                                'siswa_id' => $siswa,
                                'deleted_at' => null,
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s")
                            )
                        );

                        if ($row[12]) {
                            $this->fnInsertTagihan($siswa, $row[12], $row[13]);
                        }
                        $jmlTerUpload++;
                    }
                }
            }
            $no++;
        }
    }
}

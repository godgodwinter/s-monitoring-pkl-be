<?php

namespace Database\Seeders;

use App\Helpers\Fungsi;
use App\Models\kelas;
use App\Models\tapel;
use App\Models\tempatpkl;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class oneseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->truncate();
        // //ADMIN SEEDER
        // DB::table('users')->insert([
        //     'name' => 'Admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => Hash::make('admin'),
        //     // 'password' => '$2y$10$oOhE/tcF8MC9crGCw/Zv5.zFMGu0JLm591undChCaHJM6YrnGjgCu',
        //     'tipeuser' => 'admin',
        //     'nomerinduk' => '123',
        //     'username' => 'admin',
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        //  ]);


        DB::table('settings')->truncate();
        //settings SEEDER
        DB::table('settings')->insert([
            'app_nama' => 'Nama App',
            'app_namapendek' => 'Bae',
            'paginationjml' => '10',
            'login_siswa' => 'Aktif',
            'login_pembimbingsekolah' => 'Aktif',
            'login_pembimbinglapangan' => 'Aktif',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        // Seeder tapel
        tapel::insert([
            'nama' => '2021/2022',
            'status' => 'Aktif',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        // seeder kelas
        kelas::insert([
            'tingkatan' => 'X',
            'jurusan' => 'TKJ',
            'suffix' => '1',
            'tapel_id' => Fungsi::app_tapel_aktif(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        kelas::insert([
            'tingkatan' => 'X',
            'jurusan' => 'TSM',
            'suffix' => '1',
            'tapel_id' => Fungsi::app_tapel_aktif(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        // seeder Tempat PKL
        $dataTempatPkl = [
            [
                'nama' => 'PT Pertamina',
                'alamat' => 'Jl. Pertamina',
                'telp' => '0812341234',
                'penanggungjawab' => 'Bos Pertamina',
                'nama_pimpinan' => 'Bos Pertamina',
                'kuota' => '10',
                'tapel_id' => Fungsi::app_tapel_aktif(),
                'status' => 'Tersedia',
                'tgl_mulai' => '2020-01-01',
                'tgl_selesai' => '2020-12-31',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nama' => 'PT Bank Mandiri (Persero) Tbk',
                'alamat' => 'Jl. Kebon Kacang',
                'telp' => '08123456789',
                'penanggungjawab' => 'Pengurus',
                'nama_pimpinan' => 'Pimpinan Bank Mandiri (Persero) Tbk',
                'kuota' => '10',
                'tapel_id' => '1',
                'status' => 'Tersedia',
                'tgl_mulai' => '2020-01-01',
                'tgl_selesai' => '2020-11-01',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nama' => ' PT Bank Rakyat Indonesia Tbk',
                'alamat' => 'Jl. Rakyat Indonesia 2',
                'telp' => '0812341234',
                'penanggungjawab' => 'Bos BRI',
                'nama_pimpinan' => 'Bos BRI',
                'kuota' => '10',
                'tapel_id' => Fungsi::app_tapel_aktif(),
                'status' => 'Tersedia',
                'tgl_mulai' => '2020-01-01',
                'tgl_selesai' => '2020-12-31',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];
        foreach ($dataTempatPkl as $data) {
            tempatpkl::insert($data);
        };

        // admin
        User::insert([
            'name' => 'Admin Paijo',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'nomeridentitas' => '1',
            'password' => Hash::make('admin'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}

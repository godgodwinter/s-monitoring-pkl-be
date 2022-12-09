<?php

namespace Database\Seeders;

use App\Helpers\Fungsi;
use App\Models\kelas;
use App\Models\kelasdetail;
use App\Models\pembimbinglapangan;
use App\Models\pembimbingsekolah;
use App\Models\pendaftaranprakerin;
use App\Models\Siswa;
use App\Models\tapel;
use App\Models\tempatpkl;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class oneseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
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

        DB::table('tapel')->truncate();
        // Seeder tapel
        tapel::insert([
            'nama' => '2021/2022',
            'status' => 'Aktif',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        // DB::table('kelas')->truncate();
        // // seeder kelas
        // kelas::insert([
        //     'tingkatan' => 'X',
        //     'jurusan' => 'TKJ',
        //     'suffix' => '1',
        //     'tapel_id' => Fungsi::app_tapel_aktif(),
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);
        // kelas::insert([
        //     'tingkatan' => 'X',
        //     'jurusan' => 'TSM',
        //     'suffix' => '1',
        //     'tapel_id' => Fungsi::app_tapel_aktif(),
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);

        // DB::table('tempatpkl')->truncate();
        // // seeder Tempat PKL
        // $dataTempatPkl = [
        //     [
        //         'nama' => 'PT Pertamina',
        //         'alamat' =>  $faker->address,
        //         'telp' => $faker->phoneNumber,
        //         'penanggungjawab' => $faker->name,
        //         'kuota' =>  $faker->randomElement(['1', '2', '3', '4', '5']),
        //         'tapel_id' => Fungsi::app_tapel_aktif(),
        //         'status' => 'Tersedia',
        //         'tgl_mulai' => '2020-01-01',
        //         'tgl_selesai' => '2020-12-31',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'nama' => 'PT Bank Mandiri (Persero) Tbk',
        //         'alamat' =>  $faker->address,
        //         'telp' => $faker->phoneNumber,
        //         'penanggungjawab' => $faker->name,
        //         'kuota' =>  $faker->randomElement(['1', '2', '3', '4', '5']),
        //         'tapel_id' => '1',
        //         'status' => 'Tersedia',
        //         'tgl_mulai' => '2020-01-01',
        //         'tgl_selesai' => '2020-11-01',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'nama' => ' PT Bank Rakyat Indonesia Tbk',
        //         'alamat' =>  $faker->address,
        //         'telp' => $faker->phoneNumber,
        //         'penanggungjawab' => $faker->name,
        //         'kuota' =>  $faker->randomElement(['1', '2', '3', '4', '5']),
        //         'tapel_id' => Fungsi::app_tapel_aktif(),
        //         'status' => 'Tersedia',
        //         'tgl_mulai' => '2020-01-01',
        //         'tgl_selesai' => '2020-12-31',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'nama' => ' PT  Bank Central Asia Tbk',
        //         'alamat' =>  $faker->address,
        //         'telp' => $faker->phoneNumber,
        //         'penanggungjawab' => $faker->name,
        //         'kuota' =>  $faker->randomElement(['1', '2', '3', '4', '5']),
        //         'tapel_id' => Fungsi::app_tapel_aktif(),
        //         'status' => 'Tersedia',
        //         'tgl_mulai' => '2020-01-01',
        //         'tgl_selesai' => '2020-12-31',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'nama' => ' PT   Indomarco Prismatama',
        //         'alamat' =>  $faker->address,
        //         'telp' => $faker->phoneNumber,
        //         'penanggungjawab' => $faker->name,
        //         'kuota' =>  $faker->randomElement(['1', '2', '3', '4', '5']),
        //         'tapel_id' => Fungsi::app_tapel_aktif(),
        //         'status' => 'Tersedia',
        //         'tgl_mulai' => '2020-01-01',
        //         'tgl_selesai' => '2020-12-31',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'nama' => $faker->company,
        //         'alamat' =>  $faker->address,
        //         'telp' => $faker->phoneNumber,
        //         'penanggungjawab' => $faker->name,
        //         'kuota' =>  $faker->randomElement(['1', '2', '3', '4', '5']),
        //         'tapel_id' => Fungsi::app_tapel_aktif(),
        //         'status' => 'Tersedia',
        //         'tgl_mulai' => '2020-01-01',
        //         'tgl_selesai' => '2020-12-31',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'nama' => $faker->company,
        //         'alamat' =>  $faker->address,
        //         'telp' => $faker->phoneNumber,
        //         'penanggungjawab' => $faker->name,
        //         'kuota' =>  $faker->randomElement(['1', '2', '3', '4', '5']),
        //         'tapel_id' => Fungsi::app_tapel_aktif(),
        //         'status' => 'Tersedia',
        //         'tgl_mulai' => '2020-01-01',
        //         'tgl_selesai' => '2020-12-31',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'nama' => $faker->company,
        //         'alamat' =>  $faker->address,
        //         'telp' => $faker->phoneNumber,
        //         'penanggungjawab' => $faker->name,
        //         'kuota' =>  $faker->randomElement(['1', '2', '3', '4', '5']),
        //         'tapel_id' => Fungsi::app_tapel_aktif(),
        //         'status' => 'Tersedia',
        //         'tgl_mulai' => '2020-01-01',
        //         'tgl_selesai' => '2020-12-31',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'nama' => $faker->company,
        //         'alamat' =>  $faker->address,
        //         'telp' => $faker->phoneNumber,
        //         'penanggungjawab' => $faker->name,
        //         'kuota' =>  $faker->randomElement(['1', '2', '3', '4', '5']),
        //         'tapel_id' => Fungsi::app_tapel_aktif(),
        //         'status' => 'Tersedia',
        //         'tgl_mulai' => '2020-01-01',
        //         'tgl_selesai' => '2020-12-31',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'nama' => $faker->company,
        //         'alamat' =>  $faker->address,
        //         'telp' => $faker->phoneNumber,
        //         'penanggungjawab' => $faker->name,
        //         'kuota' =>  $faker->randomElement(['1', '2', '3', '4', '5']),
        //         'tapel_id' => Fungsi::app_tapel_aktif(),
        //         'status' => 'Tersedia',
        //         'tgl_mulai' => '2020-01-01',
        //         'tgl_selesai' => '2020-12-31',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'nama' => $faker->company,
        //         'alamat' =>  $faker->address,
        //         'telp' => $faker->phoneNumber,
        //         'penanggungjawab' => $faker->name,
        //         'kuota' =>  $faker->randomElement(['1', '2', '3', '4', '5']),
        //         'tapel_id' => Fungsi::app_tapel_aktif(),
        //         'status' => 'Tersedia',
        //         'tgl_mulai' => '2020-01-01',
        //         'tgl_selesai' => '2020-12-31',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'nama' => $faker->company,
        //         'alamat' =>  $faker->address,
        //         'telp' => $faker->phoneNumber,
        //         'penanggungjawab' => $faker->name,
        //         'kuota' =>  $faker->randomElement(['1', '2', '3', '4', '5']),
        //         'tapel_id' => Fungsi::app_tapel_aktif(),
        //         'status' => 'Tersedia',
        //         'tgl_mulai' => '2020-01-01',
        //         'tgl_selesai' => '2020-12-31',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'nama' => $faker->company,
        //         'alamat' =>  $faker->address,
        //         'telp' => $faker->phoneNumber,
        //         'penanggungjawab' => $faker->name,
        //         'kuota' =>  $faker->randomElement(['1', '2', '3', '4', '5']),
        //         'tapel_id' => Fungsi::app_tapel_aktif(),
        //         'status' => 'Tersedia',
        //         'tgl_mulai' => '2020-01-01',
        //         'tgl_selesai' => '2020-12-31',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'nama' => $faker->company,
        //         'alamat' =>  $faker->address,
        //         'telp' => $faker->phoneNumber,
        //         'penanggungjawab' => $faker->name,
        //         'kuota' =>  $faker->randomElement(['1', '2', '3', '4', '5']),
        //         'tapel_id' => Fungsi::app_tapel_aktif(),
        //         'status' => 'Tersedia',
        //         'tgl_mulai' => '2020-01-01',
        //         'tgl_selesai' => '2020-12-31',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        // ];
        // foreach ($dataTempatPkl as $data) {
        //     tempatpkl::insert($data);
        // };

        DB::table('users')->truncate();
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
        // $jmlSeeder = 10;
        // // seeder pembimbinglapangan
        // DB::table('pembimbinglapangan')->truncate();

        // pembimbinglapangan::insert([
        //     'nama' => $faker->unique()->name,
        //     'email' => $faker->unique()->email,
        //     'username' => 'pembimbinglapangan',
        //     'password' => Hash::make('123'),
        //     'nomeridentitas' => $faker->unique()->numberBetween($min = 1000, $max = 9000),
        //     'agama' => $faker->randomElement(['Islam', 'Kristen', 'Hindu', 'Budha', 'Konghucu']),
        //     'tempatlahir' => $faker->city,
        //     'tgllahir' => $faker->date($format = 'Y-m-d', $max = 'now'),
        //     'alamat' => $faker->address,
        //     'jk' => $faker->randomElement(['Laki-Laki', 'Perempuan']),
        //     'telp' => $faker->phoneNumber,
        // ]);
        // for ($i = 0; $i < $jmlSeeder; $i++) {
        //     pembimbinglapangan::insert([
        //         'nama' => $faker->unique()->name,
        //         'email' => $faker->unique()->email,
        //         'username' => $faker->unique()->username,
        //         'password' => Hash::make('123'),
        //         'nomeridentitas' => $faker->unique()->numberBetween($min = 1000, $max = 9000),
        //         'agama' => $faker->randomElement(['Islam', 'Kristen', 'Hindu', 'Budha', 'Konghucu']),
        //         'tempatlahir' => $faker->city,
        //         'tgllahir' => $faker->date($format = 'Y-m-d', $max = 'now'),
        //         'alamat' => $faker->address,
        //         'jk' => $faker->randomElement(['Laki-Laki', 'Perempuan']),
        //         'telp' => $faker->phoneNumber,
        //     ]);
        // }

        // // seeder Pembimbing Sekolah
        // DB::table('pembimbingsekolah')->truncate();

        // pembimbingsekolah::insert([
        //     'nama' => $faker->unique()->name,
        //     'email' => $faker->unique()->email,
        //     'username' => 'pembimbingsekolah',
        //     'password' => Hash::make('123'),
        //     'nomeridentitas' => $faker->unique()->numberBetween($min = 1000, $max = 9000),
        //     'agama' => $faker->randomElement(['Islam', 'Kristen', 'Hindu', 'Budha', 'Konghucu']),
        //     'tempatlahir' => $faker->city,
        //     'tgllahir' => $faker->date($format = 'Y-m-d', $max = 'now'),
        //     'alamat' => $faker->address,
        //     'jk' => $faker->randomElement(['Laki-Laki', 'Perempuan']),
        //     'telp' => $faker->phoneNumber,
        // ]);

        // for ($i = 0; $i < $jmlSeeder; $i++) {
        //     pembimbingsekolah::insert([
        //         'nama' => $faker->unique()->name,
        //         'email' => $faker->unique()->email,
        //         'username' => $faker->unique()->username,
        //         'password' => Hash::make('123'),
        //         'nomeridentitas' => $faker->unique()->numberBetween($min = 1000, $max = 9000),
        //         'agama' => $faker->randomElement(['Islam', 'Kristen', 'Hindu', 'Budha', 'Konghucu']),
        //         'tempatlahir' => $faker->city,
        //         'tgllahir' => $faker->date($format = 'Y-m-d', $max = 'now'),
        //         'alamat' => $faker->address,
        //         'jk' => $faker->randomElement(['Laki-Laki', 'Perempuan']),
        //         'telp' => $faker->phoneNumber,
        //     ]);
        // }


        // $jmlSeeder = 50;
        // // seeder Pembimbing Sekolah
        // DB::table('siswa')->truncate();

        // $siswa_id = DB::table('siswa')->insertGetId([
        //     'nama' => $faker->unique()->name,
        //     'email' => $faker->unique()->email,
        //     'username' => 'siswa',
        //     'password' => Hash::make('123'),
        //     'nomeridentitas' => '1234',
        //     'agama' => $faker->randomElement(['Islam', 'Kristen', 'Hindu', 'Budha', 'Konghucu']),
        //     'tempatlahir' => $faker->city,
        //     'tgllahir' => $faker->date($format = 'Y-m-d', $max = 'now'),
        //     'alamat' => $faker->address,
        //     'jk' => $faker->randomElement(['Laki-Laki', 'Perempuan']),
        //     'telp' => $faker->phoneNumber,
        // ]);
        // kelasdetail::insert([
        //     'kelas_id' => $faker->randomElement(['1', '2']),
        //     'siswa_id' => $siswa_id,
        // ]);

        // for ($i = 0; $i < $jmlSeeder; $i++) {
        //     $siswa_id = DB::table('siswa')->insertGetId([
        //         'nama' => $faker->unique()->name,
        //         'email' => $faker->unique()->email,
        //         'username' => $faker->unique()->username,
        //         'password' => Hash::make('123'),
        //         'nomeridentitas' => $faker->unique()->numberBetween($min = 1000, $max = 9000),
        //         'agama' => $faker->randomElement(['Islam', 'Kristen', 'Hindu', 'Budha', 'Konghucu']),
        //         'tempatlahir' => $faker->city,
        //         'tgllahir' => $faker->date($format = 'Y-m-d', $max = 'now'),
        //         'alamat' => $faker->address,
        //         'jk' => $faker->randomElement(['Laki-Laki', 'Perempuan']),
        //         'telp' => $faker->phoneNumber,
        //     ]);


        //     kelasdetail::insert([
        //         'kelas_id' => $faker->randomElement(['1', '2']),
        //         'siswa_id' => $siswa_id,
        //     ]);
        //     pendaftaranprakerin::truncate();
        // }
    }
}

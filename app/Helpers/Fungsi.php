<?php

namespace App\Helpers;

use App\Models\settings;
use App\Models\tapel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Fungsi
{
    public static function carbonCreatedAt($input = null)
    {
        //input=Y-m-d H:i:s / format default createdAt dan updateddUt
        $hasil = null;

        if ($input) {
            $hasil = Carbon::createFromFormat('Y-m-d H:i:s', $input)->format('Y-m-d');
        }
        return $hasil;
    }

    public static function rupiah($angka)
    {
        //inputan : angka
        $hasil = (int) filter_var($angka, FILTER_SANITIZE_NUMBER_INT);
        $hasil_rupiah = "Rp " . number_format($hasil, 2, ',', '.');
        return $hasil_rupiah;
    }
    public static function rupiahtanpanol($angka)
    {
        //inputan : angka
        $hasil = (int) filter_var($angka, FILTER_SANITIZE_NUMBER_INT);
        $hasil_rupiah = "Rp " . number_format($hasil, 0, ',', '.');
        return $hasil_rupiah;
    }
    public static function angka($angka)
    {
        //inputan : angka
        $hasil = (int) filter_var($angka, FILTER_SANITIZE_NUMBER_INT);
        return $hasil;
    }

    public static function tanggalindo($inputan)
    {
        //formatInputan : 2020-02-19
        $bulanindo = 'Januari';
        $str = explode("-", $inputan);
        if ($str[1] == '01') {
            $bulanindo = 'Januari';
        } elseif ($str[1] == '02') {
            $bulanindo = 'Februari';
        } elseif ($str[1] == '03') {
            $bulanindo = 'Maret';
        } elseif ($str[1] == '04') {
            $bulanindo = 'April';
        } elseif ($str[1] == '05') {
            $bulanindo = 'Mei';
        } elseif ($str[1] == '06') {
            $bulanindo = 'Juni';
        } elseif ($str[1] == '07') {
            $bulanindo = 'Juli';
        } elseif ($str[1] == '08') {
            $bulanindo = 'Agustus';
        } elseif ($str[1] == '09') {
            $bulanindo = 'September';
        } elseif ($str[1] == '10') {
            $bulanindo = 'Oktober';
        } elseif ($str[1] == '11') {
            $bulanindo = 'November';
        } else {
            $bulanindo = 'Desember';
        }

        return $str[2] . " " . $bulanindo . " " . $str[0];
    }

    public static function tanggalindocreated($data)
    {
        //inputan : 2022-02-22 03:09:56
        $data2 = explode(" ", $data);

        $inputan = $data2[0];

        $bulanindo = 'Januari';
        $str = explode("-", $inputan);
        if ($str[1] == '01') {
            $bulanindo = 'Januari';
        } elseif ($str[1] == '02') {
            $bulanindo = 'Februari';
        } elseif ($str[1] == '03') {
            $bulanindo = 'Maret';
        } elseif ($str[1] == '04') {
            $bulanindo = 'April';
        } elseif ($str[1] == '05') {
            $bulanindo = 'Mei';
        } elseif ($str[1] == '06') {
            $bulanindo = 'Juni';
        } elseif ($str[1] == '07') {
            $bulanindo = 'Juli';
        } elseif ($str[1] == '08') {
            $bulanindo = 'Agustus';
        } elseif ($str[1] == '09') {
            $bulanindo = 'September';
        } elseif ($str[1] == '10') {
            $bulanindo = 'Oktober';
        } elseif ($str[1] == '11') {
            $bulanindo = 'November';
        } else {
            $bulanindo = 'Desember';
        }

        return $str[2] . " " . $bulanindo . " " . $str[0];
    }


    public static function bulanindo($inputan)
    {
        //inputan = 02
        $bulanindo = 'Januari';
        if ($inputan == '01') {
            $bulanindo = 'Januari';
        } elseif ($inputan == '02') {
            $bulanindo = 'Februari';
        } elseif ($inputan == '03') {
            $bulanindo = 'Maret';
        } elseif ($inputan == '04') {
            $bulanindo = 'April';
        } elseif ($inputan == '05') {
            $bulanindo = 'Mei';
        } elseif ($inputan == '06') {
            $bulanindo = 'Juni';
        } elseif ($inputan == '07') {
            $bulanindo = 'Juli';
        } elseif ($inputan == '08') {
            $bulanindo = 'Agustus';
        } elseif ($inputan == '09') {
            $bulanindo = 'September';
        } elseif ($inputan == '10') {
            $bulanindo = 'Oktober';
        } elseif ($inputan == '11') {
            $bulanindo = 'November';
        } else {
            $bulanindo = 'Desember';
        }

        return $bulanindo;
    }
    public static function tanggalindobln($inputan)
    {
        //inputan : 2022-01
        $bulanindo = 'Januari';
        $str = explode("-", $inputan);
        if ($str[1] == '01') {
            $bulanindo = 'Januari';
        } elseif ($str[1] == '02') {
            $bulanindo = 'Februari';
        } elseif ($str[1] == '03') {
            $bulanindo = 'Maret';
        } elseif ($str[1] == '04') {
            $bulanindo = 'April';
        } elseif ($str[1] == '05') {
            $bulanindo = 'Mei';
        } elseif ($str[1] == '06') {
            $bulanindo = 'Juni';
        } elseif ($str[1] == '07') {
            $bulanindo = 'Juli';
        } elseif ($str[1] == '08') {
            $bulanindo = 'Agustus';
        } elseif ($str[1] == '09') {
            $bulanindo = 'September';
        } elseif ($str[1] == '10') {
            $bulanindo = 'Oktober';
        } elseif ($str[1] == '11') {
            $bulanindo = 'November';
        } else {
            $bulanindo = 'Desember';
        }

        return $bulanindo . " " . $str[0];
    }
    public static  function isWeekend($date)
    {
        $weekDay = date('w', strtotime($date));
        return ($weekDay == 0 || $weekDay == 6);
    }
    // getter and setter
    public static function app_nama()
    {
        $settings = DB::table('settings')->first();
        // dd($settings->first()->app_nama);
        if ($settings != null) {
            $data = $settings->app_nama;
        } else {
            $data = "Judul Aplikasi";
        }
        return $data;
    }
    // getter and setter
    public static function app_namapendek()
    {
        $settings = DB::table('settings')->first();
        if ($settings != null) {
            $data = $settings->app_namapendek;
        } else {
            $data = "Judul Aplikasi";
        }
        return $data;
    }


    public static function pendaftaranpkl()
    {
        $settings = DB::table('settings')->first();
        if ($settings != null) {
            $data = $settings->pendaftaranpkl;
        } else {
            $data = "Aktif";
        }
        return $data;
    }

    public static function login_siswa()
    {
        $settings = DB::table('settings')->first();
        if ($settings != null) {
            $data = $settings->login_siswa;
        } else {
            $data = "Aktif";
        }
        return $data;
    }

    public static function login_pembimbingsekolah()
    {
        $settings = DB::table('settings')->first();
        if ($settings != null) {
            $data = $settings->login_pembimbingsekolah;
        } else {
            $data = "Aktif";
        }
        return $data;
    }

    public static function login_pembimbinglapangan()
    {
        $settings = DB::table('settings')->first();
        if ($settings != null) {
            $data = $settings->login_pembimbinglapangan;
        } else {
            $data = "Aktif";
        }
        return $data;
    }


    public static function app_tapel_aktif()
    {
        $jmltapel = tapel::where('status', 'Aktif')->count();
        if ($jmltapel > 0) {
            $tapel = tapel::where('status', 'Aktif')->orderBy('created_at', 'desc')->first();
            $data = $tapel->id;
        } else {

            $tapel = tapel::orderBy('created_at', 'desc')->first();
            $data = $tapel ? $tapel->id : '';
        }
        return $data;
    }
    public static function app_min_pembayaran()
    {
        $jmltapel = settings::first();
        $data = settings::where('id', 1)->first();
        return $data ? $data->min_pembayaran : 0;
    }
    public static function app_tapel_aktif_nama()
    {
        $jmltapel = tapel::where('status', 'Aktif')->count();
        if ($jmltapel > 0) {
            $tapel = tapel::where('status', 'Aktif')->orderBy('created_at', 'desc')->first();
            $data = $tapel->nama;
        } else {

            $tapel = tapel::orderBy('created_at', 'desc')->first();
            $data = $tapel->nama ? $tapel->nama : '';
        }
        return $data;
    }

    public static function paginationjml()
    {
        $settings = DB::table('settings')->first();
        $data = $settings->paginationjml;
        return $data;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class penilaian_absensi_dan_jurnal extends Model
{
    public $table = "penilaian_absensi_dan_jurnal";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'tapel_id',
        'siswa_id',
        'prefix',
        'nilai',
        'status',
    ];

    public function tapel()
    {
        return $this->belongsTo('App\Models\tapel');
    }
    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa');
    }
}

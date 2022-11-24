<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class penilaian extends Model
{
    public $table = "penilaian";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'penilaian_guru',
        'penilaian_pembimbinglapangan',
        'absensi',
        'jurnal',
        'tapel_id',
        'status',
        'jurusan_id',
    ];

    public function guru()
    {
        return $this->belongsTo('App\Models\pembimbingsekolah', 'kepalajurusan_id', 'id');
    }
}

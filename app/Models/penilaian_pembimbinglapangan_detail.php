<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class penilaian_pembimbinglapangan_detail extends Model
{
    public $table = "penilaian_pembimbinglapangan_detail";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'penilaian_pembimbinglapangan_id',
        'nilai',
    ];

    public function penilaian_guru()
    {
        return $this->belongsTo('App\Models\penilaian_guru');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class penilaian_guru extends Model
{
    public $table = "penilaian_guru";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'penilaian_id',
        'nama',
        'status',
        'jurusan_id',
    ];

    public function penilaian()
    {
        return $this->belongsTo('App\Models\penilaian');
    }
    public function penilaian_guru_detail()
    {
        return $this->hasMany('App\Models\penilaian_guru_detail');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class jurusan extends Model
{
    public $table = "jurusan";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'nama',
        'kepalajurusan_id',
    ];

    public function guru()
    {
        return $this->belongsTo('App\Models\pembimbingsekolah', 'kepalajurusan_id', 'id');
    }
    public function kelas()
    {
        return $this->hasMany('App\Models\kelas', 'jurusan', 'id');
    }
}

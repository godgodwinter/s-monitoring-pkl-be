<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class kelasdetail extends Model
{
    public $table = "kelasdetail";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'kelas_id',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }
    public function kelas()
    {
        $items=$this->belongsTo(kelas::class, 'kelas_id', 'id')->with('jurusan_table');
        // $items->kelas_nama='aa';
        return $items;
    }
}

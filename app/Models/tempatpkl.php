<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tempatpkl extends Model
{
    public $table = "tempatpkl";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'telp',
        'penanggungjawab',
        'nama_pimpinan',
        'kuota',
        'tapel_id',
        'status',  //Tidak Tersedia/Tersedia
        'tgl_mulai', //mulai pkl
        'tgl_selesai', // selesai pkl
    ];

    public function tapel()
    {
        return $this->belongsTo('App\Models\tapel');
    }
}

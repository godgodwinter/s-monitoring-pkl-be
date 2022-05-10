<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class pendaftaranprakerin extends Model
{
    public $table = "pendaftaranprakerin";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'tgl_daftar',
        'status', //Belum Daftar/ Proses Daftar / Menunggu / Sedang Prakerin / Telah Selesai
        'tapel_id',
    ];

    public function pendaftaranprakerin_detail()
    {
        return $this->hasMany('App\Models\pendaftaranprakerin_detail');
    }

    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa');
    }

    public function tapel()
    {
        return $this->belongsTo('App\Models\tapel');
    }

    // public function getPhotoAttribute($value){

    //     return url('storage/'.$value);
    // }

    // this is a recommended way to declare event handlers
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($pendaftaranprakerin) { // before delete() method call this
            $pendaftaranprakerin->pendaftaranprakerin_detail()->delete();
            // do the rest of the cleanup...
        });
    }
}

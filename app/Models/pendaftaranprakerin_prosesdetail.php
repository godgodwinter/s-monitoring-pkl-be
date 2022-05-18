<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class pendaftaranprakerin_prosesdetail extends Model
{
    public $table = "pendaftaranprakerin_prosesdetail";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'siswa_id',
    ];

    public function pendaftaranprakerin_proses()
    {
        return $this->belongsTo('App\Models\pendaftaranprakerin_proses');
    }

    public function siswa()
    {
        return $this->belongsTo('App\Models\siswa');
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
            $pendaftaranprakerin->pendaftaranprakerin_pengajuansiswa()->delete();
            // do the rest of the cleanup...
        });
    }
}

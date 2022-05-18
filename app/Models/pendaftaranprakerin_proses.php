<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class pendaftaranprakerin_proses extends Model
{
    public $table = "pendaftaranprakerin_proses";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'tempatpkl_id',
        'pendaftaranprakerin_id',
        'status', //Disetujui /ditolak/null
        'ket',
        'file',
    ];

    public function pendaftaranprakerin_prosesdetail()
    {
        return $this->hasMany('App\Models\pendaftaranprakerin_prosesdetail');
    }
    public function tempatpkl_id()
    {
        return $this->belongsTo('App\Models\tempatpkl_id');
    }

    public function pendaftaranprakerin_id()
    {
        return $this->belongsTo('App\Models\pendaftaranprakerin_id');
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

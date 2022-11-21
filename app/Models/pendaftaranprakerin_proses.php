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
        // 'pendaftaranprakerin_id', //tidak digunakan ,, gunakan siswa_id di prosesdetail
        'status', //Disetujui /ditolak/null
        'ket',
        'file',
        'tapel_id',
        'pembimbinglapangan_id',
        'pembimbingsekolah_id',
    ];

    public function pendaftaranprakerin_prosesdetail()
    {
        return $this->hasMany('App\Models\pendaftaranprakerin_prosesdetail')->with('siswa');
    }
    public function tempatpkl()
    {
        return $this->belongsTo('App\Models\tempatpkl')->with('pembimbinglapangan');
    }

    public function tapel()
    {
        return $this->belongsTo('App\Models\tapel');
    }
    public function pembimbinglapangan()
    {
        return $this->belongsTo('App\Models\pembimbinglapangan',);
    }
    public function pembimbingsekolah()
    {
        return $this->belongsTo('App\Models\pembimbingsekolah');
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class pendaftaranprakerin_detail extends Model
{
    public $table = "pendaftaranprakerin_detail";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'tempatpkl_id',
        'status', // Disetujui / Ditolak / Menunggu
        'keterangan',
        'tgl_pengajuan',
        'tgl_konfirmasi',
        'pendaftaranprakerin_id',
        'pembimbinglapangan_id',
        'pembimbingsekolah_id',
    ];

    public function pendaftaranprakerin()
    {
        return $this->belongsTo('App\Models\pendaftaranprakerin');
    }
    public function tempatpkl()
    {
        return $this->belongsTo('App\Models\tempatpkl');
    }
    public function pembimbinglapangan()
    {
        return $this->belongsTo('App\Models\pembimbinglapangan');
    }
    public function pembimbingsekolah()
    {
        return $this->belongsTo('App\Models\pembimbingsekolah');
    }


    // public function getPhotoAttribute($value){

    //     return url('storage/'.$value);
    // }
    // public function users()
    // {
    //     return $this->belongsTo('App\Models\User');
    // }

}

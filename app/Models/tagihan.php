<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tagihan extends Model
{
    public $table = "tagihan";

    use SoftDeletes;
    use HasFactory;

    // protected $fillable = [
    //     'nama',
    //     'sekolah_id',
    //     'walikelas_id',
    // ];


    protected $guarded = [];


    public function pembayaran()
    {
        return $this->hasMany('App\Models\pembayaran');
    }
    public function siswa()
    {
        return $this->belongsTo('App\Models\Siswa');
    }
    public function tapel()
    {
        return $this->belongsTo('App\Models\tapel');
    }
}

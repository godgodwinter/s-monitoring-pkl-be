<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tapel extends Model
{
    public $table = "tapel";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'nama',
        'status',
    ];

    // public function transaksidetail()
    // {
    //     return $this->hasMany('App\Models\transaksidetail');
    // }

    // public function getPhotoAttribute($value){

    //     return url('storage/'.$value);
    // }
    // public function users()
    // {
    //     return $this->belongsTo('App\Models\User');
    // }

}

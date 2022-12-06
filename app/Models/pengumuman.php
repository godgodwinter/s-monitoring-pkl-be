<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class pengumuman extends Model
{
    public $table = "pengumuman";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'slug',
        'prefix', //default 'pengumuman'
        'status',
        'title',
        'content',
        'desc'
    ];

    public function label()
    {
        return $this->hasMany('App\Models\label', 'id', 'label_id')->where('prefix', 'pengumuman');
    }
    public function penilaian_guru_detail()
    {
        return $this->hasMany('App\Models\penilaian_guru_detail');
    }
}

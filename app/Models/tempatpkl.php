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
        'tapel_id',
    ];

    public function tapel()
    {
        return $this->belongsTo('App\Models\tapel');
    }
}

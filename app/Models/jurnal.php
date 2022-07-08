<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class jurnal extends Model
{
    public $table = "jurnal";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'label',
        'desc',
        'file',
        'status',
        'alasan',
        'siswa_id',
    ];

    public function siswa()
    {
        // return $this->belongsTo('App\Models\tapel');
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class settings extends Model
{
    public $table = "settings";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'app_nama',
        'app_namapendek',
        'paginationjml',
        'pendaftaranpkl',
        'login_siswa',
        'login_pembimbingsekolah',
        'login_pembimbinglapangan',
    ];
}

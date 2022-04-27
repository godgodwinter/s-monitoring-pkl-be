<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class kelas extends Model
{
        public $table = "kelas";

        use SoftDeletes;
        use HasFactory;

        protected $fillable = [
            'tingkatan',
            'jurusan',
            'suffix',
            'tapel_id',
        ];

        public function tapel()
        {
            return $this->belongsTo('App\Models\tapel');
        }

}

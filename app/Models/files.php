<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class files extends Model
{
        public $table = "files";

        use SoftDeletes;
        use HasFactory;

        protected $fillable = [
            'nama',
            'prefix',
            'photo',
            'files',
            'desc',
            'parrent_id',
        ];

        // public function tapel()
        // {
        //     return $this->belongsTo('App\Models\tapel');
        //     return $this->belongsTo(User::class,'users_id','id');
        // }

}

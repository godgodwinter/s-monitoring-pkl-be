<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tempatpkl_detail extends Model
{
    public $table = "tempatpkl_detail";

    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'tempatpkl_id',
        'pembimbinglapangan_id',
        'status',
    ];

    public function tempatpkl()
    {
        return $this->belongsTo('App\Models\tempatpkl');
    }
    public function pembimbinglapangan()
    {
        return $this->belongsTo('App\Models\pembimbinglapangan');
    }
}

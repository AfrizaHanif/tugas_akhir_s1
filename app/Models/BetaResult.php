<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BetaResult extends Model
{
    use HasFactory;

    protected $table = "beta_results";
    //protected $primaryKey = 'id_result';
    //public $incrementing = false;
    //protected $keyType = 'string';

    protected $fillable = [
        //'id_result',
        'id_officer',
        'id_period',
        'final_score',
        'status',
    ];

    public function officer()
    {
        return $this->belongsTo(Officer::class, 'id_officer', 'id_officer',);
    }
    public function period()
    {
        return $this->belongsTo(Period::class, 'id_period', 'id_period');
    }
}

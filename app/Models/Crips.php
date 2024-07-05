<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crips extends Model
{
    use HasFactory;

    protected $table = "crips";
    protected $primaryKey = 'id_crips';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_crips',
        'id_criteria',
        'name',
        //'description',
        'value_type',
        'value_from',
        'value_to',
        'score',
    ];

    //CONNECT TO ANOTHER TABLE
    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'id_criteria', 'id_criteria');
    }
}

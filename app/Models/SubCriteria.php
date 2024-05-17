<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCriteria extends Model
{
    use HasFactory;

    protected $table = "sub_criterias";
    protected $primaryKey = 'id_sub_criteria';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_sub_criteria',
        'id_criteria',
        'name',
        'weight',
        'attribute',
        'level',
        'need',
    ];

    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'id_criteria', 'id_criteria');
    }
    public function presence()
    {
        return $this->hasMany(Presence::class, 'id_sub_criteria', 'id_sub_criteria');
    }
    public function performance()
    {
        return $this->hasMany(Performance::class, 'id_sub_criteria', 'id_sub_criteria');
    }

    //BETA
    public function betapresence()
    {
        return $this->belongsTo(BetaPresence::class, 'id_sub_criteria', 'id_sub_criteria');
    }
    public function betaperformance()
    {
        return $this->belongsTo(BetaPerformance::class, 'id_sub_criteria', 'id_sub_criteria');
    }
}

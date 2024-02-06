<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BetaPerformance extends Model
{
    use HasFactory;

    protected $table = "beta_performances";
    protected $primaryKey = 'id_performance';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_performance',
        'id_period',
        'id_officer',
        'id_sub_criteria',
        'input',
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
    public function subcriteria()
    {
        return $this->belongsTo(SubCriteria::class, 'id_sub_criteria', 'id_sub_criteria');
    }
}

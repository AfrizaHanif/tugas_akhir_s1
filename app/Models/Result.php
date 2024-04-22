<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $table = "results";

    protected $fillable = [
        'id_officer',
        'id_period',
        'count',
        'final_score',
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

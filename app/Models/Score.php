<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $table = "scores";

    protected $fillable = [
        'id_employee',
        'id_period',
        'final_score',
        'second_score',
        'status',
    ];

    //CONNECT TO ANOTHER TABLE
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee', 'id_employee',);
    }
    public function period()
    {
        return $this->belongsTo(Period::class, 'id_period', 'id_period');
    }
}

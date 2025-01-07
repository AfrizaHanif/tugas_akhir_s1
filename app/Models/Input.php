<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Input extends Model
{
    use HasFactory;

    protected $table = "inputs";
    protected $primaryKey = 'id_input';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_input',
        'id_period', //FOREIGN
        'id_employee', //FOREIGN
        'id_criteria', //FOREIGN
        'input',
        'input_raw',
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
    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'id_criteria', 'id_criteria');
    }
}

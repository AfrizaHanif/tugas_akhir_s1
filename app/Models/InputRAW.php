<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputRAW extends Model
{
    use HasFactory;

    protected $table = "input_raws";
    protected $primaryKey = 'id_input_raw';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_input_raw',
        'id_period', //FOREIGN
        'id_officer', //FOREIGN
        'id_criteria', //FOREIGN
        'input',
        'status',
    ];

    //CONNECT TO ANOTHER TABLE
    public function officer()
    {
        return $this->belongsTo(Officer::class, 'id_officer', 'id_officer',);
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

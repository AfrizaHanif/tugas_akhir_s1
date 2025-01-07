<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryInput extends Model
{
    use HasFactory;

    protected $table = "history_inputs";

    protected $fillable = [
        'id_period',
        //'period_name',
        //'period_month',
        //'period_num_month',
        //'period_year',
        'id_employee',
        //'employee_nip',
        'employee_name',
        'employee_position',
        'id_sub_team',
        'sub_team_1_name',
        'sub_team_2_name',
        'id_category',
        'category_name',
        'id_criteria',
        'criteria_name',
        'weight',
        'attribute',
        //'level',
        'max',
        'unit',
        //'is_lead',
        'input',
        'input_raw',
    ];

    //CONNECT TO ANOTHER TABLE
    public function period()
    {
        return $this->belongsTo(Period::class, 'id_period', 'id_period',);
    }
}

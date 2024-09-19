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
        'period_name',
        'period_month',
        'period_num_month',
        'period_year',
        'id_officer',
        'officer_nip',
        'officer_name',
        'officer_position',
        'id_sub_team',
        'officer_team',
        'id_category',
        'category_name',
        'id_criteria',
        'criteria_name',
        'weight',
        'attribute',
        'level',
        'max',
        'is_lead',
        'input',
        'input_raw',
    ];
}

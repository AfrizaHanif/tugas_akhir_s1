<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryResult extends Model
{
    use HasFactory;

    protected $table = "history_results";

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
        'employee_photo',
        'final_score',
    ];

    //CONNECT TO ANOTHER TABLE
    public function period()
    {
        return $this->belongsTo(Period::class, 'id_period', 'id_period',);
    }
}

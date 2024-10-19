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
        'period_name',
        'period_month',
        'period_year',
        'id_officer',
        //'officer_nip',
        'officer_name',
        'officer_position',
        'officer_photo',
        'final_score',
    ];
}

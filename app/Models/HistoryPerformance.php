<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPerformance extends Model
{
    use HasFactory;

    protected $table = "history_performances";

    protected $fillable = [
        'period_name',
        'officer_name',
        'sub_criteria_name',
        'input',
    ];
}

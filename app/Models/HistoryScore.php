<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryScore extends Model
{
    use HasFactory;

    protected $table = "history_scores";

    protected $fillable = [
        'period_name',
        'officer_name',
        'final_score',
    ];
}

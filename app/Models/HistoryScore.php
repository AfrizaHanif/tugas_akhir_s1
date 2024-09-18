<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryScore extends Model
{
    use HasFactory;

    protected $table = "history_scores";

    protected $fillable = [
        'id_period',
        'period_name',
        'id_officer',
        'officer_nip',
        'officer_name',
        'officer_position',
        'officer_team',
        'final_score',
        'ckp',
    ];
}

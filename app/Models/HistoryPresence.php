<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPresence extends Model
{
    use HasFactory;

    protected $table = "history_presences";

    protected $fillable = [
        'id_period',
        'period_name',
        'id_officer',
        'officer_name',
        'id_sub_criteria',
        'sub_criteria_name',
        'input',
    ];
}

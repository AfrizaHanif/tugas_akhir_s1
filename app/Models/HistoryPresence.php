<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPresence extends Model
{
    use HasFactory;

    protected $table = "history_presences";

    protected $fillable = [
        'period_name',
        'officer_name',
        'sub_criteria_name',
        'input',
    ];
}

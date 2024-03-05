<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryVote extends Model
{
    use HasFactory;

    protected $table = "history_votes";

    protected $fillable = [
        'period_name',
        'officer_name',
        'votes',
    ];
}

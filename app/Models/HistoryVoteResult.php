<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryVoteResult extends Model
{
    use HasFactory;

    protected $table = "history_vote_results";

    protected $fillable = [
        'period_name',
        'officer_name',
        'vote_criteria_name',
        'final_vote',
    ];
}

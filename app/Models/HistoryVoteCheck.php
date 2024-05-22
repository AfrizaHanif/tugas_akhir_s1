<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryVoteCheck extends Model
{
    use HasFactory;

    protected $table = "history_vote_checks";

    protected $fillable = [
        'id_period',
        'period_name',
        'id_officer',
        'officer_name',
        'id_vote_criteria',
        'vote_criteria_name',
        'officer_selected',
        'officer_selected_name',
    ];
}

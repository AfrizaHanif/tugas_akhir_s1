<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryVote extends Model
{
    use HasFactory;

    protected $table = "history_votes";

    protected $fillable = [
        'id_period',
        'period_name',
        'id_officer',
        'officer_name',
        'officer_department',
        'officer_photo',
        'id_vote_criteria',
        'vote_criteria_name',
        'votes',
    ];
}

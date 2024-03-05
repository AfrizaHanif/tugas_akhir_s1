<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryVoteCheck extends Model
{
    use HasFactory;

    protected $table = "history_vote_checks";

    protected $fillable = [
        'period_name',
        'officer_name',
        'officer_selected',
    ];
}

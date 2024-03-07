<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $table = "votes";

    protected $fillable = [
        'id_officer',
        'id_period',
        'id_vote_criteria',
        'votes',
    ];

    public function officer()
    {
        return $this->belongsTo(Officer::class, 'id_officer', 'id_officer',);
    }
    public function period()
    {
        return $this->belongsTo(Period::class, 'id_period', 'id_period');
    }
    public function votecriteria()
    {
        return $this->belongsTo(VoteCriteria::class, 'id_vote_criteria', 'id_vote_criteria');
    }
}

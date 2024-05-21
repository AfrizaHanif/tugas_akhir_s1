<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteCriteria extends Model
{
    use HasFactory;

    protected $table = "vote_criterias";

    protected $fillable = [
        'id_vote_criteria',
        'name',
    ];

    //CONNECT FROM ANOTHER TABLE
    public function vote()
    {
        return $this->hasMany(Vote::class, 'id_vote_criteria', 'id_vote_criteria');
    }
    public function votecheck()
    {
        return $this->hasMany(VoteCheck::class, 'id_vote_criteria', 'id_vote_criteria');
    }
    public function voteresult()
    {
        return $this->hasMany(VoteResult::class, 'id_vote_criteria', 'id_vote_criteria');
    }
}

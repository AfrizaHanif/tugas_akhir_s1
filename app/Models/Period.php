<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $table = "periods";
    protected $primaryKey = 'id_period';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_period',
        'name',
        'month',
        'year',
        'status',
    ];

    public function presence()
    {
        return $this->hasMany(Presence::class, 'id_period', 'id_period');
    }
    public function performance()
    {
        return $this->hasMany(Performance::class, 'id_period', 'id_period');
    }
    public function result()
    {
        return $this->hasMany(Result::class, 'id_period', 'id_period');
    }
    public function score()
    {
        return $this->hasMany(Score::class, 'id_period', 'id_period');
    }
    public function vote()
    {
        return $this->hasMany(Vote::class, 'id_period', 'id_period');
    }
    public function votecheck()
    {
        return $this->hasMany(VoteCheck::class, 'id_period', 'id_period');
    }
    public function voteresult()
    {
        return $this->hasMany(VoteResult::class, 'id_period', 'id_period');
    }

    //BETA
    public function betapresence()
    {
        return $this->hasMany(BetaPresence::class, 'id_period', 'id_period');
    }
    public function betaperformance()
    {
        return $this->hasMany(BetaPerformance::class, 'id_period', 'id_period');
    }
}

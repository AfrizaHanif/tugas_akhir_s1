<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    use HasFactory;

    protected $table = "officers";
    protected $primaryKey = 'id_officer';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_officer',
        'nip_bps',
        'nip',
        'name',
        'org_code',
        'id_department',
        //'id_part',
        'status',
        'last_group',
        'last_education',
        'place_birth',
        'date_birth',
        'gender',
        'religion',
        //'id_user',
        'photo',
        //'leader',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'id_department', 'id_department',);
    }
    /*
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user',);
    }
    */
    /*
    public function part()
    {
        return $this->belongsTo(Part::class, 'id_part', 'id_part');
    }
    */
    public function presence()
    {
        return $this->hasMany(Presence::class, 'id_officer', 'id_officer');
    }
    public function performance()
    {
        return $this->hasMany(Performance::class, 'id_officer', 'id_officer');
    }
    public function result()
    {
        return $this->hasMany(Result::class, 'id_officer', 'id_officer');
    }
    public function score()
    {
        return $this->hasMany(Score::class, 'id_officer', 'id_officer');
    }
    public function vote()
    {
        return $this->hasMany(Vote::class, 'id_officer', 'id_officer');
    }
    public function votecheck()
    {
        return $this->hasMany(VoteCheck::class, 'id_officer', 'id_officer');
    }
    public function voteresult()
    {
        return $this->hasMany(VoteResult::class, 'id_officer', 'id_officer');
    }
    public function user()
    {
        return $this->hasMany(User::class, 'id_officer', 'id_officer',);
    }

    //BETA
    public function betapresence()
    {
        return $this->hasMany(BetaPresence::class, 'id_officer', 'id_officer');
    }
    public function betaperformance()
    {
        return $this->hasMany(BetaPerformance::class, 'id_officer', 'id_officer');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTeam extends Model
{
    use HasFactory;

    protected $table = "sub_teams";
    protected $primaryKey = 'id_sub_team';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_sub_team',
        'id_team',
        'name',
        'description',
    ];

    //CONNECT TO ANOTHER TABLE
    public function team()
    {
        return $this->belongsTo(Team::class, 'id_team', 'id_team');
    }

    //CONNECT FROM ANOTHER TABLE
    public function officer_1()
    {
        return $this->hasMany(Officer::class, 'id_sub_team_1', 'id_sub_team');
    }
    public function officer_2()
    {
        return $this->hasMany(Officer::class, 'id_sub_team_2', 'id_sub_team');
    }
}

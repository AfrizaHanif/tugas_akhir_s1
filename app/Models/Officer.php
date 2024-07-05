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
        //'nip_bps',
        'nip',
        'name',
        'org_code',
        'id_department',
        'id_sub_team_1',
        'id_sub_team_2',
        //'id_part',
        'status',
        'last_group',
        'last_education',
        'place_birth',
        'date_birth',
        'gender',
        'religion',
        'is_lead',
        'photo',
        //'leader',
    ];

    //CONNECT TO ANOTHER TABLE
    public function department()
    {
        return $this->belongsTo(Department::class, 'id_department', 'id_department',);
    }
    public function subteam_1()
    {
        return $this->belongsTo(SubTeam::class, 'id_sub_team_1', 'id_sub_team',);
    }
    public function subteam_2()
    {
        return $this->belongsTo(SubTeam::class, 'id_sub_team_2', 'id_sub_team',);
    }

    //CONNECT FROM ANOTHER TABLE
    public function input()
    {
        return $this->hasMany(Input::class, 'id_officer', 'id_officer');
    }
    public function result()
    {
        return $this->hasMany(Result::class, 'id_officer', 'id_officer');
    }
    public function score()
    {
        return $this->hasMany(Score::class, 'id_officer', 'id_officer');
    }
    public function user()
    {
        return $this->hasMany(User::class, 'id_officer', 'id_officer',);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = "employees";
    protected $primaryKey = 'id_employee';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_employee',
        //'nip_bps',
        //'nip',
        'name',
        'org_code',
        'id_position', //FK
        'id_sub_team_1', //FK
        'id_sub_team_2', //FK
        //'id_part',
        //'status',
        //'last_group',
        //'last_education',
        'email',
        'phone',
        'place_birth',
        'date_birth',
        'gender',
        'religion',
        'is_lead',
        'photo',
        'is_hr',
        //'leader',
        'status',
    ];

    //CONNECT TO ANOTHER TABLE
    public function position()
    {
        return $this->belongsTo(Position::class, 'id_position', 'id_position',);
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
    public function user()
    {
        return $this->hasMany(User::class, 'id_employee', 'id_employee');
    }
    public function input()
    {
        return $this->hasMany(Input::class, 'id_employee', 'id_employee');
    }
    public function result()
    {
        return $this->hasMany(Result::class, 'id_employee', 'id_employee');
    }
    public function score()
    {
        return $this->hasMany(Score::class, 'id_employee', 'id_employee');
    }
    public function message()
    {
        return $this->hasMany(Message::class, 'id_employee', 'id_employee');
    }
}

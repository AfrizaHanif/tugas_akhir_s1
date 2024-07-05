<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $table = "teams";
    protected $primaryKey = 'id_team';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_team',
        'id_part', //FOREIGN
        'name',
    ];

    //CONNECT TO ANOTHER TABLE
    public function part()
    {
        return $this->belongsTo(Part::class, 'id_part', 'id_part');
    }

    //CONNECT FROM ANOTHER TABLE
    public function subteam()
    {
        return $this->hasMany(SubTeam::class, 'id_team', 'id_team');
    }
}

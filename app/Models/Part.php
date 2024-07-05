<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $table = "parts";
    protected $primaryKey = 'id_part';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_part',
        'name',
    ];

    //CONNECT FROM ANOTHER TABLE
    public function team()
    {
        return $this->hasMany(Team::class, 'id_part', 'id_part');
    }
    /*
    public function officer()
    {
        return $this->hasMany(Officer::class, 'id_part', 'id_part');
    }
    public function department()
    {
        return $this->hasMany(Department::class, 'id_part', 'id_part');
    }
    */
}

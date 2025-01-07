<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = "positions";
    protected $primaryKey = 'id_position';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_position',
        //'id_part',
        'name',
        //'part',
        'description',
    ];

    //CONNECT TO ANOTHER TABLE
    /*
    public function part()
    {
        return $this->belongsTo(Part::class, 'id_part', 'id_part');
    }
    */

    //CONNECT FROM ANOTHER TABLE
    public function employee()
    {
        return $this->hasMany(Employee::class, 'id_position', 'id_position');
    }
}

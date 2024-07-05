<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = "departments";
    protected $primaryKey = 'id_department';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_department',
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
    public function officer()
    {
        return $this->hasMany(Officer::class, 'id_department', 'id_department');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $table = "criterias";
    protected $primaryKey = 'id_criteria';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_criteria',
        'id_category',
        'name',
        'weight',
        'attribute',
        'level',
        'max',
        'need',
        'source',
    ];

    //CONNECT TO ANOTHER TABLE
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }

    //CONNECT FROM ANOTHER TABLE
    public function input()
    {
        return $this->hasMany(Input::class, 'id_criteria', 'id_criteria');
    }
    public function crips()
    {
        return $this->hasMany(Crips::class, 'id_criteria', 'id_criteria');
    }
}

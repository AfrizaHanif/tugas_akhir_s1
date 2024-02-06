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
        'name',
        'type',
    ];

    public function subcriteria()
    {
        return $this->hasMany(SubCriteria::class, 'id_criteria', 'id_criteria');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = "categories";
    protected $primaryKey = 'id_category';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_category',
        'name',
        'type',
        'source',
    ];

    //CONNECT FROM ANOTHER TABLE
    public function criteria()
    {
        return $this->hasMany(Criteria::class, 'id_category', 'id_category');
    }
}

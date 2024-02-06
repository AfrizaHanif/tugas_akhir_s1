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

    public function officer()
    {
        return $this->hasMany(Officer::class, 'id_part', 'id_part');
    }
}

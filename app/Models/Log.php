<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = "logs";
    //protected $primaryKey = 'id_input_raw';
    //public $incrementing = false;
    //protected $keyType = 'string';

    protected $fillable = [
        'page',
        'category',
        'details',
    ];
}

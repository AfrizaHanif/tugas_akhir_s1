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
        'id_user', //FK
        'activity',
        'progress',
        'result',
        'descriptions',
    ];

    //CONNECT TO ANOTHER TABLE
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user',);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $table = "periods";
    protected $primaryKey = 'id_period';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_period',
        'name',
        'month',
        'year',
        'active_days',
        'status',
    ];

    //CONNECT FROM ANOTHER TABLE
    public function input()
    {
        return $this->hasMany(Input::class, 'id_period', 'id_period');
    }
    public function result()
    {
        return $this->hasMany(Result::class, 'id_period', 'id_period');
    }
    public function score()
    {
        return $this->hasMany(Score::class, 'id_period', 'id_period');
    }
}

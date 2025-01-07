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
        'num_month',
        'year',
        'active_days',
        'progress_status',
        'import_status',
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
    public function history_input()
    {
        return $this->hasMany(HistoryInput::class, 'id_period', 'id_period');
    }
    public function history_score()
    {
        return $this->hasMany(HistoryScore::class, 'id_period', 'id_period');
    }
    public function history_result()
    {
        return $this->hasMany(HistoryResult::class, 'id_period', 'id_period');
    }
}

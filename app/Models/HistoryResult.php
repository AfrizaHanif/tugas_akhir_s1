<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryResult extends Model
{
    use HasFactory;

    protected $table = "history_results";

    protected $fillable = [
        'period_name',
        'officer_name',
    ];
}

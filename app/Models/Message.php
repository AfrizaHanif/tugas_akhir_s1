<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = "messages";

    protected $fillable = [
        'id_officer',
        'officer_nip',
        'officer_name',
        'officer_department',
        'message_in',
        'message_out',
        'status',
    ];
}

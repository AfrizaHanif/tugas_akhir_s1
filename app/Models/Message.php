<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = "messages";

    protected $fillable = [
        'id_employee', //FK
        //'employee_nip',
        //'employee_name',
        //'employee_position',
        'message_in',
        'message_out',
        'type',


    ];

    //CONNECT TO ANOTHER TABLE
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee', 'id_employee',);
    }
}

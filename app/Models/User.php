<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id_user';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        //'nip',
        'id_employee', //FK
        'name',
        'username',
        //'email',
        'password',
        'part',
        'force_logout',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    public function part($part){
        if ($this->part == $part) {
            return true;
        }

        return false;
    }
    */
    //CONNECT TO ANOTHER TABLE
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee', 'id_employee',);
    }

    //CONNECT FROM ANOTHER TABLE
    public function log()
    {
        return $this->hasMany(Log::class, 'id_user', 'id_user');
    }
}

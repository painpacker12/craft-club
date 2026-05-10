<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'photo', 'about'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Мастер-классы, которые ведет пользователь
    public function masterClasses()
    {
        return $this->hasMany(MasterClass::class);
    }

    // Записи пользователя на мастер-классы
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Superadmin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'superadmin';

    protected $fillable = ['first_name','last_name','phone','password'];

    protected $hidden = ['password'];
}
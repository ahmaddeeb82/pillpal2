<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'password',
        'address',
        'image',   
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function medicines() {
        return $this->hasMany(Medicine::class);
    }

    public function categories() {
        return $this->hasMany(Category::class);
    }

    public function caompanies() {
        return $this->hasMany(Company::class);
    }
}

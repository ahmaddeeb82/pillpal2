<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','medicine_id'];

    protected $table = 'medicine_user';

    public function users() {
        return $this->hasMany(User::class);
    }

    public function medicines() {
        return $this->hasMany(Medicine::class);
    }
}

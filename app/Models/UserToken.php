<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    use HasFactory;

    protected $fillable = ['device_token', 'user_id'];

    protected $table = 'users_tokens';

    public function user() {
        return $this->belongsTo(User::class);
    }
}

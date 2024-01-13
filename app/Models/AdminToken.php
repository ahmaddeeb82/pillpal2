<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminToken extends Model
{
    use HasFactory;

    protected $fillable = ['device_token', 'admin_id'];

    protected $table = 'admins_tokens';

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}

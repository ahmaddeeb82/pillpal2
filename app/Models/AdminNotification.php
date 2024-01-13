<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'admin_id'];

    protected $table = 'admins_notifications';

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'admin_id'];

    public function medicines(){
        return $this->hasMany(Medicine::class);
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}

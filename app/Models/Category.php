<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'admin_id', 'image'];

    public $translatable = ['name'];

    public function medicines() {
        return $this->belongsToMany(Medicine::class,'medicine_category');
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}

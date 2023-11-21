<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineCategory extends Model
{
    use HasFactory;

    protected $fillable = ['medicine_id', 'category_id'];

    protected $table = 'medicine_category';

    public function categories() {
        return $this->hasMany(Category::class);
    }

    public function medicines() {
        return $this->hasMany(Medicine::class);
    }
}

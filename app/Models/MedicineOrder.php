<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineOrder extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'medicine_id', 'quantity', 'quantity_price'];
    
    protected $table = 'medicine_order';

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function medicines(){
        return $this->hasMany(Medicine::class);
    }
}

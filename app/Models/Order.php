<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','status','total_price','payed', 'order_date'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function medicines() {
        return $this->belongsToMany(Medicine::class,);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = ['scientific_name', 'commercial_name','quantity','expiration_date','price','company_id','expired','image', 'admin_id'];

    public function categories(){
        return $this->belongsToMany(Category::class,'medicine_category');
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'quantity_price');
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}

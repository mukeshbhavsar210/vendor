<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountPercentage extends Model {
    use HasFactory;

    protected $fillable = [ 'percentage'];

    public function products() {
        return $this->hasMany(Product::class,'discount_percentage_id');
    }


    // public function products() {
    //     return $this->hasMany(Product::class);
    // }    

   
}

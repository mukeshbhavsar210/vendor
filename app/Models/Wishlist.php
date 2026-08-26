<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model {
    use HasFactory;

    public $fillable = ['user_id','product_id'];


    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function subCategory() {
        return $this->belongsTo(SubCategory::class);
    }

    public function subSubCategory() {
        return $this->belongsTo(SubSubCategory::class);
    }

}

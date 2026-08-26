<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSubCategory extends Model {
    use HasFactory;

    protected $fillable = [ 'category_id', 'sub_category_id', 'sub_sub_category_name', 'sub_sub_category_slug'];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // public function subCategory() {
    //     return $this->belongsTo(SubCategory::class, 'sub_category_id');
    // }

    public function subCategory() {
        return $this->belongsTo(SubCategory::class);
    }

    public function products() {
        return $this->hasMany(Product::class, 'sub_sub_category_id');
    }
}
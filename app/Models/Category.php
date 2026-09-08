<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    use HasFactory;

    protected $fillable = [ 'category_name', 'category_slug', 'image', 'showHome', 'category_modal', 'menu_order', 'status'];

    public function sub_category(){
        return $this->hasMany(SubCategory::class);
    }

    // public function subCategories(){
    //     return $this->hasMany(SubCategory::class, 'category_id'); 
    // }

    public function subCategories() {
        return $this->hasMany(SubCategory::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function parent(){
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(){
        return $this->hasMany(Category::class, 'parent_id');
    }   

    
}
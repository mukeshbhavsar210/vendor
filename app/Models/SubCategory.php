<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model {
    use HasFactory;

    protected $fillable = [ 'category_id','sub_category_name','sub_category_slug','image','price','instant','banner','banner_title','banner_label','banner_details','banner_image','sort_order','status',  ];

    public function subCategories() {
        return $this->hasMany(SubCategory::class, 'category_id');  
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function services(){
        return $this->hasMany(Service::class, 'sub_category_id', 'id');
    }

    public function ratings() {
        return $this->hasMany(Rating::class, 'category_id', 'category_id');
    }

    public function subSubCategories() {
        return $this->hasMany(SubSubCategory::class);
    }
}
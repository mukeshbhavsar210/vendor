<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model {
    use HasFactory;

    protected $fillable = [ 'category_id','sub_category_name','sub_category_slug','process_id','image','price','instant','banner','banner_title','banner_label','banner_details','banner_image','sort_order','status',  ];

    protected $casts = [
        'process_id' => 'array',
    ];

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
        return $this->hasMany(Rating::class, 'service_id', 'id'); 
    }

    public function subSubCategories() {
        return $this->hasMany(SubSubCategory::class);
    }

    public function process() {
        return $this->belongsTo(Process::class, 'process_id');
    }
}
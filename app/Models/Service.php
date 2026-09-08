<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['vendor_id','category_id','sub_category_id','name','slug','description','price','price_type','city','state','pincode',
        'is_featured','admin_note','approved_at','views','search_count','rating','total_reviews','meta_title','meta_description','sort_order','status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_featured' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function service_images(){
        return $this->hasMany(ServiceImage::class);
    }    

    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }

    // public function category(){
    //     return $this->belongsTo(Category::class, 'category_id');
    // }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function subCategory() {
        return $this->belongsTo(SubCategory::class);
    }

    // public function images(){
    //     return $this->hasMany(ServiceImage::class)
    //         ->orderBy('sort_order');
    // }

    public function images(){
        return $this->hasMany(CategoryImage::class, 'category_id')
            ->orderBy('sort_order');
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }    
}
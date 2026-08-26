<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    use HasFactory;

    protected $fillable = [ 'title', 'slug', 'description', 'short_description', 'shipping_returns', 'related_products', 
        'price', 'category_id', 'sub_category_id', 'sub_sub_category_id', 'discount_percentage_id', 'is_featured', 'sku', 'barcode', 'recommended', 'views', 'average_rating', 'status', 
    ];

    protected $appends = ['average_rating', 'rating_count'];

    public function scopeFilterData($query, $category = null, $subCategory = null) {
        $query->where('status', 1);

        if ($category) {
            $query->where('category_id', $category->id);
        }

        if ($subCategory) {
            $query->where('sub_category_id', $subCategory->id);
        }

        return $query;
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function product_images(){
        return $this->hasMany(ProductImage::class);
    }    

    public function variant_images(){
        return $this->hasMany(ProductVariant::class);
    }  

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function color() {
        return $this->belongsTo(Color::class);
    }

    public function size() {
        return $this->belongsTo(Size::class);
    }
   
    public function colors() {
        return $this->belongsToMany(Color::class, 'product_colors', 'product_id', 'color_id');
    }

    public function sizes(){
        return $this->belongsToMany(Size::class, 'product_sizes', 'product_id', 'size_id');
    }

    public function images() {
        return $this->hasMany(ProductImage::class);
    }    

    public function ratings() {
        return $this->hasMany(Rating::class);
    }

    public function getAverageRatingAttribute() {
        return round($this->ratings()->avg('rating'), 1);
    }

    public function getRatingCountAttribute() {
        return $this->ratings()->count();
    }

    public function variants() {
        return $this->hasMany(ProductVariant::class);
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

    // public function subCategory_id() {
    //     return $this->belongsTo(SubCategory::class,'sub_category_id');
    // }   


    public function discounts() {
        return $this->hasMany(Discount::class);
    }    

    public function discount(){
        return $this->belongsTo(DiscountPercentage::class, 'discount_percentage_id');
    }

    public function discount_id() {
        return $this->hasOne(Discount::class, 'product_id', 'id');
    }
    
    public function discount_filter(){
        return $this->belongsTo(DiscountPercentage::class,'discount_percentage_id');
    }

    public function getDiscountPercentAttribute(){
        return optional($this->discount)->percentage ?? 0;
    }

    public function getDiscountPriceAttribute(){
        $percent = $this->discount_percent;
        return $this->price - ($this->price * $percent / 100);
    }    

    public function coupons() {
        return $this->belongsToMany(
            DiscountCoupon::class,
            'coupon_product',
            'product_id',
            'discount_coupons_id'
        );
    }

    public function getUrlAttribute() {
        return route('front.product', [
            'item2' => $this->subCategory->sub_category_slug ?? '',
            'item3' => $this->subSubCategory->sub_sub_category_slug ?? '',
            'slug'  => $this->slug
        ]);
    }

    public function getFirstImageAttribute() {
        return $this->product_images->first()->image ?? 'default-150x150.png';
    }
}
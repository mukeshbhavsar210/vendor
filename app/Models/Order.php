<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use HasFactory;

    protected $fillable = [ 'user_id', 'product_id', 'product_variant_id', 'customer_address_id', 'subtotal', 'shipping', 'grandtotal', 
                            'razorpay_order_id', 'transaction_id', 'razorpay_signature', 'payment_status', 'payment_method', 'status'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function product_images(){
        return $this->hasMany(ProductImage::class);
    }

    public function variant() {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function variant_image(){
        return $this->hasMany(ProductVariant::class);
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeWithFullRelations($query) {
        return $query->with([
            'user',
            'items',
            'orderItems.product.images',
            'orderItems.product.size',
            'orderItems.product.color'
        ]);
    }

    public function state() {
        return $this->belongsTo(State::class);
    }

    public function address() {
        return $this->belongsTo(CustomerAddress::class, 'customer_address_id');
    }

    public function statusHistories() {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function latestStatus() {        
        return $this->hasOne(OrderStatusHistory::class)->latestOfMany('created_at');
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

     public function color() {
        return $this->belongsTo(Color::class);
    }

    public function size() {
        return $this->belongsTo(Size::class);
    }

    
    // public function latestStatus() {
    //     return $this->hasOne(OrderStatusHistory::class)->latestOfMany('date');
    // }
}
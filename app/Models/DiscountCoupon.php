<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model {
    use HasFactory;

    protected $fillable = [ 'code', 'image', 'name', 'description', 'max_uses', 'max_uses_user', 'type', 
    'discount_amount', 'min_amount', 'status', 'starts_at', 'expires_at' ];

    public function products() {
        return $this->belongsToMany(
            Product::class,
            'coupon_product',
            'discount_coupons_id',
            'product_id'
        );
    }

}
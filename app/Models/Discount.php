<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model {
    use HasFactory;

    protected $fillable = [ 'product_id', 'discount_percentages_id', 'start_date', 'end_date', 'status' ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function percentage() {
        return $this->belongsTo(DiscountPercentage::class, 'discount_percentages_id');
    }
}

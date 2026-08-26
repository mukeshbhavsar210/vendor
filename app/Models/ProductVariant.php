<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model {
    use HasFactory;

    protected $fillable = ['product_id','color_id','image'];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function color() {
        return $this->belongsTo(Color::class);
    }

    public function variant_image() {
        return $this->hasOne(ProductVariant::class, 'id');
    }
}
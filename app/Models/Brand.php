<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model {
    use HasFactory;

    protected $fillable = [ 'name', 'slug', 'image', 'logo', 'status', 'description', 'discount', 'brand_order'];

    public function products() {
        return $this->hasMany(Product::class, 'brand_id');
    }
}

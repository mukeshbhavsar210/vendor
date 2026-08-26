<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model {
    use HasFactory;

    protected $fillable = ['name', 'code'];

    public function products_unique() {
        return $this->hasMany(Product::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'product_sizes', 'size_id', 'product_id');
    }
}

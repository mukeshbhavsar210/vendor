<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'price_type',
        'city',
        'state',
        'pincode',
        'status',
        'is_featured',
        'admin_note',
        'approved_at',
        'views',
        'search_count',
        'rating',
        'total_reviews',
        'meta_title',
        'meta_description',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_featured' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category(){
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    // public function images(){
    //     return $this->hasMany(ServiceImage::class)
    //         ->orderBy('sort_order');
    // }

    public function images(){
        return $this->hasMany(ServiceImage::class, 'service_id')
            ->orderBy('sort_order');
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }    
}

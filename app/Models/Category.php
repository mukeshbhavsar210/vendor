<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'meta_title',
        'meta_description',
        'sort_order',
        'status',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('sort_order');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }

    public function vendors() {
        return $this->belongsToMany(
            Vendor::class,
            'vendor_service_categories',
            'category_id',
            'vendor_id'
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model {
    protected $fillable = [
        'service_id','image','alt_text','title','sort_order','is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // public function service()
    // {
    //     return $this->belongsTo(Service::class);
    // }

    public function service() {
        return $this->belongsTo(Service::class, 'service_id');
    }      

    public function images(){
        return $this->hasMany(ServiceImage::class, 'service_id')
            ->orderBy('sort_order');
    }
}
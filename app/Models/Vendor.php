<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model {
    
    protected $fillable = [
        'user_id','business_name','slug','services','phone','email','logo','address','city','state','pincode','latitude','longitude','status'
    ];

    protected function casts(): array {
        return [
            'services' => 'array',
        ];
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function services(){
        return $this->hasMany(Service::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function categories() {
        return $this->belongsToMany(
            Category::class,
                'vendor_service_categories',
                'vendor_id',
                'category_id'
        );
    }
}
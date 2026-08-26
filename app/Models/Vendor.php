<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model {
    protected $fillable = [
        'user_id','business_name','slug','phone','email','description','logo','cover_image','address','city','state','pincode','latitude','longitude','status'
    ];

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

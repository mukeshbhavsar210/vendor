<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model {
    use HasFactory;

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function service() {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
     protected $fillable = [
        'user_id',
        'service_id',
        'vendor_id',
        'rating',
        'title',
        'comment',
        'status',
        'admin_note',
        'approved_at',
        'vendor_reply',
        'vendor_replied_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'approved_at' => 'datetime',
        'vendor_replied_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}

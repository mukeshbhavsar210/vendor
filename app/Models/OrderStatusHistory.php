<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model {
    use HasFactory;

    protected $fillable = [ 'order_id', 'tracking_number', 'courier', 'note', 'cancel_reason', 'cancel_comments', 'status', 'date' ];

    public function order() {
        return $this->belongsTo(Order::class);
    }
}

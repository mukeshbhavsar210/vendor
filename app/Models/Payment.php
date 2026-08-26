<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    use HasFactory;

     protected $fillable = [ 'payment_id', 'order_id', 'product_id', 'variant_id', 'status', 'amount'
    ];    

    protected $casts = [
        'payment_data' => 'array', // Convert JSON column to array
    ];
}

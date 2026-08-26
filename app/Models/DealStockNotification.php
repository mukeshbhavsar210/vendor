<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealStockNotification extends Model {
    protected $fillable = ['user_id', 'affiliate_product_id', 'notified'];

    use HasFactory;

    public function product(){
        return $this->belongsTo(AffiliateProduct::class, 'affiliate_product_id');
    }
}
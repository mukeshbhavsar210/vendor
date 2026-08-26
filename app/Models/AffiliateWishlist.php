<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateWishlist extends Model {
    protected $fillable = [ 'user_id', 'affiliate_product_id' ];

    public function affiliate_product(){
        return $this->belongsTo(AffiliateProduct::class);
    }

    public function likes(){
        return $this->belongsTo(AffiliateProduct::class, 'id');
    }
    
    use HasFactory;
}

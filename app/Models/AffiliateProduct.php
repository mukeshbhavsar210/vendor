<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateProduct extends Model {
    protected $fillable = [ 'title', 'image', 'affiliate_platform', 'affiliate_url', 'price', 'discounted_percentage', 'views', 'likes', 'status'];

    use HasFactory;
   
}
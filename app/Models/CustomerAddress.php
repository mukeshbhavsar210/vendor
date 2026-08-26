<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model {
    use HasFactory;
    protected $fillable = [ 'user_id', 'address_type', 'default_address', 'name', 'mobile', 'address', 'locality', 'city', 'zip', 'state_id',  ];

    public function state() {
        return $this->belongsTo(State::class, 'state_id');
    }

    // public function state()
    // {
    //     return $this->belongsTo(State::class);
    // }

}

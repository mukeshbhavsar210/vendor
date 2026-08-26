<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ 'name', 'email', 'mobile', 'alternate_mobile', 'birthdate', 'gender', 'role', 'avatar_color,', 'image', 'status', 'password' ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function address() {
        return $this->hasOne(CustomerAddress::class, 'user_id');
    }

    public function addresses() {
        return $this->hasMany(CustomerAddress::class);
    }

    public function getImageUrlAttribute() {
        if (!empty($this->image)) {
            return asset('uploads/user/'.$this->image);
        }

        return asset('admin-assets/img/default-150x150.png');
    }


}

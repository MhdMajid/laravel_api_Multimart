<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Address;
use App\Models\Payment_detail;
use App\Models\Payment;
use App\Models\Shopping_cart;
use App\Models\Wish_list;
use App\Models\Order;
use App\Models\Seller;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function Addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'user_id', 'id');
    }


    public function Payment_details(): HasMany
    {
        return $this->hasMany(Payment_detail::class, 'user_id', 'id');
    }


    public function Payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id', 'id');
    }

    public function Shopping_carts(): HasMany
    {
        return $this->hasMany(Shopping_cart::class, 'user_id', 'id');
    }

    public function Wish_list(): HasMany
    {
        return $this->hasMany(Wish_list::class, 'user_id', 'id');
    }

    public function Orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function Seller(): HasMany
    {
        return $this->hasMany(Seller::class, 'user_id', 'id');
    }

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'gender',
        'email',
        'password',
        'role',
    ];

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
        'password' => 'hashed',
    ];
}

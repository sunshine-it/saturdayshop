<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // 地址栏
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }
    // 收藏商品
    public function favoriteProducts()
    {
        // 定义一个多对多的关联
        return $this->belongsToMany(Product::class, 'user_favorite_products')
                    ->withTimestamps()->orderBy('user_favorite_products.created_at', 'desc');
    }

    // 与购物车的模型关联
    public function cartItems()
    {
        // 一对多
        return $this->hasMany(CartItem::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// 购买车模型
class CartItem extends Model
{
    //
    protected $fillable = ['amount'];
    public $timestamps = false;

    public function user()
    {
        // 一对一
        return $this->belongsTo(User::class);
    }

    public function productSku()
    {
        // 一对一
        return $this->belongsTo(productSku::class);
    }
}

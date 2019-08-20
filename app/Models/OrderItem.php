<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// 订单数据模型
class OrderItem extends Model
{
    //
    protected $fillable = ['amount', 'price', 'rating', 'review', 'reviewed_at'];
    protected $dates = ['reviewed_at'];
    public $timestamps = false;

    public function product()
    {
        // 一对一
        return $this->belongsTo(Product::class);
    }

    public function productSku()
    {
        // 一对一
        return $this->belongsTo(ProductSku::class);
    }

    public function order()
    {
        // 一对一
        return $this->belongsTo(Order::class);
    }
}

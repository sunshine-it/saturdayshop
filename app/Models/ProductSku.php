<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// 产品的 SKU 表模型类
class ProductSku extends Model
{
    //
    protected $fillable = ['title', 'description', 'price', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

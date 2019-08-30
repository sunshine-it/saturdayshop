<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// 商品属性的模型 (用于 Elasticsearch)
class ProductProperty extends Model
{
    protected $fillable = ['name', 'value'];
    // 没有 created_at 和 updated_at 字段
    public $timestamps = false;

    public function product()
    {
        // 一对一
        return $this->belongsTo(Product::class);
    }

}

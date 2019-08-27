<?php

namespace App\Models;

use Illuminate\Support\Str; // 使用访问器
use Illuminate\Database\Eloquent\Model;

// 商品信息表模型类
class Product extends Model
{
    //
    const TYPE_NORMAL = 'normal';
    const TYPE_CROWDFUNDING = 'crowdfunding';
    public static $typeMap = [
        self::TYPE_NORMAL  => '普通商品',
        self::TYPE_CROWDFUNDING => '众筹商品',
    ];

    protected $fillable = [
                    'title', 'description', 'image', 'on_sale',
                    'rating', 'sold_count', 'review_count', 'price', 'type'
    ];
    protected $casts = [
        'on_sale' => 'boolean', // on_sale 是一个布尔类型的字段
    ];
    // 与商品 SKU 关联
    public function skus()
    {
        // 一对多 （一件商品 有多个 sku ）
        return $this->hasMany(ProductSku::class);
    }
    // 商品模型与商品类目模型的关联关系
    public function category()
    {
        // 一对一
        return $this->belongsTo(Category::class);
    }
    // 访问器来输出绝对路径
    public function getImageUrlAttribute()
    {
        // 如果 image 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($this->attributes['image'], ['http://', 'https://'])) {
            return $this->attributes['image'];
        }
        return \Storage::disk('public')->url($this->attributes['image']);
    }
    // 商品对众筹商品的关联关系
    public function crowdfunding()
    {
        return $this->hasOne(CrowdfundingProduct::class);
    }
}

<?php

namespace App\Services;

use Auth;
use App\Models\CartItem;

// 购物车业务逻辑类
class CartService
{
    // 获取 sku
    public function get()
    {
        return Auth::user()->cartItems()->with(['productSku.product'])->get();
    }

    // 添加商品到购物车
    public function add($skuId, $amount)
    {
        $user = Auth::user();
        // 从数据库中查询该商品是否已经在购物车中
        if ($cart = $user->cartItems()->where('product_sku_id', $skuId)->first()) {
            // 如果存在则直接叠加商品数量
            $cart->update([
                'amount' => $cart->amount + $amount,
            ]);
        }
        else {
            // 否则创建一个新的购物车记录
            $cart = new CartItem(['amount' => $amount]);
            $cart->user()->associate($user);
            $cart->productSku()->associate($skuId);
            $cart->save();
        }
        return $cart;
    }

    // 从购物车中移除商品
    public function remove($skuIds)
    {
        // 可以传单个 ID，也可以传 ID 数组
        if (!is_array($skuIds)) {
            $skuIds = [$skuIds];
        }
        Auth::user()->cartItems()->whereIn('product_sku_id', $skuIds)->delete();
    }

}

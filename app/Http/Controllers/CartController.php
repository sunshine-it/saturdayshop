<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartRequest;
use Illuminate\Http\Request;
use App\Models\ProductSku;
use App\Services\CartService;

// 购物车类
class CartController extends Controller
{
    protected $cartService;

    // 利用 Laravel 的自动解析功能注入 CartService 类
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    // 添加购物车
    public function add(AddCartRequest $request)
    {
        $this->cartService->add($request->input('sku_id'), $request->input('amount'));
        return [];
    }

    //  购物车列表
    public function index(Request $request)
    {
        $cartItems = $this->cartService->get();
        // 地址
        $addresses = $request->user()->addresses()->orderBy('last_used_at', 'desc')->get();
        return view('cart.index', ['cartItems' => $cartItems, 'addresses' => $addresses]);
    }

    // 从购物车中移除商品
    public function remove(productSku $sku, Request $request)
    {
        $this->cartService->remove($sku->id);
        return [];
    }
}

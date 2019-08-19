<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

// 商品列表类
class ProductsController extends Controller
{
    // 商品列表
    public function index(Request $request)
    {
        // 未上架的商品就不会被展示出来
        $products = Product::query()->where('on_sale', true)->paginate(16);
        return view('products.index', ['products' => $products]);
    }
}

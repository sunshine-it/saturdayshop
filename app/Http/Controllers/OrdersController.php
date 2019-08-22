<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\UserAddress;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;

// 订单类
class OrdersController extends Controller
{
    // 处理订单
    public function store(OrderRequest $request, OrderService $orderService)
    {
        $user    = $request->user();
        $address = UserAddress::find($request->input('address_id'));

        return $orderService->store($user, $address, $request->input('remark'), $request->input('items'));
    }

    // 订单列表
    public function index(Request $request)
    {
        // 使用 with 方法预加载，避免N + 1问题
        $orders = Order::query()->with(['items.product', 'items.productSku'])
                    ->where('user_id', $request->user()->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate();
        return view('orders.index', ['orders' => $orders]);
    }

    // 订单详情页
    public function show(Order $order, Request $request)
    {
        $this->authorize('own', $order);
        //  load() 方法 延迟预加载
        return view('orders.show', ['order' => $order->load(['items.productSku', 'items.product'])]);
    }
}

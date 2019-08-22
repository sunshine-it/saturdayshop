<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\ProductSku;
use App\Models\UserAddress;
use App\Models\Order;
use Carbon\Carbon;
use App\Exceptions\InvalidRequestException;
use App\Jobs\CloseOrder;
use Illuminate\Http\Request;

// 订单类
class OrdersController extends Controller
{
    //
    public function store(OrderRequest $request)
    {
        $user = $request->user();
        // 开启一个数据库事务
        $order = \DB::transaction(function () use ($user, $request) {
            $address = UserAddress::find($request->input('address_id'));
            // 更新此地址的最后使用时间
            $address->update(['last_used_at' => Carbon::now()]);
            // 创建一个订单
            $order = new Order([
                'address'      => [
                    'address'       => $address->full_address,
                    'zip'           => $address->zip,
                    'contact_name'  => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark'       => $request->input('remark'),
                'total_amount' => 0,
            ]);
            // 订单关联到当前用户
            $order->user()->associate($user);
            // 写入数据库
            $order->save();

            $totalAmount = 0;
            $items       = $request->input('items');
            // 遍历用户提交的 SKU
            foreach ($items as $data) {
                $sku    = ProductSku::find($data['sku_id']);
                // 创建一个 OrderItem 并直接与当前订单关联
                $item   = $order->items()->make([
                    'amount'  =>  $data['amount'],
                    'price'   =>  $sku->price,
                ]);
                $item->product()->associate($sku->product_id);
                $item->productSku()->associate($sku);
                $item->save();
                $totalAmount += $sku->price * $data['amount'];
                // 如果减库存失败则抛出异常，由于这块代码是在 DB::transaction() 中执行的，
                // 因此抛出异常时会触发事务的回滚，之前创建的 orders 和 order_items 记录都会被撤销
                if ($sku->decreaseStock($data['amount']) <= 0) {
                    throw new InvalidRequestException('该商品库存不足');
                }
            }
            // 更新订单总金额
            $order->update(['total_amount' => $totalAmount]);
            // 将下单的商品从购物车中移除 | collect() 辅助函数快速取得所有 SKU ID
            $skuIds = collect($items)->pluck('sku_id');
            $user->cartItems()->whereIn('product_sku_id', $skuIds)->delete();
            return $order;
        });
        // 触发 job 任务执行队列
        $this->dispatch(new CloseOrder($order, config('app.order_ttl')));
        return $order;
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

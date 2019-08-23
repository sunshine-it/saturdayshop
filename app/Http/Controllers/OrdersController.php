<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\UserAddress;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Exceptions\InvalidRequestException;
use Carbon\Carbon;
use App\Http\Requests\SendReviewRequest;
use App\Events\OrderReviewed;

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

    // 确认收货
    public function received(Order $order, Request $request)
    {
        // 校验权限
        $this->authorize('own', $order);
        // 判断订单的发货状态是否为已发货
        if ($order->ship_status !== Order::SHIP_STATUS_DELIVERED) {
            throw new InvalidRequestException('发货状态不正确');
        }

        // 更新发货状态为已收到
        $order->update(['ship_status' => Order::SHIP_STATUS_RECEIVED]);
        // 由于把确认收货的操作从表单提交改成了 AJAX 请求，因此控制器中的返回值需要修改一下
        // 返回订单信息
        return $order;
    }

    // 展示评价页面
    public function review(Order $order)
    {
        // 校验权限
        $this->authorize('own', $order);
        // 判断是否已经支付
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未支付，不可评价');
        }
        // 使用 load 方法加载关联数据，避免 N + 1 性能问题
        return view('orders.review', ['order' => $order->load(['items.productSku', 'items.product'])]);
    }

    // 提交评价
    public function sendReview(Order $order, SendReviewRequest $request)
    {
        // 校验权限
        $this->authorize('own', $order);
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未支付，不可评价');
        }
        // 判断是否已经评价
        if ($order->reviewed) {
            throw new InvalidRequestException('该订单已评价，不可重复提交');
        }
        $reviews = $request->input('reviews');
        // 开启事务
        \DB::transaction(function () use ($reviews, $order) {
            // 遍历用户提交的数据
            foreach ($reviews as $review) {
                $orderItem = $order->items()->find($review['id']);
                // 保存评分和评价
                $orderItem->update([
                    'rating'      => $review['rating'],
                    'review'      => $review['review'],
                    'reviewed_at' => Carbon::now(),
                ]);
            }
            // 将订单标记为已评价
            $order->update(['reviewed' => true]);
            event(new OrderReviewed($order)); // 使用订单已评价 OrderReviewed 事件
        });

        return redirect()->back();
    }
}

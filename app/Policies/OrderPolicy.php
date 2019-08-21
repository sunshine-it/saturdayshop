<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

// 订单策略
class OrderPolicy
{
    use HandlesAuthorization;

    public function own(User $user, Order $order)
    {
        return $order->user_id == $user->id;
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\UserAddress;

// 用户地址策略类
class UserAddressPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    // 地址策略方法（当前用户只能操作自己的地址）
    public function own(User $user, UserAddress $address)
    {
        return $address->user_id == $user->id;
    }
}

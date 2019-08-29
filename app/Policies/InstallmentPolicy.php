<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Installment;
use Illuminate\Auth\Access\HandlesAuthorization;

// 分期付款的策略
class InstallmentPolicy
{
    use HandlesAuthorization;

    // 对应的用户才能看到
    public function own(User $user, Installment $installment)
    {
        return $installment->user_id == $user->id;
    }
}

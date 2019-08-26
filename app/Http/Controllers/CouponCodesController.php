<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CouponCode;
use Carbon\Carbon;

// 用户界面-检查优惠卷
class CouponCodesController extends Controller
{
    //
    public function show($code)
    {
        // 判断优惠券是否存在
        if (!$record = CouponCode::where('code', $code)->first()) {
            // 其中 abort() 方法可以直接中断程序的运行，接受的参数会变成 Http 状态码返回给用户
            abort(404);
        }

        // 如果优惠券没有启用，则等同于优惠券不存在
        if (!$record->enabled) {
            abort(404);
        }

        if ($record->total - $record->used <= 0) {
            return response()->json(['msg' => '该优惠券已被兑完'], 403);
        }

        if ($record->not_before && $record->not_before->gt(Carbon::now())) {
            return response()->json(['msg' => '该优惠券现在还不能使用'], 403);
        }

        if ($record->not_after && $record->not_after->lt(Carbon::now())) {
            return response()->json(['msg' => '该优惠券已过期'], 403);
        }
        return $record;
    }

}

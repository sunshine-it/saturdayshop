<?php

namespace App\Http\Requests;

// 申请退款类
class ApplyRefundRequest extends Request
{
    public function rules()
    {
        return [
            'reason' => 'required',
        ];
    }
    public function attributes()
    {
        return [
            'reason' => '原因',
        ];
    }
}

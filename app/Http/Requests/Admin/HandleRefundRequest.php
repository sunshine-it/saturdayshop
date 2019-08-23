<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

// 管理后台 - 拒绝退款类
class HandleRefundRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'agree'  => ['required', 'boolean'],
            'reason' => ['required_if:agree,false'],  // 拒绝退款时需要输入拒绝理由
        ];
    }
}

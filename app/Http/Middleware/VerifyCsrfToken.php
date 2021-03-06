<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // 服务器端回调地址加入 CSRF 校验白名单
        'payment/alipay/notify',
        'payment/wechat/notify',
        'payment/wechat/refund_notify',
        'installments/alipay/notify',
        'installments/wechat/notify',
        'installments/wechat/refund_notify',
    ];
}

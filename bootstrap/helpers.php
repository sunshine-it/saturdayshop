<?php

if ( ! function_exists('route_class')) {
    // 此方法会将当前请求的路由名称转换为 CSS 类名称，作用是允许我们针对某个页面做页面样式定制
    function route_class() {
        // str_replace() 该函数返回一个字符串或者数组。该字符串或数组是将 subject 中全部的 '.' 都被 '-' 替换之后的结果
        return str_replace('.', '-', Route::currentRouteName());
    }
}

if (! function_exists('ngrok_url')) {
    function ngrok_url($routeName, $parameters = [])
    {
        // 开发环境，并且配置了 NGROK_URL
        if(app()->environment('local') && $url = config('app.ngrok_url')) {
            // route() 函数第三个参数代表是否绝对路径
            return $url.route($routeName, $parameters, false);
        }

        return route($routeName, $parameters);
    }
}

if (! function_exists('big_number')) {
    // 默认的精度为小数点后两位
    function big_number($number, $scale = 2)
    {
        return new \Moontoast\Math\BigNumber($number, $scale);
    }
}

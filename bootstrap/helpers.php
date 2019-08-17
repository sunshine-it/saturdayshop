<?php

if ( ! function_exists('route_class')) {
    // 此方法会将当前请求的路由名称转换为 CSS 类名称，作用是允许我们针对某个页面做页面样式定制
    function route_class() {
        // str_replace() 该函数返回一个字符串或者数组。该字符串或数组是将 subject 中全部的 '.' 都被 '-' 替换之后的结果
        return str_replace('.', '-', Route::currentRouteName());
    }
}

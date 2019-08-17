<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 自定义页面逻辑控制器类
class PagesController extends Controller
{
    // 使用 root() 方法来处理首页的展示
    public function root()
    {
        return view('pages.root');
    }
}

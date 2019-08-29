<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Installment;
// 分期付款类
class InstallmentsController extends Controller
{
    // 分期付款列表页
    public function index(Request $request)
    {
        $installments = Installment::query()
            ->where('user_id', $request->user()->id)
            ->paginate(10);

        return view('installments.index', ['installments' => $installments]);
    }
}

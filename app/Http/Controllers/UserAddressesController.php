<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Http\Requests\UserAddressRequest;

class UserAddressesController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('user_addresses.index', ['addresses' => $request->user()->addresses,]);
    }

    // 新增收货地址页面
    public function create()
    {
        // 由于新增页面和编辑页面比较类似，所以共用一个模板文件 create_and_edit
        return view('user_addresses.create_and_edit', ['address' => new UserAddress()]);
    }
    public function store(UserAddressRequest $request)
    {
        $request->user()->addresses()->create($request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]));

        return redirect()->route('user_addresses.index');
    }
}

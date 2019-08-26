<?php

use Illuminate\Database\Seeder;

// 优惠券假数据种子类
class CouponCodesSeeder extends Seeder
{
    public function run()
    {
        //
        factory(\App\Models\CouponCode::class, 20)->create();
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrdersAddType extends Migration
{
    // 在订单表中添加一个 type 字段来表明这个订单是普通商品订单还是众筹商品订单
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('type')->after('id')->default(\App\Models\Order::TYPE_NORMAL);
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}

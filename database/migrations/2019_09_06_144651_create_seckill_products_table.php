<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// 秒杀数据结构类
class CreateSeckillProductsTable extends Migration
{
    // 执行迁移
    public function up()
    {
        Schema::create('seckill_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
        });
    }

    // 回滚迁移
    public function down()
    {
        Schema::dropIfExists('seckill_products');
    }
}

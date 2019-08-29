<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// 还款计划表
class CreateInstallmentItemsTable extends Migration
{
    public function up()
    {
        Schema::create('installment_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('installment_id');
            $table->foreign('installment_id')->references('id')->on('installments')->onDelete('cascade');
            $table->unsignedInteger('sequence');
            $table->decimal('base');
            $table->decimal('fee');
            $table->decimal('fine')->nullable();
            $table->dateTime('due_date');
            $table->dateTime('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_no')->nullable();
            $table->string('refund_status')->default(\App\Models\InstallmentItem::REFUND_STATUS_PENDING);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('installment_items');
    }
}

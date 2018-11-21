<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('sn')->comment('订单编号');
            $table->unsignedInteger('price')->comment('实付款(分)');
            $table->unsignedInteger('payable_price')->comment('应付款(分)');
            $table->unsignedInteger('items_price')->comment('订单金额(分)');
            $table->unsignedInteger('freight')->comment('运费(分)');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态0待付款1待发货2待收货3待评价4已完成');
            $table->unsignedTinyInteger('refund')->default(0)->comment('退款0关闭1开启');
            $table->string('remark')->nullable()->comment('备注');
            $table->string('express_type')->nullable()->comment('快递公司类型');
            $table->string('tracking_no')->nullable()->comment('快递单号');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique('sn');
            $table->index(['user_id', 'status']);
            $table->index('refund');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

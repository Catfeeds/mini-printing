<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('user_id');
            $table->string('sn')->comment('退款编号');
            $table->string('reason')->comment('退款原因');
            $table->unsignedInteger('require_price')->comment('要求退款金额(分)');
            $table->unsignedInteger('price')->default(0)->comment('实际退款金额(分)');
            $table->string('describe')->nullable()->comment('退款说明');
            $table->unsignedInteger('status')->default(1)->comment('状态0取消退款1申请中2卖家同意3卖家退款4退款成功5卖家拒绝');
            $table->string('refuse_reason')->nullable()->comment('拒绝理由');
            $table->timestamps();
            $table->unique('order_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_refunds');
    }
}

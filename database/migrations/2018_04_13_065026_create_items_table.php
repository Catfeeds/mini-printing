<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->comment('分类ID');
            $table->string('sn', 100)->comment('商品编号');
            $table->string('title')->comment('产品名称');
            $table->unsignedInteger('price')->comment('价格(分)');
            $table->unsignedInteger('original_price')->comment('原价(分)');
            $table->unsignedInteger('freight')->comment('运费(分)');
            $table->unsignedInteger('stock')->comment('库存');
            $table->unsignedInteger('sales_volume')->default(0)->comment('销量');
            $table->longText('details')->comment('产品详情');
            $table->unsignedInteger('order')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态0下架1上架');
            $table->timestamps();
            $table->softDeletes();
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNavigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('icon')->comment('图标');
            $table->string('title')->comment('标题');
            $table->string('type')->comment('类型');
            $table->string('target')->default('')->comment('目标');
            $table->unsignedInteger('order')->default(0)->commnet('排序');
            $table->unsignedTinyInteger('status')->comment('是否启用0否1是');
            $table->timestamps();
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
        Schema::dropIfExists('navigations');
    }
}

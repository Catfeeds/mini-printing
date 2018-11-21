<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('star')->default(5)->comment('评星1~5');
            $table->text('message');
            $table->unsignedTinyInteger('read')->default(0)->comment('已读状态0未读1已读');
            $table->timestamps();
            $table->index('item_id');
            $table->index('order_id');
            $table->index('read');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}

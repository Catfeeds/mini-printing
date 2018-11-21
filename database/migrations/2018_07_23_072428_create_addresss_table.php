<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddresssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresss', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户编号');
            $table->string('national_code',20)->comment('国家编码');
            $table->string('postal_code',20)->comment('邮政编码');
            $table->string('tel',20)->comment('手机号码');
            $table->string('province',20)->comment('省份');
            $table->string('city',20)->comment('城市');
            $table->string('county',20)->comment('区域');
            $table->string('detail',200)->comment('详细地址');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresss');
    }
}

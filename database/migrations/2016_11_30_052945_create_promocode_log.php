<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromocodeLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocode_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('promo_code');
            $table->integer('promo_code_id'); // FK
            $table->integer('order_id'); // FK
            $table->integer('user_id');// FK
            $table->integer('product_id');// FK
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
        Schema::drop('promocode_log');
    }
}

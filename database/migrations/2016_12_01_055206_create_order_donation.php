<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDonation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_donation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id');
            $table->integer('order_id');
            $table->integer('user_id');
            $table->float('amout');
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
        Schema::drop('order_donation');
    }
}

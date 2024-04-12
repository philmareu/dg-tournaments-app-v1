<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderRegistrationProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_registration_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tournament_order_registration_id');
            $table->unsignedInteger('registration_product_id');
            $table->unsignedInteger('cost');
            $table->text('data');
            $table->timestamps();

            $table->foreign('tournament_order_registration_id', 'or_r')->references('id')->on('order_registrations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('registration_product_id', 'or_rp')->references('id')->on('registration_products')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_registration_products');
    }
}

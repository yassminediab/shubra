<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrders2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('transaction_id');
            $table->float('price')->default(0);
            $table->integer('total_items')->default(0);
            $table->float('weight')->default(0);
            $table->float('discount')->default(0);
            $table->float('total_item_price')->default(0);
            $table->float('delivery_fee')->default(0);
            $table->float('total_price')->default(0);
            $table->dateTime('delivery_time')->nullable();
            $table->string('payment_method');
            $table->unsignedBigInteger('address_id');
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->string('current_status')->default('confirmed');
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
        Schema::dropIfExists('orders');
    }
}

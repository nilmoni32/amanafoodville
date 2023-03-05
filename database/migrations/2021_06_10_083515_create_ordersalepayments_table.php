<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersalepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordersalepayments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordersale_id')->nullable();
            $table->enum('payment_method', ['cash','card','mobile'])->default('cash');
            $table->string('bank_name')->nullable();
            $table->decimal('store_paidamount', 12, 6)->nullable(); // Amount to be stored after the exchange or customer paid actual amount when no exchange is made.
            $table->decimal('cash_exchange', 12, 6); // exchange amount. 
            $table->decimal('customer_paid_amount', 12, 6); // customer actual paid amount
            $table->decimal('card_discount', 12, 6)->nullable(); 
            $table->decimal('mobile_discount', 12, 6)->nullable(); 
            $table->timestamps();

            $table->foreign('ordersale_id')->references('id')->on('ordersales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordersalepayments');
    }
}

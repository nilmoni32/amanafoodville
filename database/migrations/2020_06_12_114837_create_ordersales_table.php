<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordersales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_id');  
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('director_id')->nullable(); //discount reference
            $table->string('order_number')->unique();
            $table->decimal('grand_total', 13, 6)->nullable();
            $table->string('order_date');
            $table->string('order_tableNo')->nullable();
            $table->enum('status', ['receive', 'delivered', 'cancel' ])->default('receive');
            $table->decimal('discount', 12, 6)->nullable(); // reference discount
            $table->decimal('reward_discount', 12, 6)->nullable();             
            $table->string('payment_method')->nullable(); // to store multiple values such as cash, card and mobile banking
            $table->decimal('cash_pay', 13, 6)->nullable();
            $table->decimal('card_pay', 13, 6)->nullable();
            $table->decimal('mobile_banking_pay', 13, 6)->nullable();
            $table->decimal('card_discount')->nullable(); // card discount
            $table->decimal('mobile_discount')->nullable(); //  mobile bank means bkash, nagad discount
            $table->decimal('fraction_discount')->nullable(); // means if duetotal is 333.78 then fraction discount will store .78 tk
            $table->string('gpstarmobile_no')->nullable();
            $table->decimal('gpstar_discount')->nullable();

            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins'); 
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('director_id')->references('id')->on('directors');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordersales');
    }
}

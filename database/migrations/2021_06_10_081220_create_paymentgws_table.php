<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentgwsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentgws', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name')->nullable();
            $table->enum('bank_type', ['card', 'mobile'])->default('card');
            $table->string('discount_percent')->nullable();
            $table->decimal('discount_upper_limit', 12, 6)->nullable();  
            $table->decimal('discount_lower_limit', 12, 6)->nullable();
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
        Schema::dropIfExists('paymentgws');
    }
}

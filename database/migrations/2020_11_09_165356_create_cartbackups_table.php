<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartbackupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cartbackups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');           
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('product_attribute_id')->nullable();             
            $table->string('ip_address')->nullable();
            $table->integer('product_quantity')->default(1);           
            $table->integer('has_attribute')->default(0);
            $table->decimal('unit_price', 8, 2)->nullable();
            $table->integer('order_cancel')->default(0);
            $table->decimal('production_food_cost', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('user_id')
            ->references('id')
            ->on('users');            

            $table->foreign('product_id')
            ->references('id')
            ->on('products');

            $table->foreign('order_id')
            ->references('id')
            ->on('orders')
            ->onDelete('cascade');

            $table->foreign('product_attribute_id')
            ->references('id')
            ->on('product_attributes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cartbackups');
    }
}

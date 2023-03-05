<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * This is a POS CART
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id'); 
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('ordersale_id')->nullable(); 
            $table->string('product_name')->nullable();
            $table->decimal('product_quantity', 8, 2)->default(1.00);
            $table->decimal('unit_price', 8, 2)->nullable();
            $table->decimal('production_food_cost', 8, 2)->nullable();
            $table->integer('order_cancel')->default(0); 
            $table->string('order_tbl_no')->nullable();
            
            $table->timestamps();

            $table->foreign('admin_id')
            ->references('id')
            ->on('admins');            

            $table->foreign('product_id')
            ->references('id')
            ->on('products')
            ->onDelete('cascade');

            $table->foreign('ordersale_id')
            ->references('id')
            ->on('ordersales')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}

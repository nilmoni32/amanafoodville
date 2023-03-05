<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuffetsalebackupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buffetsalebackups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buffet_id'); 
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('buffetorder_id')->nullable(); 
            $table->string('product_name')->nullable();
            $table->decimal('product_quantity', 8, 2)->default(1.00);
            $table->decimal('unit_price', 8, 2)->nullable();
            $table->decimal('production_food_cost', 8, 2)->nullable();
            $table->integer('order_cancel')->default(0); 
            $table->string('order_tbl_no')->nullable();

            $table->foreign('buffet_id')->references('id')->on('buffets');
            $table->foreign('product_id')->references('id')->on('products');            
	        $table->foreign('admin_id')->references('id')->on('admins'); 
            $table->foreign('buffetorder_id')->references('id')->on('buffetorders');
            
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
        Schema::dropIfExists('buffetsalebackups');
    }
}

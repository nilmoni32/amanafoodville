<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplimentarysalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complimentarysales', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('product_id'); 
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('complimentary_ordersales_id')->nullable(); 
            $table->string('product_name')->nullable();
            $table->decimal('product_quantity', 8, 2)->default(1.00);
            $table->decimal('unit_price', 8, 2)->nullable();
            
            $table->timestamps();

            $table->foreign('admin_id')
            ->references('id')
            ->on('admins');            

            $table->foreign('product_id')
            ->references('id')
            ->on('products')
            ->onDelete('cascade');

            $table->foreign('complimentary_ordersales_id')
            ->references('id')
            ->on('complimentary_ordersales')
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
        Schema::dropIfExists('complimentarysales');
    }
}

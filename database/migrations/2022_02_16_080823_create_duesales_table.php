<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDuesalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
	Schema::create('duesales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable(); 
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('dueordersale_id')->nullable(); 
            $table->string('product_name')->nullable();
            $table->decimal('product_quantity', 8, 2)->default(1.00);
            $table->decimal('unit_price', 8, 2)->nullable();            
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

            $table->foreign('dueordersale_id')
            ->references('id')
            ->on('dueordersales')
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
        Schema::dropIfExists('duesales');
    }
}

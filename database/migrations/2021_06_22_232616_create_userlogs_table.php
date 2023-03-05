<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userlogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable(); 
            $table->unsignedBigInteger('product_id')->nullable(); 
            $table->string('done_by')->nullable();
            $table->string('log_type')->nullable();
            $table->string('log_date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins'); 
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userlogs');
    }
}

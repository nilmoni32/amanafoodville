<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplimentaryOrdersalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complimentary_ordersales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('order_number')->unique();
            $table->decimal('grand_total', 13, 6)->nullable();
            $table->string('order_date');
            $table->text('notes')->nullable();
            
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins'); 
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complimentary_ordersales');
    }
}

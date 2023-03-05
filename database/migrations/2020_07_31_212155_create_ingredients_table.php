<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('typeingredient_id')->index();           
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('total_quantity',8,2)->default(0.0);
            $table->decimal('total_price', 8, 2)->default(0.0);
            $table->decimal('alert_quantity',8,2)->default(0.0);
            $table->string('measurement_unit');
            $table->string('smallest_unit');
            $table->decimal('smallest_unit_price',8, 3)->default(0.0); 
            $table->boolean('status')->default(1);  // checking ingredient availability 
            $table->string('pic')->nullable();
            
            $table->foreign('typeingredient_id')->references('id')->on('typeingredients')->onDelete('cascade');

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
        Schema::dropIfExists('ingredients');
    }
}

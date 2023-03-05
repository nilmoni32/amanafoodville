<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientDamagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredient_damages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ingredient_id')->index(); 
            $table->string('name')->nullable();
            $table->decimal('quantity',8,2);
            $table->string('unit');
            $table->decimal('price', 8, 2);
            $table->string('reported_by')->nullable();
            $table->datetime('reported_date')->nullable();
            
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
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
        Schema::dropIfExists('ingredient_damages');
    }
}

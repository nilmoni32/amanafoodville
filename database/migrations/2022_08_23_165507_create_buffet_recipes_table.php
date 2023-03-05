<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuffetRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buffet_recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buffet_id')->index();    
            $table->unsignedBigInteger('recipe_id')->index();
            $table->decimal('recipe_cost_price', 8, 3)->nullable();
            $table->decimal('recipe_sale_price', 8, 3)->nullable();
            $table->foreign('buffet_id')->references('id')->on('buffets')->onDelete('cascade');            
            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
            
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
        Schema::dropIfExists('buffet_recipes');
    }
}

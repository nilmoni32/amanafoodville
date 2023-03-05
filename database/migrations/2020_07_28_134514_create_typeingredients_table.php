<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeingredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('typeingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); //url
            $table->text('description')->nullable();
            // we will store the parent category id to make a nested tree of categories 
            $table->unsignedInteger('parent_id')->default(1)->nullable();           
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
        Schema::dropIfExists('typeingredients');
    }
}

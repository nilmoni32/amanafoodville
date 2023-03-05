<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category_name');
            $table->string('slug')->unique(); //url
            $table->text('description')->nullable();
            // we will store the parent category id to make a nested tree of categories 
            $table->unsignedInteger('parent_id')->default(1)->nullable();
            // provide us some control to show or hide a category in the main navigation. 
            $table->boolean('menu')->default(1); 
            $table->string('image')->nullable();
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
        Schema::dropIfExists('categories');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredient_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ingredient_id')->index(); 
            $table->string('name')->nullable(); 
            $table->datetime('purchase_date');
            $table->datetime('expire_date');
            $table->decimal('quantity',8,2);
            $table->string('unit');
            $table->decimal('price', 8, 2);
            $table->string('added_by')->nullable(); 

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
        Schema::dropIfExists('ingredient_purchases');
    }
}

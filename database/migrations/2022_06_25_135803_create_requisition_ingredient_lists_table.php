<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisitionIngredientListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('requisition_ingredient_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisition_to_supplier_id')->nullable();            
            $table->unsignedBigInteger('supplier_stock_id')->nullable(); //supplier stock id
            $table->string('name');
            $table->string('unit');
            $table->decimal('unit_cost', 8, 2);
            $table->decimal('quantity',8,2);
            $table->decimal('stock',8,2);  // supplier stock
            $table->decimal('total', 8, 2); // unit_cost * quantity
            $table->foreign('requisition_to_supplier_id')->references('id')->on('requisition_to_suppliers')->onDelete('cascade');
            $table->foreign('supplier_stock_id')->references('id')->on('supplier_stocks');
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
        Schema::dropIfExists('requisition_ingredient_lists');
    }
}

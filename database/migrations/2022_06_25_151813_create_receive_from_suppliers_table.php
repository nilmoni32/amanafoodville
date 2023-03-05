<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiveFromSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receive_from_suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->index();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('requisition_to_supplier_id')->nullable();
            $table->string('chalan_no')->unique();  //supplier provided #(no) against the requisition number
            $table->datetime('chalan_date')->nullable();
            $table->datetime('payment_date')->nullable();
            $table->datetime('requisition_date')->nullable();
            $table->datetime('expected_delivery')->nullable();
            $table->string('purpose',191)->nullable();
            $table->string('remarks',191)->nullable();            
            $table->decimal('applyDiscount', 10, 6)->nullable();
            $table->integer('totalFreeQuantity')->default(1);
            $table->decimal('total_quantity',8,2)->default(1.0);
            $table->decimal('total_amount', 13, 6)->nullable();
            
            $table->foreign('admin_id')->references('id')->on('admins'); 
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('requisition_to_supplier_id')->references('id')->on('requisition_to_suppliers');
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
        Schema::dropIfExists('receive_from_suppliers');
    }
}

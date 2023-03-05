<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnToSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('return_to_suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->index();
            $table->unsignedBigInteger('receive_from_supplier_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->datetime('chalan_date')->nullable();
            $table->string('purpose',191)->nullable();
            $table->string('remarks',191)->nullable();
            $table->decimal('total_quantity',8,2)->default(1.0);
            $table->decimal('total_amount', 13, 6)->nullable();

            $table->foreign('admin_id')->references('id')->on('admins'); 
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('receive_from_supplier_id')->references('id')->on('receive_from_suppliers');
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
        Schema::dropIfExists('return_to_suppliers');
    }
}

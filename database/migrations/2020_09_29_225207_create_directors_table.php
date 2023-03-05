<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile'); 
            $table->string('email')->nullable(); 
            $table->enum('ref_type', ['Management', 'ShareHolder', 'Employee', 'other'])->default('other');
            $table->string('discount_slab_percentage')->nullable();    
            $table->decimal('discount_upper_limit', 12, 6)->nullable();        
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
        Schema::dropIfExists('directors');
    }
}

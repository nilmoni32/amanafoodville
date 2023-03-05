<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGpstardiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gpstardiscounts', function (Blueprint $table) {
            $table->id();
            $table->string('gp_star_name')->nullable();            
            $table->string('discount_percent')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->decimal('discount_upper_limit', 12, 6)->nullable();  
            $table->decimal('discount_lower_limit', 12, 6)->nullable();
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
        Schema::dropIfExists('gpstardiscounts');
    }
}

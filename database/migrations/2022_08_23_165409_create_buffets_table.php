<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuffetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buffets', function (Blueprint $table) {
            $table->id();
            $table->string('buffet_name');
            $table->decimal('unit_cost_price', 8, 2)->nullable();
            $table->decimal('unit_sale_price', 8, 2)->nullable();
            $table->integer('buffet_guest_list')->nullable();
            $table->integer('buffet_guest_list_served')->nullable();
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
        Schema::dropIfExists('buffets');
    }
}

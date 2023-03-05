<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDueordersaleIdToSalebackupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      
	Schema::table('salebackups', function (Blueprint $table) {
            $table->unsignedBigInteger('dueordersale_id')->nullable()->after('ordersale_id');
            $table->foreign('dueordersale_id')->references('id')->on('dueordersales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
	Schema::table('salebackups', function (Blueprint $table) {
            //Drop foreign key constraints
            $table->dropForeign(['dueordersale_id']);
            //Drop the column
            $table->dropColumn('dueordersale_id');
        });
    }
}

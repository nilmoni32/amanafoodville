<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDueordersaleIdAndCustomerDueToOrdersalepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
	Schema::table('ordersalepayments', function (Blueprint $table) {
            $table->unsignedBigInteger('dueordersale_id')->nullable()->after('ordersale_id');
            $table->string('customer_due')->nullable()->after('cash_exchange');

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
        Schema::table('ordersalepayments', function (Blueprint $table) {
            //Drop foreign key constraints
            $table->dropForeign(['dueordersale_id']);
            //Drop the column
            $table->dropColumn(['dueordersale_id', 'customer_due']);
        });
    }
}

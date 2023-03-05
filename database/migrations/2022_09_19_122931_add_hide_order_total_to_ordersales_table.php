<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHideOrderTotalToOrdersalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordersales', function (Blueprint $table) {
            $table->decimal('hide_order_total', 13, 6)->default('9999999.000000')->after('vat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordersales', function (Blueprint $table) {
            $table->dropColumn('hide_order_total');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFbaInboundShipmentsAddPalletCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fba_inbound_shipments', function (Blueprint $table) {
            $table->integer('pallet_count')->nullable();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fba_inbound_shipments', function (Blueprint $table) {
            $table->dropColumn('pallet_count');
        });
    }
}

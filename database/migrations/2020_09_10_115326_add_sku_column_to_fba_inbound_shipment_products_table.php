<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddSkuColumnToFbaInboundShipmentProductsTable
 */
class AddSkuColumnToFbaInboundShipmentProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fba_inbound_shipment_products', function (Blueprint $table) {
            $table->string('sku')
                ->after('fba_inbound_shipment_id');
            $table->string('labeled_by')
                ->after('quantity_in_case');
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
            $table->dropColumn('sku');
            $table->dropColumn('labeled_by');
        });
    }
}

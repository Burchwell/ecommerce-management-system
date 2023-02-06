<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class UpdateProductsTable
 */
class UpdateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('mfn_fulfillable_qty')->nullable()->after('price');

            $table->integer('afn_warehouse_qty')->nullable()->after('mfn_fulfillable_qty');
            $table->integer('afn_unsellable_qty')->nullable()->after('afn_warehouse_qty');
            $table->integer('afn_reserved_qty')->nullable()->after('afn_unsellable_qty');
            $table->integer('afn_total_qty')->nullable()->after('afn_reserved_qty');
            $table->integer('afn_per_unit_volume')->nullable()->after('afn_total_qty');
            $table->integer('afn_inbound_working_qty')->nullable()->after('afn_per_unit_volume');
            $table->integer('afn_inbound_shipped_qty')->nullable()->after('afn_inbound_working_qty');
            $table->integer('afn_inbound_receiving_qty')->nullable()->after('afn_inbound_shipped_qty');
            $table->integer('afn_researching_qty')->nullable()->after('afn_inbound_receiving_qty');
            $table->integer('afn_reserved_future_supply')->nullable()->after('afn_researching_qty');
            $table->integer('afn_future_supply_buyable')->nullable()->after('afn_reserved_future_supply');

            $table->integer('fba_reserved_customer_order')->nullable()->after('afn_future_supply_buyable');
            $table->integer('fba_reserved_fc_transfer')->nullable()->after('fba_reserved_customer_order');
            $table->integer('fba_reserved_fc_processing')->nullable()->after('fba_reserved_fc_transfer');

            $table->integer('total_fees_estimate')->nullable()->after('fba_reserved_fc_processing');
            $table->integer('referral_fee')->nullable()->after('total_fees_estimate');
            $table->integer('variable_closing_fee')->nullable()->after('referral_fee');
            $table->integer('per_item_fee')->nullable()->after('variable_closing_fee');
            $table->integer('fba_fees')->nullable()->after('per_item_fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('mfn_fulfillable_qty');
            $table->dropColumn('afn_warehouse_qty');
            $table->dropColumn('afn_unsellable_qty');
            $table->dropColumn('afn_reserved_qty');
            $table->dropColumn('afn_total_qty');
            $table->dropColumn('afn_per_unit_volume');
            $table->dropColumn('afn_inbound_working_qty');
            $table->dropColumn('afn_inbound_shipped_qty');
            $table->dropColumn('afn_inbound_receiving_qty');
            $table->dropColumn('afn_researching_qty');
            $table->dropColumn('afn_reserved_future_supply');
            $table->dropColumn('afn_future_supply_buyable');
            $table->dropColumn('fba_reserved_customer_order');
            $table->dropColumn('fba_reserved_fc_transfer');
            $table->dropColumn('fba_reserved_fc_processing');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippmentColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipment_carrier')
                ->nullable()
                ->after('status');

            $table->string('shipment_code')
                ->nullable()
                ->after('shipment_carrier');

            $table->string('shipment_package_type')
                ->nullable()
                ->after('shipment_code');

            $table->string('shipment_tracking_number')
                ->nullable()
                ->after('shipment_package_type');

            $table->string('shipment_tracking_last_event')
                ->nullable()
                ->after('shipment_tracking_number');

            $table->string('shipment_tracking_last_updated_api_at')
                ->nullable()
                ->after('shipment_tracking_last_event');

            $table->string('shipment_special_services')
                ->nullable()
                ->after('shipment_tracking_last_updated_api_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shipment_carrier');
            $table->dropColumn('shipment_code');
            $table->dropColumn('shipment_package_type');
            $table->dropColumn('shipment_tracking_number');
            $table->dropColumn('shipment_tracking_last_event');
            $table->dropColumn('shipment_special_services');
        });
    }
}

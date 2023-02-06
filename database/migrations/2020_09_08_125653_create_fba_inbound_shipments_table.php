<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbaInboundShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fba_inbound_shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_id');
            $table->string('shipment_name');
            $table->string('destination_fulfillment_center_id');
            $table->string('label_prep_type')->nullable();
            $table->bigInteger('ship_from_warehouse_id')->references('id')->on('warehouses')->onDelete('set null');
            $table->string('box_content_source')->nullable();
            $table->string('shipment_status');
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
        Schema::dropIfExists('fba_inbound_shipments');
    }
}

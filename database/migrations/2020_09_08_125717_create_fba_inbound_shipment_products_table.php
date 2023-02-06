<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbaInboundShipmentProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fba_inbound_shipment_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fba_inbound_shipment_id')->references('id')->on('fba_inbound_shipments')->onDelete('cascade');
            $table->string('product_id')->references('id')->on('products')->onDelete('set null');
            $table->integer('quantity_shipped');
            $table->integer('quantity_received');
            $table->integer('quantity_in_case');
            $table->date('release_date')->nullable();
            $table->string('fulfillment_network_sku');
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
        Schema::dropIfExists('fba_inbound_shipment_products');
    }
}

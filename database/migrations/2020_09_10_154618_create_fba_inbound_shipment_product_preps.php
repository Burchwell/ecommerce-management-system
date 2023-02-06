<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbaInboundShipmentProductPreps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fba_inbound_shipment_product_preps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fba_is_product_id');
            $table->string('prep_type');
            $table->string('prep_owner');
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
        Schema::dropIfExists('fba_inbound_shipment_product_preps');
    }
}

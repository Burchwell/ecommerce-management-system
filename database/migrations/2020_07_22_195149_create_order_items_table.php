<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('source_id');
            $table->integer('order_id');
            $table->integer('order_number');
            $table->integer('product_id');
            $table->integer('variant_id');
            $table->integer('fulfillable_quantity');
            $table->string('fulfillment_service');
            $table->string('fulfillment_status');
            $table->decimal('price');
            $table->string('sku');
            $table->integer('quantity');
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
        Schema::dropIfExists('order_items');
    }
}

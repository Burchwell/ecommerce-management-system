<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbaRestockInventoryRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fba_restock_inventory_recommendations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->decimal('sales_last_thirty', 15, 2)->nullabel();
            $table->string('units_sold_thirty')->nullabel();
            $table->string('total_units')->nullabel();
            $table->string('inbound')->nullabel();
            $table->string('available')->nullabel();
            $table->string('fc_transfer')->nullabel();
            $table->string('fc_processing')->nullabel();
            $table->string('customer_order')->nullabel();
            $table->string('unfulfillable')->nullabel();
            $table->string('days_of_supply')->nullabel();
            $table->string('alert')->nullabel();
            $table->string('recommended_replenishment_qty');
            $table->string('recommended_ship_date');
            $table->enum('current_month_inventory_level_thresholds_published', ['Yes', 'No'])->comment('Current Month')->default('No');
            $table->string('current_month_very_low_inventory_threshold')->nullabel();
            $table->string('current_month_minimum_inventory_threshold')->nullabel();
            $table->string('current_month_maximum_inventory_threshold')->nullabel();
            $table->string('current_month_very_high_inventory_threshold')->nullabel();
            $table->enum('next_month_inventory_level_thresholds_published', ['Yes', 'No'])->comment('Next Month')->default('No');
            $table->string('next_month_very_low_inventory_threshold')->nullabel();
            $table->string('next_month_minimum_inventory_threshold')->nullabel();
            $table->string('next_month_maximum_inventory_threshold')->nullabel();
            $table->string('next_month_very_high_inventory_threshold')->nullabel();
            $table->string('utilization')->nullabel();
            $table->string('maximum_shipment_quantity')->nullabel();
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
        Schema::dropIfExists('fba_restock_inventory_recommendations');
    }
}

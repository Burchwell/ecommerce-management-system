<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('adjustable_type');
            $table->string('adjustable_id');
            $table->string('adjustment_id');
            $table->string('adjustable_order_number');
            $table->string('reason')->nullable();
            $table->boolean('is_restock')->default(0);
            $table->string('quantity')->default(0);
            $table->string('type')->nullable();
            $table->decimal('amount')->default(0);
            $table->decimal('tax')->default(0);
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
        Schema::dropIfExists('order_adjustments');
    }
}

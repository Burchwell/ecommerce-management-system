<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFreightLabelsTable
 */
class CreateFreightLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freight_labels',
            function (Blueprint $table) {
            $table->id();
            $table->string('po_number');
            $table->bigInteger('carrier_id');
            $table->string('vendor');
            $table->timestamp('pickup_at');
            $table->integer('pallets');
            $table->text('address');
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
        Schema::dropIfExists('freight_labels');
    }
}

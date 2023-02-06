<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbaWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fba_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('fba_code');
            $table->string('name')->default('Amazon.com Services, Inc.');
            $table->string('address');
            $table->string('city');
            $table->integer('zipcode');
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
        Schema::dropIfExists('fba_warehouses');
    }
}

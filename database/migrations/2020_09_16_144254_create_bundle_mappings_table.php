<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundleMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundle_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('bundle_sku');
            $table->string('child_sku_1');
            $table->string('child_sku_2')->nullable();
            $table->string('child_sku_3')->nullable();
            $table->string('child_sku_4')->nullable();
            $table->string('child_sku_5')->nullable();
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
        Schema::dropIfExists('bundle_mappings');
    }
}

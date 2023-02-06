<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePalettesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('palettes', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('sku');
            $table->integer('totalpcs');
            $table->integer('cartonqty');
            $table->decimal('length',16, 2);
            $table->decimal('width',16, 2);
            $table->decimal('height',16, 2);
            $table->text('imageurl')->nullable();
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
        Schema::dropIfExists('palettes');
    }
}

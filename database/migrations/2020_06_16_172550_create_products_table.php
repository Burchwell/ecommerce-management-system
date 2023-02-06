<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('name')->nullable();
            $table->string('upc')->nullable();
            $table->string('asin')->nullable();
            $table->text('description')->nullable();
            $table->decimal('weight', 15,2)->nullable();
            $table->decimal('length', 15,2)->nullable();
            $table->decimal('width', 15,2)->nullable();
            $table->decimal('height', 15,2)->nullable();
            $table->text('location')->nullable();;
            $table->bigInteger('default_shipping_method_id')->nullable();
            $table->enum('verified', ['yes', 'no'])->nullable();
            $table->enum('active', ['yes', 'no'])->default('yes');
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
        Schema::dropIfExists('products');
    }
}

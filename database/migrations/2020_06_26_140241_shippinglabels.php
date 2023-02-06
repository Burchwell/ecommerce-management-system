<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Shippinglabels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippinglabels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number');
            $table->integer('shipping_service_id');
            $table->decimal('shipping_cost', 16,2)->default(0);
            $table->text('label');
            $table->boolean('printed')->default(false);
            $table->string('print_location')->nullable();
            $table->timestamp('printed_at')->nullable();
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
        Schema::drop('shippinglabels');
    }
}

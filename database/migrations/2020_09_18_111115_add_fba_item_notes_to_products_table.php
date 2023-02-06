<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFbaItemNotesToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Ship Individual Units
        Schema::table('products', function (Blueprint $table) {
            $table->string('fba_item_notes')
                ->nullable()
                ->after('is_finished_bundle');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('fba_item_notes');
        });
    }
}

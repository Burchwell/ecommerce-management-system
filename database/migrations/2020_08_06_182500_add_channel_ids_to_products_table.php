<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChannelIdsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('channel_advisor_id')->nullable()->after('id');
            $table->integer('shopify_id')->nullable()->after('channel_advisor_id');
            $table->integer('shipstation_id')->nullable()->after('shopify_id');
            $table->string('title')->nullable()->after('shipstation_id');
            $table->string('handle')->nullable()->after('title');
            $table->text('body_html')->nullable()->after('handle');
            $table->string('vendor')->nullable()->after('body_html');
            $table->string('product_type')->nullable()->after('vendor');
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
            $table->dropColumn('channel_advisor_id');
            $table->dropColumn('shopify_id');
            $table->dropColumn('shipstation_id');
            $table->dropColumn('title');
            $table->dropColumn('handle');
            $table->dropColumn('body_html');
            $table->dropColumn('vendor');
        });
    }
}

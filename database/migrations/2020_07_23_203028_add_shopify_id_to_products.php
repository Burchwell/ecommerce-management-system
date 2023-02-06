<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShopifyIdToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('shopify_id')->nullable()->after('id');
            $table->string('title')->nullable()->after('shopify_id');
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
            $table->dropColumn('shopify_id');
            $table->dropColumn('title');
            $table->dropColumn('handle');
            $table->dropColumn('body_html');
            $table->dropColumn('vendor');
            $table->dropColumn('product_type');
        });
    }
}

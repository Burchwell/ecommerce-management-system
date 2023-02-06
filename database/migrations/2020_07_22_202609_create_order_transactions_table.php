<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('transaction_id');
            $table->integer('parent_id');
            $table->decimal('amount', 16,2);
            $table->text('authorization');
            $table->string('currency');
            $table->string('error_code')->nullable();
            $table->string('gateway');
            $table->string('kind');
            $table->string('message');
            $table->json('payment_details');
            $table->string('status');
            $table->timestamp('created_at')->default(now());
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_transactions');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEodChecklistEodTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eod_checklist_eod_task', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('eod_checklist_id');
            $table->integer('eod_task_id');
            $table->string('completedBy');
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
        Schema::dropIfExists('eod_checklist_eod_task');
    }
}

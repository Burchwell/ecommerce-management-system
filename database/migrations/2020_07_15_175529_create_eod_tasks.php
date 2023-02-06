<?php

use App\Models\Warehouse\EodChecklistEodTask;
use App\Models\Warehouse\EodTask;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEodTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eod_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->timestamps();
        });

        foreach(EodChecklistEodTask::$tasks as $task => $description) {
            EodTask::create(['name'=>$task, 'description'=>$description]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eod_tasks');
    }
}

<?php

use Illuminate\Database\Seeder;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = [
            'cabrs' => 'Clean All Bathrooms and Refresh Supplies as Needed',
            'eqtb' => 'Empty Warehouse Trash Bins/Cans (Only Replace Bag if Needed, Otherwise Empty into Trash Trailer)',
            'rzpl' => 'Replace Zebra Printer Labels if Less Than 3" Stack Remains (20%)',
            'rsbtge' => 'Restock Shipping Area Boxes/Tape Guns/Envelopes',
            'csato' => 'Clean Shipping Area and Tidy Up/Organize',
            'rwsf' => 'Restock Warehouse Supply Fridge as Needed',
            'cadpdl' => 'Check for any Amazon Deliveries, etc. and Place in Designated Location Near Front Offices',
            'peedca' => 'Place all Electrical Equipment in Designated Charging Areas (Jacks, Walkie Talkies, Scissor Lift, etc)',
            'clbd' => 'Close and Lock all Bay Doors/Man Doors (Double Check all Doors)'
        ];

        foreach ($tasks as $task => $description) {
            \App\Models\Warehouse\EodTask::create(['name' => $task, 'description' => $description]);
        }
    }
}

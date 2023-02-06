<?php

namespace App\Console\Commands;

use App\Models\Label;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OneOffFixMissingLabels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oneoff:labels:repair';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = Storage::disk("labels")->allFiles();
        foreach ($files as $file) {
            $meta_data = Storage::disk("labels")->getMetadata($file);
            if ($meta_data['path'] !== '.DS_Store') {
                $order_number = str_replace('-final.pdf', '', $meta_data['path']);

                $label = Label::where('order_number', '=', $order_number)->first();
                $timestamp = Carbon::parse($meta_data['timestamp']);
                if ($label === null) {
                    echo "Label Log for $order_number not found. adding after the fact to Database.\r\n";
                    \Log::warning('Label Log for '.$order_number.' not found. adding after the fact to Database');
                    $label = [
                        'order_number' => $order_number,
                        'printed_at' =>  $timestamp,
                        'printed' => 1,
                        'computer_name' => 'DEVPC1'
                    ];
                    Label::updateOrCreate(['order_number' => $order_number], $label);
                } else {
                    if ($label->printed === 0) {
                        echo "Label Log for $order_number found. Setting printed.\r\n";
                        $label->printed = 1;
                        $label->printed_at = $timestamp;
                        $label->save();
                    }
                }
            }
        }
    }
}

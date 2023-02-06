<?php

namespace App\Console\Commands\Notifications\Ping;

use App\Events\Ping;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SystemNotResponding extends Command
{
    public $users = [
        'jeremy@skaraudio.com',
//        'kevin@skaraudio.com'
    ];

    public $timeout = 10; // Minutes
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ping:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $time = Carbon::now();

        $sources = \App\Models\Ping::where('type', '=', 'App\Models\Label')->get();
        $sources->each(function ($source) use ($time) {
            if (Carbon::createFromDate($source->last_ping)->addMinutes(5)->isPast()) {
                $body = "The System `{$source->source}` has not pinged the server within the last {$this->timeout} Minutes.";
                Mail::send('emails.tpl', compact("body"), function ($message) {
                    $message->to($this->users)->subject("System Ping Timeout.");
                });
            }
        });
    }
}

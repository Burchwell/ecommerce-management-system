<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InventoryTagging implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get Inventory

        // Get Orders By Tag

    }
}

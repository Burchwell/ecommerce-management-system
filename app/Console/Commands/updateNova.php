<?php

namespace App\Console\Commands;

use App\Helpers\Tracking\FedEx;
use App\Models\Order;
use App\Traits\Tracking;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use SebastianBergmann\Environment\Console;
use Symfony\Component\Console\Output\ConsoleOutput;

class updateNova extends Command
{
    use Tracking;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova:update';

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
        $cnsle  = new ConsoleOutput();
        $orders = Order::whereNull('shipstation_id')
            ->orWhereNull('shipment_tracking_last_event')
            ->orWhereNull('shipment_tracking_number')
            ->orderBy('id', 'DESC')
            ->chunk(60, function ($orders) use ($cnsle) {
                foreach ($orders as $order) {
                    $this->_getNovaData($order->order_number, $order);
                    $cnsle->writeln("Order #".$order->order_number." updated.");
                }
                sleep(30);
            });
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class NovaImportOrdersCommand extends Command
{

    /** @var string  */
    private $api_token = 'Qq1BFP5EtYSgHuKCYfCNwr1S8LIKbuAjXK5iw7NvA3c0zZthD1IIZMlCDJcK';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova:import:orders ';

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
        $orders = Order::whereNull('shipstation_id')->get();
        $orders->each(function ($order) {
            $client = new Client();
            $url = sprintf('https://nova.skaraudio.com/api/order/%s?api_token=%s', $order->order_number, $this->api_token);
            $resposne = $client->get($url);
            $nova_order = json_decode($resposne->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            $order->shipstation_id = $nova_order['orderId'];
            $order->save();
        });
    }
}

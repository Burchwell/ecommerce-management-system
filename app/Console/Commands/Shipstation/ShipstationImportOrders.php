<?php

namespace App\Console\Commands\Shipstation;

use App\Models\Order;
use App\Models\Products\Warehouse;
use GuzzleHttp\Client;
use http\Exception\RuntimeException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class ShipstationImportOrders
 * @package App\Console\Commands\Shipstation
 */
class ShipstationImportOrders extends Command
{

    private $optionKeys = [
        'shipStartDate',
        'shipEndDate',
        'page',
        'pageSize',
        'sortBy',
        'sortDir'
    ];

    private $sheet;

    public $spreadsheet_id = "";
    public $spreadsheet_sheet_name = "";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipstation:import:orders {--modifyDateStart} {--modifyDateEnd} {--pageSize=500} {--page=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Shipping status from shipstation and update google sheet tracking information.';

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
    public
    function handle()
    {
        $this->log("Starting orderUpdate Update");

        // Get Orders
        $options = $this->_setOptions();
        $response = $this->getOrders($options);
        $this->log("Updating Orders");
        $this->saveOrders($response['orders']);
    }

    /**
     * @return array
     */
    private
    function _setOptions()
    {
        $options = [];
        foreach ($this->options() as $key => $option) {
            if (in_array($key, $this->optionKeys, true)) {
                $options[$key] = $option;
            }
        }

        if (!array_key_exists('modifyDateEnd', $options)) {
            $options['modifyDateEnd'] = date('Y-m-d\T23:59:59.999999\Z', strtotime('yesterday'));
        }

        if (!array_key_exists('modifyDateStart', $options)) {
            $options['modifyDateStart'] = date('Y-m-d\TH:i:s.u\Z', strtotime('yesterday'));
        }

        return $options;
    }

    /**
     * @param $options
     * @return mixed
     */
    private
    function getOrders($options)
    {
        $page = 1;
        $max = 2;
        $client = $this->_newShipstationClient();
        $orders = [];
        while ($page <= $max) {
            $options['page'] = $page;
            $response = $client->get("https://ssapi4.shipstation.com/orders", ["query" => $options]);

            // If We hit Shipstation Request Limit
            // Wait and try again after Request Counter is reset.

            if ($response->getStatusCode() === 200) {
                $contents = json_decode($response->getBody()->getContents(), true);
                // Add results to shipments
                foreach ($contents['shipments'] as $shipment) {
                    $orders[] = $shipment;
                }
                $max = $contents['pages'];
            } elseif ($response->getStatusCode() === 429) {
                $retry = $response->getHeaderLine('Retry-After');
                $this->log("429: To Many Requests. Retrying in $retry");
                sleep($retry);
                return $this->getShipments($options);
                // Request was successful process results
            } else {
                throw new RuntimeException($response->getBody()->getContents());
            }
            ++$page;
        }
        return $orders;
    }

    private
    function saveOrders($orders) {
        foreach ($orders as $order) {
            $dborder = Order::where('order_number', '=', $order['orderNumber'])->first();
            $warehouse = Warehouse::where('shipstation_id', '=', $order['advancedOptions']['warehouseId'])->first();
            if ($dborder !== null && $warehouse !== null) {
                $dborder->shipped_from = $warehouse->getKey();
            }
        }
    }

    private
    function _newShipstationClient()
    {
        $options = [
            'headers' => [
                'Accept' => 'applictaion/json',
                'Content-Type' => 'applictaion/json'
            ],
            'auth' => [
                config('shipstation.shipstation.api_key'),
                config('shipstation.shipstation.api_secret_key')
            ]
        ];

        if (config('shipstation.shipstation.api_partner_key')) {
            $options['headers']['x-partner'] = config('shipstation.shipstation.api_partner_key');
        }

        return new Client($options);
    }

    private
    function log($message, $consoleOutput = true)
    {
        Log::channel('updateGSTracking')->info($message);
        if ($consoleOutput) {
            $output = new ConsoleOutput();
            $output->writeln($message);
        }
    }
}

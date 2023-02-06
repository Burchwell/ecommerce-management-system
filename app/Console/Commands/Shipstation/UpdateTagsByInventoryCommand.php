<?php

namespace App\Console\Commands\Shipstation;

use App\Jobs\Channable\Inventory\UpdateJob;
use App\Models\Products\Inventory;
use App\Models\Products\Product;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;

class UpdateTagsByInventoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipstation:updatetagsbyinventory {--test=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Shipstation Tags based on teh Product Inventory';

    /** warehouses @var int[]  */
    protected $warehouses = [
        1=>26462,
        2=>27825
    ];

    /** messages @var string[]  */
    private $messages = [
        "out_of_stock" => "Tag %s is going to be removed from %s. %s is out of stock.",
        "limited_stock" => "Tag %s is going to be removed from %s. %s ."
    ];

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
        $slack = [];
        Log::channel('autotag')->info("Starting `Update Shipstation tags by Inventory`");
        if ($this->option('test') === "true") {
            $this->log('Testing API Requests disabled.');
        }
        $client = $this->_newShipstationClient();

        foreach ($this->warehouses as $warehouse_id => $tag) {
            $this->log("Processing orders for warehouse $warehouse_id");
            $response = $client->get("https://ssapi4.shipstation.com/orders/listbytag?orderStatus=awaiting_shipment&tagId=$tag");
            $orders = collect(json_decode($response->getBody()->getContents(), true)['orders']);
            foreach ($orders->chunk(50) as $chunk) {
                foreach ($chunk as $order) {
                    $outofstock = [];
                    $nextWH = $warehouse_id === 2 ? 1 : 2;
                    $slack[$order['orderId']] = [];

                    // check if items are outofstock
                    foreach ($order['items'] as $item) {
                        $product = Product::with('inventory')
                            ->where('sku', '=', str_replace('DLR-', '', $item['sku']))
                            ->first();

                        if (is_object($product) && $item['sku'] !== "" && !in_array(explode("-", $item['sku'])[0], ['BNDLE', 'RFBISH', 'USED', '3CH']))  {
                            $outofstock[$warehouse_id] = (!$this->isInStock($warehouse_id, $product, $item['quantity']));
                            $outofstock[$nextWH] = (!$this->isInStock($nextWH, $product, $item['quantity']));

                            $slack[$order['orderId']][$item['sku']][($warehouse_id === 1) ? "TPA" : "VEGAS"] = ["in_stock" => !$outofstock[$warehouse_id]];
                            $slack[$order['orderId']][$item['sku']]["inStock"][($nextWH === 1) ? "TPA" : "VEGAS"] = ["in_stock" => !$outofstock[$nextWH]];

                            if ($outofstock[$warehouse_id] !== null && $outofstock[$nextWH] !== null) {
                                if ($outofstock[$warehouse_id]) {
                                    $slack[$order['orderId']][$item['sku']]["action"][] = "Removing Tag $tag from order #" . $order['orderId'];
                                    $this->log("Removing Tag $tag from order #" . $order['orderId']);
                                    if ($this->option('test') === "false") {
                                        $this->removeTagFromOrder($order['orderId'], $tag);
                                    }

                                    if ($outofstock[$nextWH]) {
                                        $slack[$order['orderId']][$item['sku']]["action"][] = "Tagging: RED 0a-b. Auto-Process Script [Sold Out All LOCS]";
                                        $this->log("Tagging: RED 0a-b. Auto-Process Script [Sold Out All LOCS]");
                                        if ($this->option('test') === "false") {
                                            $this->addTagToOrder($order['orderId'], 60755);
                                        }
                                    } else {
                                        $slack[$order['orderId']][$item['sku']]["action"][] = "Available in warheouse #$nextWH. Adding tag " . $this->warehouses[$nextWH] . ".";
                                        $this->log("Available in warheouse #$nextWH. Adding tag " . $this->warehouses[$nextWH] . ".");
                                        if ($this->option('test') === "false") {
                                            $this->addTagToOrder($order['orderId'], $this->warehouses[$nextWH]);
                                        }
                                    }
                                } else {
                                    $slack[$order['orderId']][$item['sku']]["action"][] = "{$item['sku']} in stock. No changes made.";
                                    $this->log("{$item['sku']} in stock. No changes made.");
                                }
                            } else {
                                $slack[$order['orderId']][$item['sku']]["action"][] = "Product not found in DB.";
                                $this->log("{$item['sku']} NOT FOUND IN DB.");
                            }
                        }
                    }

                    $slack[$order['orderId']]["action"] = "Tag Order as 0a-a. Auto-Process Script Ran Successfully.";
                    $this->log("Tag Order as 0a-a. Auto-Process Script Ran Successfully.");

                    if ($this->option('test') === "false") {
                        // Tag Order as 0a-a. Auto-Process Script Ran Successfully
                        $this->addTagToOrder($order['orderId'], 60756);
                    }
                }
            }
            $this->slack(json_encode($slack));
        }
        Log::info("`Update Shipstation tags by Inventory` completed");
    }

    private function isInStock($warehouse, $product, $quantity)
    {
        $inventory = Inventory::where([
            ['warehouse_id', '=', $warehouse],
            ['product_id', '=', $product->getKey()]
        ])->first();

        $this->log("$product->sku: $quantity|" . $inventory->quantity);

        if (!empty($inventory) && $quantity <= $inventory->quantity) {
            return true;
        }
        return false;
    }

    private function _newShipstationClient()
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

    private function addTagToOrder($orderId, $tag) {
        $client = $this->_newShipstationClient();
        $client->request("POST", 'https://ssapi4.shipstation.com/orders/addtag', [
            "json" => [
                'orderId' => $orderId,
                'tagId' => $tag
            ]
        ]);
    }

    private function removeTagFromOrder($orderId, $tag)
    {
        $client = $this->_newShipstationClient();
        $client->request("POST", 'https://ssapi4.shipstation.com/orders/removetag', [
            "json" => [
                'orderId' => $orderId,
                'tagId' => $tag
            ]
        ]);
    }

    private
    function log($message, $consoleOutput = true)
    {
        Log::channel('autotag')->info($message);
        if ($consoleOutput) {
            $output = new ConsoleOutput();
            $output->writeln($message);
        }
    }

    private
    function slack($message)
    {
        $client = new Client([
            "Content-Type" => 'application/json'
        ]);
        $client->request("POST", "https://hooks.slack.com/services/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", [
            "json" => ["text" => $message]
        ]);
    }
}

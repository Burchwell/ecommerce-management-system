<?php

namespace App\Console\Commands\Shipstation;

use Carbon\Carbon;
use DateTime;
use Google_Spreadsheet;
use GuzzleHttp\Client;
use http\Exception\RuntimeException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;

class OrdersUpdateGSTracking extends Command
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
    protected $signature = 'shipstation:updateGSTrackingInfo {--page=1} {--pageSize=500} {--sortBy=ShipDate} {--sortDir=ASC} {--spreadsheetID=1sWl1VtJ_87x7Xg7QUEnKnEAPHUbHF7O8qqOIX_Q-5bE} {--spreadsheetName=Automated Label Print Log v3}';

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
        $this->log("Starting gSheets Update");
        // Get Shipments
        $options = $this->_setOptions();
        $shipments = $this->getShipments($options);

        $this->log("Updating Tracking No.");
        // Get Sheet Data
        $client = Google_Spreadsheet::getClient(storage_path('google_service_credentials.json'));
        $sheet = ($client->file($this->option('spreadsheetID'))->sheet($this->option('spreadsheetName')))->fetch();
        $items = array_filter($sheet->select(function ($row) use ($options) {
            $dateStart = new DateTime($options['shipDateStart'], new \DateTimeZone('America/New_york'));
            $dateEnd = new DateTime($options['shipDateEnd'], new \DateTimeZone('America/New_york'));
            $shippingDate = new DateTime($row["Date"], new \DateTimeZone('America/New_york'));
            $six_h_ago = new DateTime(date("Y-m-d H:i:s", strtotime('-6 hours')), new \DateTimeZone('America/New_york'));
            $updateTS = new DateTime($row['Update Time Stamp'], new \DateTimeZone('America/New_york'));

             if (
                 $shippingDate->getTimestamp() > $dateStart->getTimestamp()
                 && $shippingDate->getTimestamp() < $dateEnd->getTimestamp()
//                 && empty($row["Update Time Stamp"]) ||
//                    $updateTS->getTimestamp() <= $six_h_ago->getTimestamp() &&
//                    $updateTS->getTimestamp() <= (new DateTime())->getTimestamp()

             ) {
                 return $row;
             }
        }));

        foreach ($shipments as $shipment) {
            foreach ($items as $item) {
                if ($item['Order ID'] === $shipment["orderNumber"]) {
                    $item["Tracking No."] = $shipment["trackingNumber"];
                }
            }
        }

        $reqCt = 0;

        $this->log("Updating Shipment Info.");
        foreach ($items as $row) {
            if (empty($row['Tracking No.'])) {

            } else {
                $status = $this->getShipmentStatus($row["Carrier"], $row["Tracking No."]);
                if ($row["Tracking Status"] !== $status["status_description"]) {
                    $sheet->update([
                        "Tracking No." => $row["Tracking No."],
                        "Tracking Status" => $status["carrier_status_description"],
                        "Update Time Stamp" => Carbon::now()->setTimezone('America/New_york')->toIso8601String()
                    ], [
                        "Order ID" => $row["Order ID"]
                    ]);

                    $this->log($row["Order ID"] . "proccessed. " . json_encode($row));
                    $reqCt++;
                }

                if ($reqCt % 75 === 0) {
                    sleep(100);
                }
            }
        }
    }

    private
    function getShipmentStatus($carrier, $tracking_number)
    {
        $carrier_codes = [
            "FedEx" => "fedex",
            "UPS" => 'ups',
            "U.S. Mail" => 'usps',
            "USPS" => 'usps',
        ];
        $carrier_code = $carrier_codes[$carrier];
        $client = $this->_newShipEngineClient();
        $response = $client->get('https://api.shipengine.com/v1/tracking', ["query" => [
            "tracking_number" => $tracking_number,
            "carrier_code" => $carrier_code
        ]]);

        return json_decode($response->getBody()->getContents(), true);
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

        if (!array_key_exists('shipDateEnd', $options)) {
            $options['shipDateEnd'] = date('Y-m-d\TH:i:s.u\Z', strtotime(now()));
        }

        if (!array_key_exists('shipDateStart', $options)) {
            $options['shipDateStart'] = date('Y-m-d\TH:i:s.u\Z', strtotime('-1 week', strtotime($options['shipDateEnd'])));
        }

        return $options;
    }

    /**
     * @param $options
     * @return mixed
     */
    private
    function getShipments($options)
    {
        $page = 1;
        $max = 2;
        $client = $this->_newShipstationClient();
        $shipments = [];
        while ($page <= $max) {
            $options['page'] = $page;
            $response = $client->get("https://ssapi4.shipstation.com/shipments", ["query" => $options]);

            // If We hit Shipstation Request Limit
            // Wait and try again after Request Counter is reset.

            if ($response->getStatusCode() === 200) {
                $contents = json_decode($response->getBody()->getContents(), true);
                // Add results to shipments
                foreach ($contents['shipments'] as $shipment) {
                    $shipments[] = $shipment;
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
        return $shipments;
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
    function _newShipEngineClient()
    {
        $options = [
            'headers' => [
                'Accept' => 'applictaion/json',
                'Content-Type' => 'applictaion/json',
                'API-Key' => config('shipstation.shipengine.api_key')
            ]
        ];

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

    private
    function slack($message)
    {
        $client = new Client();
        $client->request("POST", "https://hooks.slack.com/services/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", [
            "headers"=> [
                "Content-Type" => 'application/json',
                "Accept" => 'application/json'
            ],
            "json" => ["text" => $message]
        ]);
    }
}

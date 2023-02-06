<?php

namespace App\Http\Controllers;

use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request;
use PHPShopify\ShopifySDK;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $shopify;

    public $defaultPerPage = 15;

    public $perPage;

    public function __construct()
    {
        $config = [
            'ShopUrl' => config('shopify.store'),
            'ApiKey' => config('shopify.api_key'),
            'Password' => config('shopify.password')
        ];

        $this->shopify = new ShopifySDK($config);
    }

    public function index()
    {
        $this->perPage = Request::input('perPage');
        return view('web.app');
    }

    function contains($str, array $arr)
    {
        foreach($arr as $a) {
            if (stripos($str,$a) !== false) return true;
        }
        return false;
    }

    public function yesNo($bool) {
        return $bool === true ? 'Yes' : 'No';
    }

    public function dateDifference($date1, $date2) {
        $dStart = new \DateTime($date2);
        $dEnd = new \DateTime($date1);
        return ($dStart->diff($dEnd))->format('%r%a');
    }

    public function createAPDF($template, $data, $orientation = 'portrait', $saveTo = null, $options)
    {
        $pdf = PDF::loadView($template, $data)
            ->setOrientation($orientation);

        foreach ($options as $key => $value) {
            $pdf->setOption($key, $value);
        }

        if ($saveTo !== null && !file_exists(storage_path($saveTo))) {
            $path = storage_path("{$saveTo}");
            return $pdf->save($path);
        } else {
            return $pdf;
        }
    }
}

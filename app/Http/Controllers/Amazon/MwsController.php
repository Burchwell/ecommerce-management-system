<?php

namespace App\Http\Controllers\Amazon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ThiagoMarini\AmazonMwsClient;

/**
 * Class MwsController
 * @package App\Http\Controllers\Amazon
 */
class MwsController extends Controller
{
    /** @var AmazonMwsClient  */
    protected $Client;

    /**
     * OrdersController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->Client = new AmazonMwsClient(
            config('mws.access_key'),
            config('mws.private_key'),
            config('mws.seller_id'),
            [config('mws.marketplace_id')],
            config('mws.auth_token')
        );
    }
}

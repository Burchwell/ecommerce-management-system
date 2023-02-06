<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductsController;
use App\Mail\TestAmazonSes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Routes for your application. These
| Routes are loaded by the RouteServiceProvider within a group which



| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'amazon'], function () {
    Route::group(['prefix' => 'inventory'], function () {
        Route::get('{date}', 'Amazon\InventoryController@listInventorySupply');
    });



    Route::group(['prefix' => 'mws'], function () {
        Route::get('status', 'Amazon\MWS\OrdersController@getServiceStatus');
        Route::group(['prefix' => 'merchant'], function () {
            Route::group(['prefix' => 'fulfillment'], function () {
                Route::group(['prefix' => '{AmazonOrderId}'], function () {
                    Route::get('', 'Amazon\MWS\MerchantFulfillmentController@getEligibleShippingService');
                    Route::get('sellerinfo', 'Amazon\MWS\MerchantFulfillmentController@getAdditionalSellerInputs');
                });
            });
        });

        Route::group(['prefix' => 'orders'], function () {
            Route::get('', 'Amazon\MWS\OrdersController@listOrders');
            Route::group(['prefix' => '{AmazonOrderId}'], function () {
                Route::get('', 'Amazon\MWS\OrdersController@getOrder');
                Route::get('items', 'Amazon\MWS\OrdersController@listOrderItems');
            });
            Route::group(['prefix' => 'date'], function () {
                Route::get('{date?}', 'Amazon\MWS\AmazonMwsController@getOrders');
            });
        });

        Route::group(['prefix' => 'products'], function () {
            Route::get('', 'ProductsController@index');
        });

        Route::group(['prefix' => 'reports'], function () {
            Route::get('/list', 'Amazon\MWS\ReportsController@getReportList');
            Route::get('/requestList', 'Amazon\MWS\ReportsController@getReportRequestList');
            Route::get('/count', 'Amazon\MWS\ReportsController@getReportCount');
            Route::get('/show/{id}', 'Amazon\MWS\ReportsController@getReport');
            Route::get('/update', 'Amazon\MWS\ReportsController@updatePrice');
        });


        Route::group(['prefix' => 'fulfillments'], function () {
            Route::get('', 'Amazon\FulfillmentsController@index');
        });
    });
});

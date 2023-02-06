<?php

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
Route::group(["prefix" => "v1"], function () {
//    require_once __DIR__ .'/api/amazon.php';
    require_once __DIR__ . '/api/auth.php';
    require_once __DIR__ . '/api/labels.php';
    require_once __DIR__ . '/api/nova.php';
    require_once __DIR__ . '/api/pings.php';

    require_once __DIR__ . '/api/warehouse.php';
    require_once __DIR__ . '/api/services.php';
    require_once __DIR__ . '/api/shopify.php';
    require_once __DIR__ . '/api/shipstation.php';
    require_once __DIR__ . '/api/channeladvisor.php';
    require_once __DIR__ . '/api/webhooks.php';

    Route::group(['middleware' => ['check.credentials']], function () {
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
        require_once __DIR__ . '/api/products.php';
        require_once __DIR__ . '/api/orders.php';
        require_once __DIR__ . '/api/logs.php';
    });
});

Route::group(['prefix' => 'v2'], function () {
    Route::group(['prefix' => 'fisp'], function () {
        Route::group(['prefix' => '{id}'], function () {
            Route::get('view', 'FispController@show');
            Route::get('pdf', 'FispController@pdf');
        });
    });
//
//    Route::group(['prefix' => 'orders'], function () {
//        Route::group(['prefix' => 'tracking'], function () {
//            Route::group(['prefix' => 'update'], function () {
//                Route::get('{order_number?}', 'Orders\OrdersController@updateTracking');
//            });
//        });
//    });

    Route::group(['prefix' => 'tracking'], function () {
        Route::group(['prefix' => 'update'], function () {
            Route::get('', 'ShippingLabelsLogController@updateTracking');
            Route::get('nova', 'ShippingLabelsLogController@updateTracking');
        });
    });
});

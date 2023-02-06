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
Route::group(['prefix' => 'shopify'], function () {
    Route::group(['prefix' => 'orders'], function () {
        Route::get('', 'Shopify\OrdersController@index');
        Route::get('order', 'Shopify\OrdersController@getall');
        Route::group(['prefix' => '{order_id}'], function () {
            Route::get('', 'Shopify\OrdersController@view');
            Route::get('all', 'Shopify\OrdersController@getAll');
            Route::group(['prefix' => 'transactions'], function () {
                Route::get('', 'Shopify\Orders\TransactionsController@index');
                Route::get('count', 'Shopify\Orders\TransactionsController@count');
                Route::get('{transaction_id}', 'Shopify\Orders\TransactionsController@view');
            });
            Route::get('nova', 'Orders\NovaController@show');

            Route::group(['prefix' => 'risks'], function () {
                Route::get('', 'Shopify\Orders\RisksController@index');
                Route::get('{risk_id}', 'Shopify\Orders\RisksController@view');
            });

            Route::get('validate', 'Shopify\OrdersController@validateOrder');
        });
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('', 'Shopify\ProductsController@index');
        Route::group(['prefix' => '{product_id}'], function () {
            Route::get('', 'Shopify\ProductsController@view');
            Route::get('shopify', 'Shopify\ProductsController@import');
        });
    });

    Route::group(['prefix' => 'setup'], function () {
        Route::get('install', 'Shopify\SetupController@install');
        Route::get('token', 'Shopify\SetupController@getAccessToken');
    });
});

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
Route::group(['prefix' => 'products'], function () {
    Route::get('', 'ProductsController@index');
    Route::get('inventory', 'ProductsController@inventory');
    Route::get('fulfillments', 'ProductsController@fulfillments');

    Route::group(['prefix' => '{sku}'], function () {
        Route::get('', 'ProductsController@showBySku');
        Route::group(['prefix' => 'mappings'], function () {
            Route::get('', 'ProductsController@getMapping');
            Route::post('', 'ProductsController@storeMapping');
            Route::group(['prefix' => '{id}'], function () {
                Route::delete('', 'ProductsController@deleteMapping');
            });
        });
    });
    Route::group(['prefix' => 'sku'], function () {
        Route::group(['prefix' => '{sku}'], function () {
            Route::get('', 'ProductsController@showBySku');
        });
    });
    Route::group(['prefix'=> 'search'], function () {
        Route::get('{search}', 'ProductsController@search');
    });

    Route::group(['prefix' => 'sales'], function () {
        Route::get('{days}', 'ProductsController@salesByDays');
    });
});

Route::group(['prefix' => 'skumappings'], function () {
    Route::get('', 'ProductsController@skuMappings');
    Route::post('', 'ProductsController@saveAllSkuMappings');
    Route::post('{id}', 'ProductsController@saveSkuMappings');
    Route::delete('{id}', 'ProductsController@deleteSkuMappings');
});

Route::group(['prefix' => 'inventory'], function () {
    Route::get('', 'Products\InventoryController@index');
    Route::get('{search}', 'Products\InventoryController@search');
});

Route::group(['prefix' => 'fulfillments'], function () {
    Route::get('', 'Products\FulfillmentController@index');
    Route::group(['prefix' => 'inventory'], function () {
        Route::get('recommendations', 'FbaRestockInventoryRecommendationsController@index');
    });
});



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


Route::group(["prefix" => "logs"], function () {
    Route::group(['prefix' => 'shippinglabels'], function () {
        Route::get('', 'ShippingLabelsLogController@index');
        Route::get('pdf/{id}', 'ShippingLabelsLogController@printPDF');
        Route::get('csv', 'ShippingLabelsLogController@downloadCSV');
    });



    Route::group(['prefix' => "{id}"], function () {
        Route::post('printed', 'LabelController@log');
    });
});

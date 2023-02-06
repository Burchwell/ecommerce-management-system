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
Route::group(["prefix" => "shipstation"], function () {
    Route::group(['prefix'=> "tags"], function () {
        Route::get('', 'Shipstation\TagsController@index');
        Route::get('{id}', 'Shipstation\TagsController@show');
        Route::post('', 'Shipstation\TagsController@create');
        Route::put('{id}', 'Shipstation\TagsController@update');
        Route::delete('{id}', 'Shipstation\TagsController@delete');
    });
});
Route::apiResource("pings", "PingController");

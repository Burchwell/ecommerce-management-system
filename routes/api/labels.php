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


Route::group(["prefix" => "labels"], function () {
    Route::group(['prefix' => 'packingslip'], function () {
        Route::post('create', 'LabelsController@create');
        Route::get('view', 'LabelsController@view');
    });

    Route::group(['prefix' => "{id}"], function () {
        Route::post('printed', 'LabelController@log');
    });
});

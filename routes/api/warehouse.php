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


Route::group(['prefix' => 'warehouse'], function () {
    Route::group(['prefix' => 'eodchecklist'], function () {
        Route::get('', 'EodCheckListController@index');
        Route::get('{date}', 'EodCheckListController@edit');
        Route::get('view/{date}', 'EodCheckListController@show');
        Route::post('', 'EodCheckListController@store');
        Route::delete('{date}', 'EodCheckListController@destroy');
    });
});


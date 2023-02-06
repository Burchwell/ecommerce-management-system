<?php

use App\Http\Controllers\InventoryController;
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
Route::group(['prefix' => 'orders'], function () {
    Route::get('', 'Orders\OrdersController@index');
    Route::get('{id}', 'Orders\OrdersController@view');

    Route::group(['prefix' => 'freight'], function () {
        Route::get('view', 'FreightController@show');
        Route::post('label', 'FreightController@create');
    });
});

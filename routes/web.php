<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web Routes for your application. These
| Routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Controller@index');

Route::group(['prefix' => 'shopify'], function () {
    Route::get('preferences', 'Shopify\PreferencesController@index');
    Route::group(['prefix' => 'setup'], function () {
        Route::get('install', 'Shopify\SetupController@install');
        Route::get('token', 'Shopify\SetupController@getAccessToken');
    });
});

Route::group(['prefix' => 'orders'], function () {
    Route::group(['prefix' => 'validate'], function () {
        Route::post('{orderNumber}', 'Orders\OrdersController@validateOrder');
    });
});

Route::group(['prefix' => 'warehouse'], function () {
    Route::group(['prefix' => 'pallets'], function () {
        Route::get('', 'PalletsController@index');
        Route::post('', 'PalletsController@create');
        Route::get('show/{sku}', 'PalletsController@show');
    });

    Route::group(['prefix' => 'eodtasks'], function () {
        Route::get('', 'EodTasksController@index');
    });

    Route::group(['prefix' => 'eodchecklist'], function () {
        Route::get('', 'EodCheckListController@index');
        Route::get('{id}', 'EodCheckListController@edit');
        Route::get('view/{id}', 'EodCheckListController@show');
        Route::post('{checklist}', 'EodCheckListController@store');
        Route::post('view/complete/{checklist}', 'EodCheckListController@complete');
        Route::post('view/incomplete/{checklist}', 'EodCheckListController@incomplete');
        Route::delete('{date}', 'EodCheckListController@destroy');
        Route::get('date/{date}', 'EodCheckListController@showByDate');
    });
});

Route::group(['prefix' => 'app/'], function () {
    Route::get('{any?}', 'AppController@index')->where('any', '^(?!api).*$');
});


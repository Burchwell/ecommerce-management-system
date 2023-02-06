<?php


use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'services'], function () {
    Route::get('/cronitor', 'CronitorController@getCronStatus');

    Route::group(['prefix' => 'fba'], function () {
        Route::group(['prefix' => 'shipments'], function () {
            Route::get('import', 'FbaInboundController@importShipments');
            Route::group(['prefix' => 'inbound'], function () {
                Route::get('', 'FbaInboundController@index');
                Route::get('{shipmentId}', 'FbaInboundController@show');
            });
        });
    });
    Route::get('/fba/inbound', 'FbaInboundController@index');
    Route::get('/fba/inbound/import', 'FbaInboundController@importShipments');

    Route::get('/fba/inbound/{shipmentId}', 'FbaInboundController@show');
    Route::get('/fba/inbound/{shipmentId}/boxlabels', 'FbaInboundController@getBoxLabels');
    Route::get('/fba/inbound/{shipmentId}/palletlabels', 'FbaInboundController@getPalletLabels');
    Route::put('/fba/inbound/{shipmentId}/update', 'FbaInboundController@updateFbaShipment');
});

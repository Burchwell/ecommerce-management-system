<?php
Route::group(['prefix' => 'webhooks'], function () {
    Route::group(['prefix' => 'order'], function () {
        Route::get('', 'Orders\OrdersController@webhook');
    });

    Route::group(['prefix' => 'shipments'], function () {
        Route::post('', 'Orders\ShipmentsController@webhook');
    });
});

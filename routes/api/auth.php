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
Route::group(['prefix' => 'auth'], function () {
//    Route::post('register', 'AuthController@register');
    Route::any('login', 'Api\AuthController@login')->name('login');
    Route::get('checkAuthToken', 'Api\AuthController@checkAuthToken');
});


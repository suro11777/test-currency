<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => [\App\Http\Middleware\Cors::class],
    'namespace' => 'Api'
], function () {
    Route::get('currencies', 'CurrencyController@getCurrencies')->name('get-all-currencies');
    Route::get('currency/{id}', 'CurrencyController@getCurrencyById')->name('get-currency');
});

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
Route::get('categories/{id?}', 'API\CategoryController@index')->middleware('localization');
Route::get('homepage', 'API\HomePageController@index')->middleware('localization');
Route::get('offers', 'API\OfferController@index')->middleware('localization');
Route::get('offers/{id}', 'API\OfferController@show')->middleware('localization');

Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');




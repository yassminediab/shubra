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
Route::post('users/verify', 'API\AuthController@verifyUser');

Route::post('products/{id}/reviews', 'API\ProductController@reviewProduct')->middleware('jwt.auth');
Route::get('products/{id}/reviews', 'API\ProductController@getProductReviews');
Route::get('products/{id}', 'API\ProductController@getProduct');

Route::post('carts/{id?}', 'API\CartController@addToCart')->middleware('jwt.verify');
Route::get('carts/{id}', 'API\CartController@getCart');
Route::delete('carts/{id}/products/{productId}', 'API\CartController@deleteCart');

Route::post('addresses', 'API\AddressController@createAddress')->middleware('jwt.verify');
Route::get('addresses', 'API\AddressController@getAddresses')->middleware('jwt.auth');
Route::get('addresses/{id}', 'API\AddressController@getAddress')->middleware('jwt.verify');
Route::get('cities', 'API\AddressController@listCities')->middleware('jwt.verify');
Route::get('states/{id}', 'API\AddressController@listStates')->middleware('jwt.verify');

Route::post('orders', 'API\OrderController@createOrder')->middleware('jwt.verify');
Route::get('orders/{id}', 'API\OrderController@getOrder')->middleware('jwt.verify');
Route::get('orders', 'API\OrderController@listOrders')->middleware('jwt.auth');
Route::post('orders/{id}/feedback', 'API\OrderController@rateOrder')->middleware('jwt.verify');
Route::post('orders/{id}/delivery-feedback', 'API\OrderController@rateDelivery')->middleware('jwt.verify');

Route::get('coupons', 'API\OrderController@listOrders')->middleware('jwt.auth');








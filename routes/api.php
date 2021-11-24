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
Route::get('offers/type', 'API\OfferController@getOffersWithTypes')->middleware('localization');
Route::get('offers', 'API\OfferController@index')->middleware('localization');
Route::get('offers/{id}', 'API\OfferController@show')->middleware('localization');

Route::get('coupons', 'API\CouponController@index')->middleware('localization')->middleware('jwt.auth');
Route::get('coupons/{id}', 'API\CouponController@get')->middleware('localization')->middleware('jwt.auth');

Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');
Route::post('users/verify', 'API\AuthController@verifyUser');

Route::post('products/{id}/reviews', 'API\ProductController@reviewProduct')->middleware('jwt.auth');
Route::get('products/{id}/reviews', 'API\ProductController@getProductReviews');
Route::get('categories/{id}/products', 'API\ProductController@getCategoryProducts');
Route::get('products/{id}', 'API\ProductController@getProduct');
Route::get('products', 'API\ProductController@searchProducts');
Route::post('/products/{id}/wishlist', 'API\ProductController@wishlistProduct')->middleware('jwt.auth');

Route::post('carts/{id?}', 'API\CartController@addToCart')->middleware('jwt.verify');
Route::get('carts/{id}', 'API\CartController@getCart');
Route::post('carts/{id}/coupons', 'API\CartController@addCouponToCart')->middleware('jwt.auth');
Route::post('carts/{id}/vouchers', 'API\CartController@addVoucherToCart')->middleware('jwt.auth');
Route::delete('carts/{id}/products/{productId}', 'API\CartController@deleteCart');

Route::post('addresses', 'API\AddressController@createAddress')->middleware('jwt.verify');
Route::get('addresses', 'API\AddressController@getAddresses')->middleware('jwt.auth');
Route::get('addresses/{id}', 'API\AddressController@getAddress')->middleware('jwt.verify');
Route::delete('addresses/{id}', 'API\AddressController@deleteAddress')->middleware('jwt.auth');
Route::put('addresses/{id}', 'API\AddressController@editAddress')->middleware('jwt.auth');
Route::put('addresses/{id}/verify', 'API\AddressController@verifyAddress')->middleware('jwt.verify');
Route::get('cities', 'API\AddressController@listCities')->middleware('jwt.verify');
Route::get('states/{id}', 'API\AddressController@listStates')->middleware('jwt.verify');

Route::post('orders', 'API\OrderController@createOrder')->middleware('jwt.verify');
Route::get('orders/{date}/time-slots', 'API\OrderController@getAvailableTimeSlots')->middleware('jwt.verify');
Route::get('orders/{id}', 'API\OrderController@getOrder')->middleware('jwt.verify');
Route::get('orders', 'API\OrderController@listOrders')->middleware('jwt.auth');
Route::post('orders/{id}/feedback', 'API\OrderController@rateOrder')->middleware('jwt.verify');
Route::post('orders/{id}/delivery-feedback', 'API\OrderController@rateDelivery')->middleware('jwt.verify');
Route::post('orders/{id}/cancel', 'API\OrderController@cancelOrder')->middleware('jwt.auth');
Route::post('orders/{id}/products/{productId}/prepare', 'API\OrderController@prepareItemInOrder')->middleware('jwt.auth');
Route::post('orders/{id}/products/{productId}/issue', 'API\OrderController@issueItemInOrder')->middleware('jwt.auth');
Route::post('orders/{id}/packages', 'API\OrderController@addPackagesToOrder')->middleware('jwt.auth');

Route::get('/wishlist', 'API\UserController@getWishlist')->middleware('jwt.auth');
Route::put('/profile', 'API\UserController@editProfile')->middleware('jwt.auth');
Route::put('/email', 'API\UserController@editEmail')->middleware('jwt.auth');
Route::put('/phone', 'API\UserController@editPhone')->middleware('jwt.auth');
Route::put('/password', 'API\UserController@editPassword')->middleware('jwt.auth');
Route::post('/avatar', 'API\UserController@editAvatar')->middleware('jwt.auth');
Route::get('/profile', 'API\UserController@getProfile')->middleware('jwt.auth');









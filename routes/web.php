<?php

require 'admin.php'; // adding admin php with web.php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('generate', function (){
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo 'ok';
});

//Route::view('/', 'site.pages.homepage');
Route::get('/', 'Site\PagesController@index')->name('index');
//route for page not found
Route::get('/pagenotfound', 'Site\PagesController@pagenotfound')->name('notfound');

//other pages
Route::get('/reservation', 'Site\PagesController@reservation')->name('reservation');
// Reservation request route
Route::post('/reservation/mail', 'Site\ContactController@mailReservation')->name('reservation.post.mail');
// contact us mail
Route::post('/contact/mail', 'Site\ContactController@contact')->name('contact.post.mail');

Route::get('/contact', 'Site\PagesController@contact')->name('contact');
Route::get('/about', 'Site\PagesController@about')->name('about');

//User Authentication
Auth::routes(['verify' => true]);
// verify token for account activation
Route::post('/verify', 'Auth\VerificationTokenController@verify')->name('verify');
//forgot password
Route::post('/postforgot', 'Auth\ForgotPasswordController@postforgot')->name('postforgot');
//verify token for user password reset
Route::get('/verifytoken','Auth\VerificationTokenController@verifytoken')->name('verifytoken');
Route::post('/postverifytoken','Auth\VerificationTokenController@postverifytoken')->name('postverifytoken');
//Reset Password
Route::resource('/resetpassword', 'Auth\ResetPasswordController');

//user Dashboard : route is protected via middleware
Route::group(['middleware' => ['auth:web']], function () {     
Route::get('user/dashboard', 'Site\UserController@dashboard')->name('user.dashboard');
Route::post('user/dashboard/profile-update', 'Site\UserController@updateProfile')->name('user.updateProfile');
Route::post('user/dashboard/change-password', 'Site\UserController@changePassword')->name('user.changePassword');
//ajax route for user payment history.
Route::get('user/dashboard/payment-history/{year}', 'Site\UserController@paymenyHistory');
});


//Product Routes 
Route::get('/category/all', 'Site\ProductController@index')->name('products.index');
Route::get('/category/{slug}', 'Site\ProductController@categoryproductshow')->name('categoryproduct.show');
Route::get('/search', 'Site\ProductController@search')->name('search');

//Cart Routes
Route::get('/cart', 'Site\CartController@index')->name('cart.index');  
// this is for homepage cart store
Route::post('/cart/store', 'Site\CartController@store')->name('cart.store');
// this is for categorypage cart store
Route::post('/category/cart/store', 'Site\CartController@store')->name('cart.store');
Route::post('/cart/update', 'Site\CartController@update')->name('cart.update');
Route::post('/cart/delete', 'Site\CartController@destroy')->name('cart.delete');

//checkout Routes ['auth:web'] or ['auth'] is same
Route::group(['middleware' => ['auth']], function(){
    Route::get('/checkout', 'Site\CheckoutController@getCheckout')->name('checkout.index');
    Route::post('/checkout/order', 'Site\CheckoutController@placeOrder')->name('checkout.place.order');
    Route::get('/checkout/order/{id}', 'Site\CheckoutController@checkoutPayment')->name('checkout.payment');
    Route::get('/checkout/order/{id}/cancel', 'Site\CheckoutController@cancelOrder')->name('checkout.cancel');
    Route::get('/checkout/order/{id}/cash', 'Site\CheckoutController@cashOrder')->name('checkout.cash');
    // ajax route for user area and address
    Route::get('/checkout/zones/{id}', 'Site\CheckoutController@getZones');
    Route::get('/checkout/user/address/', 'Site\CheckoutController@getUserAddress');
    // SSLCOMMERZ Start
    Route::post('/pay', 'SslCommerzPaymentController@index');
    Route::post('/success', 'SslCommerzPaymentController@success');
    Route::post('/fail', 'SslCommerzPaymentController@fail');
    Route::post('/cancel', 'SslCommerzPaymentController@cancel');
    Route::post('/ipn', 'SslCommerzPaymentController@ipn');
    // SSLCOMMERZ END
});




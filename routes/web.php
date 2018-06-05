<?php

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



Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', 'Auth\AdminLoginController@loginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.post');
    Route::get('/register', 'Auth\AdminRegisterController@registrationForm')->name('admin.register');
    Route::post('/register', 'Auth\AdminRegisterController@register')->name('admin.register.post');
    Route::resource('/products', 'Api\AdminProductController');
    Route::resource('/manage-users', 'Api\AdminUserController');
    Route::get('/users', 'AdminController@users')->name('admin.users');
    Route::get('/', 'AdminController@index')->name('admin.home');

});

Route::group(['prefix' => '/api/v1'], function () {
    Route::resource('products', 'Api\ProductController', ['except' => ['create', 'edit']]);
    Route::resource('cart', 'Api\CartController', ['except' => ['create', 'edit']]);
    Route::get('cart-place-order', 'Api\CartController@placeOrder');

});
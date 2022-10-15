<?php

use Illuminate\Http\Request;

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

/*
|--------------------------------------------------------------------------
| 認証系
|--------------------------------------------------------------------------
*/
Route::get('/user', 'UserController@isLogin')->name('user'); // ログインユーザー

Route::post('/register', 'Auth\RegisterController@register')->name('register'); // 買い手ユーザー登録
Route::post('/login', 'Auth\LoginController@login')->name('login'); // 買い手ログイン
Route::post('/logout', 'Auth\LoginController@logout')->name('logout'); // 買い手ログアウト

Route::post('/shop/register', 'Shop\Auth\RegisterController@register')->name('shop.register'); // 売り手ユーザー登録
Route::post('/shop/login', 'Shop\Auth\LoginController@login')->name('shop.login'); // 売り手ログイン
Route::post('/shop/logout', 'Shop\Auth\LoginController@logout')->name('shop.logout'); // 売り手ログアウト

Route::post('/forgot', 'Auth\ForgotPasswordController@forgot')->name('forgot'); // 買い手パスワード再発行
Route::post('/reset', 'Auth\ResetPasswordController@reset')->name('reset'); // 買い手パスワード再設定
Route::get('/reset-password/{token}', 'Auth\ResetPasswordController@resetPassword')->name('reset-password'); // パスワード再設定画面表示

Route::post('/shop/forgot', 'Shop\Auth\ForgotPasswordController@forgot')->name('shop.forgot'); // 売り手パスワード再発行
Route::post('/shop/reset', 'Shop\Auth\ResetPasswordController@reset')->name('shop.reset'); // 買い手パスワード再設定
Route::get('/shop/reset-password/{token}', 'Shop\Auth\ResetPasswordController@resetPassword')->name('shop.reset-password'); // パスワード再設定画面表示

/*
|--------------------------------------------------------------------------
| 商品系
|--------------------------------------------------------------------------
*/
Route::post('/product', 'ProductController@sellProduct')->name('product.sell'); // 商品出品
Route::put('/product/{id}', 'ProductController@editProduct')->name('product.edit'); // 商品編集


Route::get('/product_list', 'ProductController@showProductList')->name('product-list.show'); // 商品一覧
Route::get('/product_list/{id}', 'ProductController@showProduct')->name('product.show'); // 商品詳細

Route::get('/sell_product/{id}', 'ProductController@showSellProduct')->name('sell-product.show'); // 出品した商品詳細


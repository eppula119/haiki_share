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
Route::delete('/product/{id}', 'ProductController@deleteProduct')->name('product.delete'); // 商品削除
Route::post('/buy/{id}', 'ProductController@buyProduct')->name('product.buy'); // 商品購入
Route::delete('/buy/{id}', 'ProductController@buyCancelProduct')->name('product.buy-cancel'); // 商品購入キャンセル


Route::get('/product_list', 'ProductController@showProductList')->name('product-list.show'); // 商品一覧
Route::get('/product_list/{id}', 'ProductController@showProduct')->name('product.show'); // 商品詳細
Route::get('/sell_product/{id}', 'ProductController@showSellProduct')->name('sell-product.show'); // 出品した商品詳細
Route::get('/prefecture_list', 'ProductController@getPrefectureList')->name('prefecture-list'); // 選択可能な都道府県取得
Route::get('/bought_product_list', 'ProductController@showBoughtProductList')->name('bought-product-list.show'); // 購入された商品一覧
Route::get('/sell_product_list', 'ProductController@showSellProductList')->name('bought-product-list.show'); // 出品した商品一覧

/*
|--------------------------------------------------------------------------
| ユーザー系
|--------------------------------------------------------------------------
*/
Route::get('/mypage', 'UserController@showMypage')->name('mypage.show'); // マイページ表示
Route::put('/profile', 'UserController@editProfile')->name('profile.edit'); // 買い手ユーザープロフィール編集
Route::put('/shop/profile', 'UserController@editShopProfile')->name('shop-profile.edit'); // 売り手ユーザープロフィール編集
Route::get('/all_prefecture_list', 'UserController@getAllPrefectureList')->name('all-prefecture-list'); // 全都道府県取得

/*
|--------------------------------------------------------------------------
| その他
|--------------------------------------------------------------------------
*/
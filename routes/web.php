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

Route::get('/', function () {
    return view('app');
});

// =============== 新規登録(買い手)画面表示 ===================
Route::get('/buyer_register', function () {
    return view('auth/buyer_register');
});
// =============== ログイン(買い手)画面表示 ===================
Route::get('/buyer_login', function () {
    return view('auth/buyer_login');
});
// =============== 新規登録(売り手)画面表示 ===================
Route::get('/seller_register', function () {
    return view('auth/seller_register');
});
// =============== ログイン(売り手)画面表示 ===================
Route::get('/seller_login', function () {
    return view('auth/seller_login');
});

// =============== パスワードリマインダー画面表示 ===================
Route::get('/password_reminder', function () {
    return view('auth/password_reminder');
});
// =============== パスワード再設定画面表示 ===================
Route::get('/password_reset', function () {
    return view('auth/password_reset');
});

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

// APIのURL以外のリクエストに対しては「app.blade.php」テンプレートを返す
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.+');

// =============== トップ画面表示 ===================
Route::get('/', function () {
    return view('main/top');
});

/*
|--------------------------------------------------------------------------
| 認証系画面
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| ユーザー情報系画面
|--------------------------------------------------------------------------
*/
// =============== プロフィール編集(買い手)画面表示 ===================
Route::get('/buyer_edit_profile', function () {
    return view('main/buyer_edit_profile');
});
// =============== プロフィール編集(売り手)画面表示 ===================
Route::get('/seller_edit_profile', function () {
    return view('main/seller_edit_profile');
});
// =============== マイページ(買い手)画面表示 ===================
Route::get('/buyer_mypage', function () {
    return view('main/buyer_mypage');
});
// =============== マイページ(売り手)画面表示 ===================
Route::get('/seller_mypage', function () {
    return view('main/seller_mypage');
});

/*
|--------------------------------------------------------------------------
| 商品系画面
|--------------------------------------------------------------------------
*/
// =============== 商品一覧画面表示 ===================
// Route::get('/product_list', function () {
//     return view('main/product_list');
// });
// =============== 商品詳細画面表示 ===================
Route::get('/product_detail', function () {
    return view('main/product_detail');
});
// =============== 商品出品画面表示 ===================
Route::get('/sell_product', function () {
    return view('main/sell_product');
});
// =============== 商品編集画面表示 ===================
Route::get('/edit_product', function () {
    return view('main/edit_product');
});


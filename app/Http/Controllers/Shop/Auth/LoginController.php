<?php

namespace App\Http\Controllers\Shop\Auth;

use App\Http\Controllers\Shop\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function guard()
    {
        //売り手ユーザー認証のguardを指定
        return \Auth::guard('shop');
    }

    // ログインユーザー情報を返す
    protected function authenticated(Request $request, $shop)
    {
        $shop->type = 'shop';
        // 都道府県情報一緒に返す
        $shop["prefecture"] = $shop->prefecture;
        // メールアドレスもjson形式で渡す
        $shop->makeVisible(['email']);
        return $shop;
    }

    // セッション再生性
    protected function loggedOut(Request $request)
    {
        // セッションを再生成する
        $request->session()->regenerate();
        // レスポンスの値をjson形式で返す
        return response()->json();
    }

    // ログアウト実行
    public function logout(Request $request)
    {
        $this->guard()->logout();

        return $this->loggedOut($request) ?: redirect('/seller_login'); // ログアウト後のリダイレクト先
    }
}

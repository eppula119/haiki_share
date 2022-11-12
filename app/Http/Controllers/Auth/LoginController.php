<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; //ログ取得

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // ログインユーザー情報を返す
    protected function authenticated(Request $request, $user)
    {
        // メールアドレスもjson形式で渡す
        $user->makeVisible(['email']);
        $user->type = 'user';
        return $user;
    }

    // ログアウト実行
    protected function loggedOut(Request $request)
    {
        // セッションを再生成する
        $request->session()->regenerate();
        // レスポンスの値をjson形式で返す
        return response()->json();
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Carbon; // 日付関連に使用
use Illuminate\Support\Str; // 文字列関連に使用
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt; // 暗号・複合化時に使用
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\PasswordReset;
use App\Rules\AlphaNumHalf; // 半角英数字入力のバリデーションルール
use App\Models\User;
use App\Models\ResetPassword;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    // vueでアクセスするリセットへのルート
    protected $vueRouteReset = 'password_reset';
    // トークン有効期限を1時間に設定(600 = 10分)
    protected $expires = 600 * 6;

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest');
    }

    /**
     * パスワード変更メールからのコールバック
     * @param string $token
     * @return Redirect
     */
    public function resetPassword($token = null)
    {
        // トークンがあるかチェック
        $isFoundResetPassword = ResetPassword::where('token', $token)->exists();
        // なかったとき
        if (!$isFoundResetPassword) {
            // メッセージをクッキーにつけてリダイレクト
            $message = Lang::get('passwords.token');
            return $this->redirectWithMessage($this->vueRouteReset, $message);
        }

        // トークンをクッキーにつけてリセットページにリダイレクト
        return $this->redirectWithToken($this->vueRouteReset, $token);
    }

    /**
     * パスワードリセット
     * @param Request $request
     * @return void
     */
    public function reset(Request $request)
    {   // バリデーション
        $validator = $this->validator($request->all());
        
        // 送られてきたトークンを復号
        $token = Crypt::decryptString($request->token);
        // リセットパスワードモデルを取得
        $resetPassword = ResetPassword::where('token', $token)->first();
        // ユーザ(買い手)の宣言
        $user = null;
        
        // 追加のバリデーション
        $validator->after(function ($validator) use ($resetPassword, &$user) {
            // リセットパスワードがない場合
            if (!$resetPassword) {
                return $validator->errors()->add('token', '無効な処理です。再度パスワード再発行手続きから行ってください。');
            }
            // トークン期限切れチェック
            $isExpired = $this->tokenExpired($resetPassword->created_at);
            if ($isExpired) {
                return $validator->errors()->add('token', 'パスワード再設定可能な有効期限が切れました。');
            }
            // ユーザの取得
            $user = User::where('email', $resetPassword->email)->first();
            // ユーザの存在チェック
            if (!$user) {
                return $validator->errors()->add('token', 'ユーザー情報が存在しません。');
            }
        });

        // これで、バリデーションがある場合に、jsonレスポンスを返す
        $validator->validate();
        // トランザクション、アップデート後のユーザを返す
        $user = DB::transaction(function () use ($request, $resetPassword, $user) {
            // リセットパスワードテーブルからデータを削除
            ResetPassword::destroy($resetPassword->email);
            // パスワードを変更
            $user->password = Hash::make($request->password);
            // リメンバートークンを変更
            $user->setRememberToken(Str::random(60));
            // データを保存
            $user->save();
            
            // ユーザ(買い手)を返却
            return $user;
        });

        // イベントを発行
        event(new PasswordReset($user));
        // ユーザをログインさせる
        Auth::login($user, true);
        // メールアドレスもjson形式で渡す
        $user->makeVisible(['email']);
        // ユーザー種別を渡す
        $user->type = 'user';
        // ユーザ(買い手)を返却
        return $user;
    }

    /**
     * バリデーションルール
     * @param  array  $data
     * @return \Illuminate\Support\Facades\Validator;
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'token' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed', new AlphaNumHalf],
        ]);
    }

    /**
     * トークン有効期限の判定.
     * @param  string  $createdAt
     * @return bool
     */
    protected function tokenExpired($createdAt) {
        return Carbon::parse($createdAt)->addSeconds($this->expires)->isPast();
    }


    /**
     * メッセージをクッキーに付けてリダイレクト
     * @param  string  $route
     * @param  string  $message
     * @return Redirect
     */
    protected function redirectWithMessage($vueRoute, $message)
    {
        // vueでアクセスするルートを作る
        // コールバックURLをルート名で取得
        // .envの「APP_URL」に設定したurlを取得
        $baseUrl = config('app.url');
        $route = "{$baseUrl}/{$vueRoute}";

        return redirect($route)
          // PHPネイティブのsetcookieメソッドに指定する引数同じ
          ->cookie('MESSAGE', $message, 0, '', '', false, false)
          ->withCookie('TYPE', 'user', 0, '', '', false, false);
    }

    /**
     * クッキーにトークンをつけてリダイレクト
     * @param  string  $route
     * @param  string  $message
     * @return Redirect
     */
    protected function redirectWithToken($vueRoute, $token)
    {
        // vueでアクセスするルートを作る
        // コールバックURLをルート名で取得
        // .envの「APP_URL」に設定したurlを取得
        $baseUrl = config('app.url');
        $route = "{$baseUrl}/{$vueRoute}";
        return redirect($route)
            ->cookie('RESETTOKEN', $token, 0, '', '', false, false)
            ->withCookie('TYPE', 'user', 0, '', '', false, false);
    }
}

<?php

namespace App\Http\Controllers\Shop\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Str; // 文字列関連に使用
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\ResetPassword;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * 送られてきた内容をテーブルに保存してパスワード変更メールを送信
     *
     * @param  \Illuminate\Http\Request  $request
     * @return ResetPassword
     */

     public function forgot(Request $request) {
         // 送られてきた内容のバリデーション
         $this->validator($request->all())->validate();

         // トークンを作成
         $token = $this->createToken();

         // 古いデータが有れば削除
         ResetPassword::destroy($request->email);

         // 送られてきた内容をテーブルに保存
         $resetPassword = new ResetPassword($request->all());
         // 新しく作成したトークンをtokenカラムへ保存
         $resetPassword->token = $token;
         $resetPassword->save();

         $type = 'shop';

         // メールクラスでメールを送信
         $this->sendResetPasswordMail($resetPassword->email, $token);

         return $resetPassword;
     }

      /**
     * バリデーション
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => [ 'required', 'email', 'string', 'max:255', 'exists:shops,email' ]
        ]);
    }

    /**
     * トークンを作成する
     * @return stirng
     */
    private function createToken()
    {
        return hash_hmac('sha224', Str::random(40), config('app.key'));
    }

    /**
     * メールクラスでメールを送信
     *
     * @param string $email
     * @param string $token
     * @param string $type
     * @return void
     */
    private function sendResetPasswordMail($email, $token)
    {
        // ResetPasswordMailインスタンスの第二引数に売り手か買い手かの種別を渡す
        Mail::to($email)->send(new ResetPasswordMail($token, 'shop'));
    }
}

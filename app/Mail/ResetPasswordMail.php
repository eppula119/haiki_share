<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $type)
    {
        // 引数でトークンを受け取る
        $this->token = $token;
        // 引数で種別(売り手・買い手)を受け取る
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'パスワードリセットメール’'; // 件名
        // コールバックURLをルート名で取得
        $baseUrl = config('app.url');
        $token = $this->token;
        $type = $this->type;
        // 買い手ユーザーの場合
        if($type === 'user') {
            $url = "{$baseUrl}/api/reset-password/{$token}";
        // 売り手ユーザーの場合
        } else if($type === 'shop') {
            $url = "{$baseUrl}/api/{$type}/reset-password/{$token}";
        }

        // 送信元のアドレス
        $from = config('mail.from.address');

        return $this->from($from)
            ->subject($subject)
            // 送信メールのビュー
            ->view('auth.password_reset_mail')
            // ビューで使う変数を渡す
            ->with('url', $url);
    }
}

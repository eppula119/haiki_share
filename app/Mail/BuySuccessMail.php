<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Log; //ログ取得

class BuySuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $type)
    {
        // 引数で購入関連情報を受け取る
        $this->data = $data;
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
        Log::debug('メール送信直前');
        // コールバックURLをルート名で取得
        $baseUrl = config('app.url');
        // 送信元のアドレス
        $from = config('mail.from.address');
        // ユーザー種別(売り手・買い手)
        $type = $this->type;
        Log::debug('typeの中身');
        Log::debug($type);
        // 購入関連の情報
        $data = $this->data;

        // 出品(売り手)ユーザーの場合
        if($type === 'shop') {
            Log::debug('出品ユーザーにメール送信！');
            Log::debug('$dataの中身');
            Log::debug($data);
            $subject = '商品が購入されました'; // 件名
            $url = "{$baseUrl}/product_list";

            return $this->from($from)
                ->subject($subject)
                // 送信メールのビュー
                ->view('mail.product_buy_seller')
                // ビューで使う変数を渡す
                ->with([
                    'url' => $url,
                    'userName' => $data->userName,
                    'email' => $data->userEmail,
                    'buyDate' => $data->buyDate,
                    'productName' => $data->name,
                    'price' => $data->price,
                    'bestBefore' => $data->best_day . $data->best_time,
                ]);

        // 購入(買い手)ユーザーの場合
        } else if($type === 'user') {
            Log::debug('購入ユーザーにメール送信！');
            Log::debug('$dataの中身');
            Log::debug($data);
            $subject = '購入完了メール'; // 件名
            $url = "{$baseUrl}/product_list";

            return $this->from($from)
                ->subject($subject)
                // 送信メールのビュー
                ->view('mail.product_buy_buyer')
                // ビューで使う変数を渡す
                ->with([
                    'url' => $url,
                    'productName' => $data->name,
                    'price' => $data->price,
                    'bestBefore' => $data->best_day . $data->best_time,
                    'shopName' => $data->shop->name . $data->shop->branch_name,
                    'address' => $data->shop->prefecture->name . $data->shop->city . ' ' . $data->shop->other_address
                ]);
        }

        Log::debug('typeがおかしい');
        

        

        
    }
}

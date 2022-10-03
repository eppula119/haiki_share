<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduct; // バリデーションルール
use App\Models\Product; // 商品モデル
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // データベースの操作
use Illuminate\Support\Facades\Auth; // 認証済みユーザー
use Illuminate\Support\Facades\Storage; // 画像ファイルの操作
use Illuminate\Support\Facades\Log; //ログ取得

class ProductController extends Controller
{
    public function __construct()
    {
        // 売り手ユーザーの認証が必要
        $this->middleware('auth:shop')->except(['showProductList', 'showProduct']);
    }

    /**
     * 商品一覧
     */
    public function showProductList()
    {
        $products = Product::with(['shop' => function ($query) {
            $query->with(['prefecture']);
        }])->orderBy('created_at', 'desc')->paginate(3);


        return $products;
    }

    /**
     * 商品詳細
     */
    public function showProduct($id)
    {
        $product = Product::with(['shop' => function ($query) {
            $query->with(['prefecture']);
        }])->find($id);

        return $product;
    }

    /**
     * 商品出品
     * @param StoreProduct $request
     * @return \Illuminate\Http\Response
     */
    public function sell(StoreProduct $request)
    {
        
        $product = new Product();
        $product->name = $request->product_name;
        $product->price = $request->price;
        // 賞味期限(日付)と賞味期限(時間)の入力値を結合
        $product->best_before = $request->day . ' ' . $request->time ;


        // 登録可能画像数だけループ
        for ($i = 0; $i < 5; $i++) {
            $imgNumber = $i + 1;
            // n枚目の画像登録がある場合
            if(!empty($request["image_{$imgNumber}"])) {
                // 「image_{n}」カラムに保存
                $product["image_{$imgNumber}"] = $request["image_{$imgNumber}"];
                // 出品商品のファイル名を取得
                $fileName = $request["image_{$imgNumber}"]->getClientOriginalName();
                /* インスタンス生成時に割り振られたランダムなID値と
                    本来のファイル名を組み合わせてファイル名とする */
                $product["image_{$imgNumber}"] = $product->id . '_' . $fileName;
                /* S3にファイルを保存
                第三引数の'public'はファイルを公開状態で保存するため */
                Storage::cloud()
                    ->putFileAs('product_images', $request["image_{$imgNumber}"], $product["image_{$imgNumber}"], 'public');
            }
        }

        /* データベースエラー時にファイル削除を行うため
           トランザクションを利用する */
        DB::beginTransaction();

        try {
            // productsテーブルの「shop_id」にログイン中の売り手ユーザーIDを紐付けて登録
            Auth::guard('shop')->user()->products()->save($product);
            // データーベースへ保存実行
            DB::commit();
        } catch (\Exception $exception) {
            // データベースを保存前に戻す
            DB::rollBack();
            // 登録可能画像数だけループ
            for ($i = 0; $i < 5; $i++) {
                $imgNumber = $i + 1;
                // DBとの不整合を避けるためアップロードしたファイルをクラウドからも削除
                if(!empty($request["image_{$imgNumber}"])) {
                    Storage::cloud()->delete('product_images/'. $product["image_{$imgNumber}"]);
                }
            }
            throw $exception;
        }

        // 新規作成のためレスポンスコードは201(CREATED)を返却
        return response($product, 201);
    }
}

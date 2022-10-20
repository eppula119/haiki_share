<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduct; // バリデーションルール
use App\Models\Product; // 商品モデル
use App\Models\User; // 買い手ユーザーモデル
use App\Rules\MegaBytes; // 画像ファイルサイズルール
use App\Mail\BuySuccessMail; // 購入成功メールクラス
use Carbon\Carbon; // 日時を扱うクラス
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // メール送信の操作
use Illuminate\Support\Facades\Validator; // バリデーション作成
use Illuminate\Support\Facades\DB; // データベースの操作
use Illuminate\Support\Facades\Auth; // 認証済みユーザー
use Illuminate\Support\Facades\Storage; // 画像ファイルの操作


use Illuminate\Support\Facades\Log; //ログ取得

class ProductController extends Controller
{
    public function __construct()
    {
        // 売り手ユーザーの認証が必要
        $this->middleware('auth:shop')->except(['showProductList', 'showProduct', 'buyProduct']);
        // 買い手ユーザーの認証が必要
        $this->middleware('auth:user')->except(['showProductList', 'showProduct', 'showSellProduct', 'sellProduct', 'editProduct']);
    }

    /**
     * 商品一覧
     */
    public function showProductList()
    {
        $products = Product::with(['users', 'shop' => function ($query) {
                $query->with(['prefecture']);
            }])->orderBy('created_at', 'desc')->paginate(3);
        
        // ログインユーザー
        $loginUser = Auth::user();
        // ログインユーザーID保持、ログインしていない場合はnull値を渡す
        $loginUserId = $loginUser ? $loginUser->id : null;

        // 取得した商品の数けループ
        foreach ($products as $product) {
            // 購入フラグ付与
            $this->addBuyflg($product, $loginUserId);
            
        }

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

        // ログインユーザー
        $loginUser = Auth::user();
        // ログインユーザーID保持、ログインしていない場合はnull値を渡す
        $loginUserId = $loginUser ? $loginUser->id : null;
        // 購入フラグ付与
        $this->addBuyFlg($product, $loginUserId);
        

        return $product;
    }

    /**
     * 出品した商品詳細
     */
    public function showSellProduct($id)
    {
        Log::debug("showSellProduct実行");
        Log::debug($id);

        $product = Product::find($id);
        Log::debug("productの中身");
        Log::debug($product);

        return $product;
    }

    /**
     * 商品出品
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function sellProduct(Request $request)
    {
        Log::debug("requestの中身");
        Log::debug($request);
        // 送られてきた内容のバリデーション
        $this->validator($request->all())->validate();
        
        $product = new Product();
        Log::debug("new Product()の中身");
        Log::debug($product);
        $product->name = $request->product_name;
        $product->price = $request->price;
        // 賞味期限(日付)と賞味期限(時間)の入力値を結合
        $product->best_before = $request->best_day . ' ' . $request->best_time ;


        // // 登録可能画像数だけループ
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

    /**
     * 商品編集
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function editProduct(Request $request, $id)
    {
        Log::debug("requestの中身");
        Log::debug($request);
        Log::debug("idの中身");
        Log::debug($id);

        // 送られてきた内容の画像以外、バリデーション実行
        $this->validator($request->only(['product_name', 'price', 'best_day', 'best_time']))->validate();
        Log::debug("バリデーションクリア");

        $product = Product::find($id);
        Log::debug("productの中身");
        Log::debug($product);
        $product->name = $request->product_name;
        $product->price = $request->price;
        // 賞味期限(日付)と賞味期限(時間)の入力値を結合
        $product->best_before = $request->best_day . ' ' . $request->best_time;

        // リクエストから取得した画像パスを配列で保持
        $searchRequestImages = array();
        // クラウドのストレージから削除した画像を配列で保持(トランザクションで、エラー時に復元させるため)
        $deleteImages = array();
        // クラウドのストレージに登録した画像を配列で保持(トランザクションで、エラー時に削除するため)
        $createdImages = array();
        // クラウドのストレージ情報取得
        $storage = Storage::cloud();

        // 登録可能画像数だけループ
        for ($i = 0; $i < 5; $i++) {
            $imgNumber = $i + 1;
            // n枚目の画像登録がある場合
            if(!empty($request["image_{$imgNumber}"])) {
                // リクエストから取得した画像パスの商品ID名以前を切り取って配列に追加(画像パスには商品ID名が付与されているため)
                array_push($searchRequestImages, strstr($request["image_{$imgNumber}"], $id, false));
            }

        }

        Log::debug('$searchRequestImagesの中身');
        Log::debug($searchRequestImages);
        // 登録可能画像数だけループ
        for ($i = 0; $i < 5; $i++) {
            $imgNumber = $i + 1;
            Log::debug('$product["image_{$imgNumber}"]の中身');
            Log::debug($product["image_{$imgNumber}"]);
            // データベースの「images_n」カラムの画像パスが、リクエストから取得した画像パスのいずれかに一致するか判定
            $result = array_search($product["image_{$imgNumber}"], $searchRequestImages);
            
            // 一致しない場合
            if($result === false) {
                // クラウドのストレージデータからファイル名を一時的に変更(トランザクションエラー時、名前を戻す)
                $storage->move('product_images/' . $product["image_{$imgNumber}"], 'product_images/' . 'delete_' . $product["image_{$imgNumber}"]);
                // 削除予定画像配列に追加
                array_push($deleteImages, 'product_images/' . 'delete_' . $product["image_{$imgNumber}"]);
            }
        }

        // 登録可能画像数だけループ
        for ($i = 0; $i < 5; $i++) {
            // 画像ナンバー
            $imgNumber = $i + 1;

            // n枚目の画像登録がある場合
            if(!empty($request["image_{$imgNumber}"])) {
                // 画像URLから「product_images」文字列より以前を削除
                $imgPath = strstr($request["image_{$imgNumber}"], 'product_images', false);
                // クラウドのストレージに画像パスが保存されている場合
                if($storage->exists($imgPath)) {
                    // 画像パスから商品ID名以前を切り取って追加
                    $product["image_{$imgNumber}"] = strstr($request["image_{$imgNumber}"], $id, false);
                    
                // クラウドのストレージに画像パスが保存されていない場合
                } else {
                    Log::debug("image_{$imgNumber}".'の画像URLはS3に存在しない！！！');
                    // 新たに登録する画像なため、画像バリデーション実施
                    $validator = Validator::make($request->all(), [
                        "image_{$imgNumber}" => ['file','mimes:jpg,jpeg,png', new MegaBytes(1)],
                    ]);
                    // バリデーション失敗の場合
                    if ($validator->fails()) {
                        $errors["errors"] = '';
                        Log::debug("画像バリデーション失敗!");
                        // 削除予定画像の数だけループ
                        for($i = 0; $i < count($deleteImages); $i++) {
                            Log::debug($deleteImages[$i]);
                            // クラウドのストレージデータから変更したファイル名を元に戻す
                            $storage->move($deleteImages[$i], preg_replace('/delete_/', '', $deleteImages[$i]));
                        }
                         $errors["errors"] =  $validator->messages();
                        return response()->json($errors, 422);
                    }



                    Log::debug("画像バリデーションクリア!");
                    
                    // 出品商品のファイル名を取得
                    $fileName = $request["image_{$imgNumber}"]->getClientOriginalName();
                    /* インスタンス生成時に割り振られたランダムなID値と
                        本来のファイル名を組み合わせてファイル名とする */
                    $product["image_{$imgNumber}"] = $product->id . '_' . $fileName;
                    /* S3にファイルを保存
                    第三引数の'public'はファイルを公開状態で保存するため */
                    Storage::cloud()
                        ->putFileAs('product_images', $request["image_{$imgNumber}"], $product["image_{$imgNumber}"], 'public');
                    // ストレージに登録した画像を保持する配列へ追加
                    $createdImages[] = $product["image_{$imgNumber}"];
                }
            // n枚目の画像登録がない場合
            } else {
                // データベースの「image_n」カラムにNULL値を保存
                $product["image_{$imgNumber}"] = NULL;
            }
        }

        Log::debug('データベース登録直前の$productの中身');
        Log::debug($product);





        /* データベースエラー時にファイル削除を行うため
           トランザクションを利用する */
        DB::beginTransaction();

        try {
            // productsテーブルの「shop_id」にログイン中の売り手ユーザーIDを紐付けて登録
            Auth::guard('shop')->user()->products()->save($product);
            // データーベースへ保存実行
            DB::commit();
            // クラウドのストレージデータから削除
            $storage->delete($deleteImages);
        } catch (\Exception $exception) {
            Log::debug('例外発生');
            // データベースを保存前に戻す
            DB::rollBack();
            // 登録可能画像数だけループ
            for($i = 0; $i < count($createdImages); $i++) {
                Log::debug('$createdImages[$i]の中身');
                Log::debug($createdImages[$i]);
                // DBとの不整合を避けるためアップロードしたファイルをクラウドからも削除
                $storage->delete('product_images/'. $createdImages[$i]);
            }
            // 削除した画像の数だけループ
            for($i = 0; $i < count($deleteImages); $i++) {
                Log::debug("削除予定だが、トランザクションエラーのため削除しない");
                Log::debug($deleteImages[$i]);
                // クラウドのストレージデータから変更したファイル名を元に戻す
                $storage->move($deleteImages[$i], preg_replace('/delete_/', '', $deleteImages[$i]));
            }
            throw $exception;
        }

        // レスポンスコードは200(OK)を返却
        return response($product, 200);
    }

    /**
     * 商品購入
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function buyProduct(Request $request, $id)
    {
        Log::debug("buyProductメソッド実行！！ requestの中身");
        Log::debug($request);

        // 購入する商品情報を取得
        $product = Product::where('id', $id)->with(['shop' => function ($query) {
            $query->with(['prefecture']);
        }])->first();

        Log::debug('$productの中身');
        Log::debug($product);
        // 購入テーブルにレコードが存在する場合
        if(!empty($product->users)) {


            // 購入テーブルの「updated_at」カラムの値を格納する配列
            $updatedAtArray = array();



            // 取得した商品を購入した買い手ユーザーの数だけループ
            foreach ($product->users as $user) {
                Log::debug('$userの中身');
                Log::debug($user);
                Log::debug('$user->pivot->deleted_atの中身');
                Log::debug($user->pivot->deleted_at);

                // 購入テーブルの「updated_at」カラムの値を追加
                array_push($updatedAtArray, $user->pivot->updated_at);

            }


            // 購入履歴がある場合
            if(!empty($updatedAtArray)) {
                Log::debug('購入履歴有り');
                // 最新の購入日時のみ取得
                $latestUpdatedAt = max($updatedAtArray);
                Log::debug($latestUpdatedAt);
            }
            
            // 取得した商品を購入した買い手ユーザーの数だけループ
            foreach ($product->users as $user) {
                // 最新購入日時のレコードにて、購入キャンセルされてない(購入済み)場合
                if($latestUpdatedAt === $user->pivot->updated_at && $user->pivot->deleted_at === null) {
                        Log::debug('既に購入されています');
                        return response('既に購入されています', 202);
                }
            }

        }
        // 出品(売り手)ユーザーのメールアドレスを変数に入れる
        $shopEmail = $product->shop->email;
        Log::debug('出品ユーザーのメールアドレス');
        Log::debug($shopEmail);
        // 購入(買い手)ユーザーのメールアドレスを取得
        $userEmail = User::where('id', $request->userId)->first(['email'])->email;
        Log::debug('購入ユーザーのメールアドレス');
        Log::debug($userEmail);

        // トランザクション利用
        DB::beginTransaction();
        try {
            // 現在日時を取得
            $date = Carbon::now();
            // 購入テーブルに商品IDと買い手ユーザーIDを紐付けて、レコード登録
            $product->users()->attach($request->userId, ['created_at' => $date, 'updated_at' => $date]);
            // データーベースへ保存実行
            DB::commit();
            // メール内容に記載するデータをそれぞれ追加
            $product->buyDate = $date->format('Y年m月d日 H時i分s秒');
            $product->userName = $request->userName;
            $product->userEmail = $userEmail;

            // 作成したBuySuccessMailインスタンスを利用して出品者へメール送信
            Mail::to($shopEmail)->send(new BuySuccessMail($product, 'shop'));
            // 作成したBuySuccessMailインスタンスを利用して購入者へメール送信
            Mail::to($userEmail)->send(new BuySuccessMail($product, 'user'));
        } catch (\Exception $exception) {
            // データベースを保存前に戻す
            DB::rollBack();
            throw $exception;
        }
        
        Log::debug('実行完了');


        // 購入成功のため、レスポンスコード200(OK)を返却
        return response('購入手続きが完了しました。ご注文内容を記載したメールを登録メールアドレス宛にお送りしましたのでご確認下さい。', 200);
    }

    /**
     * バリデーション
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        Log::debug('バリデーション にかけるデータの中身');
        Log::debug($data);
        return Validator::make($data, [
            'image_1' => ['file','mimes:jpg,jpeg,png', new MegaBytes(1)],
            'image_2' => ['file','mimes:jpg,jpeg,png', new MegaBytes(1)],
            'image_3' => ['file','mimes:jpg,jpeg,png', new MegaBytes(1)],
            'image_4' => ['file','mimes:jpg,jpeg,png', new MegaBytes(1)],
            'image_5' => ['file','mimes:jpg,jpeg,png', new MegaBytes(1)],
            'product_name' => ['required','string','max:30'],
            'price' => ['required','numeric'],
            'best_day' => ['required','date_format:Y-m-d'],
            'best_time' => ['required','date_format:H:i'],
        ]);
    }

    /**
     * 購入フラグ判定
     * @param $product 商品, $loginUserId ログインユーザーID
     * @return \Illuminate\Http\Response
     */
    public function addBuyFlg($product, $loginUserId) {
        // 購入テーブルの「updated_at」カラムの値を格納する配列
        $updatedAtArray = array();
        $product->buy_flg = array('buy' => false, 'myBuy' => false);
        Log::debug('$productの中身');
        Log::debug($product);
        // 購入した(購入キャンセル含む)商品IDに紐づくユーザー情報をループ処理
        foreach ($product->users as $user) {
            Log::debug('$userの中身');
            Log::debug($user);
            // 購入テーブルの「updated_at」カラムの値を追加
            array_push($updatedAtArray, $user->pivot->updated_at);
        }
        Log::debug('$updatedAtArray中身');
        Log::debug($updatedAtArray);
        // 購入履歴がある場合
        if(!empty($updatedAtArray)) {
            Log::debug('購入履歴有り');
            // 最新の購入日時のみ取得
            $latestUpdatedAt = max($updatedAtArray);
            Log::debug($latestUpdatedAt);
        }

        foreach ($product->users as $user) {
            Log::debug('2回目foreachの$userの中身');
            Log::debug($user);
            Log::debug('$latestUpdatedAtの中身');
            Log::debug($latestUpdatedAt);
            Log::debug('$user->pivot->updated_atの中身');
            Log::debug($user->pivot->updated_at);
            Log::debug('$user->pivot->deleted_atの中身');
            Log::debug($user->pivot->deleted_at);
            // 最新購入日時のレコードにて、購入キャンセルされてない(購入済み)場合
            if($latestUpdatedAt === $user->pivot->updated_at && $user->pivot->deleted_at === null) {
                Log::debug('最新購入日時のレコードにて、購入キャンセルされてない!');
                // 購入済みフラグをtrue
                $product->buy_flg = array('buy' => true, 'myBuy' => false);
                Log::debug($product);
                // ログインユーザーが購入している場合
                if($user->id === $loginUserId) {
                    Log::debug('ログインユーザーが購入している!');
                    Log::debug('$user->id中身');
                    Log::debug($user->id);
                    // 自分の購入フラグをtrue
                    $product->buy_flg = array('buy' => true, 'myBuy' => true);
                }
            }
        }
    }
}

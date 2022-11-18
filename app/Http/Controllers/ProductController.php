<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduct; // バリデーションルール
use App\Models\Product; // 商品モデル
use App\Models\User; // 買い手ユーザーモデル
use App\Models\BoughtProduct; // 購入商品モデル
use App\Models\Prefecture; // 都道府県モデル
use App\Rules\MegaBytes; // 画像ファイルサイズルール
use App\Mail\BuySuccessMail; // 購入成功メールクラス
use Carbon\Carbon; // 日時を扱うクラス
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // メール送信の操作
use Illuminate\Support\Facades\Validator; // バリデーション作成
use Illuminate\Support\Facades\DB; // データベースの操作
use Illuminate\Support\Facades\Auth; // 認証済みユーザー
use Illuminate\Support\Facades\Storage; // 画像ファイルの操作

class ProductController extends Controller
{
    public function __construct()
    {
        // 売り手ユーザーの認証が必要(except配列内のアクションは例外)
        $this->middleware('auth:shop')->except(['showProductList', 'showProduct', 'buyProduct', 'buyCancelProduct', 'getPrefectureList']);
        // 買い手ユーザーの認証が必要(except配列内のアクションは例外)
        $this->middleware('auth:user')->except(['showProductList', 'showProduct', 'showSellProduct', 'sellProduct', 'editProduct', 'getPrefectureList', 'showBoughtProductList', 'showSellProductList', 'deleteProduct']);
    }

    /**
     * 商品一覧
     */
    public function showProductList(Request $request)
    {
        $params = $request->query();
        // TOPページ表示の場合
        if(!empty($params['pageName']) && $params['pageName'] === 'top') {
            $products = Product::with(['users', 'shop' => function ($query) {
                $query->with(['prefecture']);
            }])->orderBy('created_at', 'desc')->take(3)->get();
        // 商品一覧表示系の場合
        } else {
            $products = Product::filter($params)->with(['users', 'shop' => function ($query) {
                $query->with(['prefecture']);
            }])->orderBy('created_at', 'desc')->paginate(9);
        }
        
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
     * 購入された商品一覧
     */
    public function showBoughtProductList(Request $request)
    {
        $params = $request->query();
        
        // レスポンスで返す購入された商品リスト
        $products = [];
        // 購入された商品リスト
        $boughtProducts = [];
        // ログインユーザーのID保持
        $userId = $request->userId;

        $boughtProducts = BoughtProduct::whereIn('created_at', function($q) {
            return $q->from('bought_products')
                ->selectRaw('MAX(created_at) as created_at') // 購入日が最新の商品のみ取得
                ->groupBy('product_id'); // 重複した購入商品がある場合、1レコードのみ取得
        })
        ->where('deleted_at', NULL) // 削除フラグが無い(購入キャンセルしていない)購入商品のみ取得
        // 購入された商品の名前から、出品者IDがログイン中の売り手ユーザーIDと同じ商品のみ取得
        ->whereHas('product.shop', function($query) use($userId) {
            $query->where('id', $userId);
        })
        ->orderBy('created_at', 'DESC')
        ->pluck('product_id');

        // 取得した商品IDから商品情報を取得
        $products = Product::whereIn('id', $boughtProducts)->filter($params)->with(['users', 'shop' => function ($query) {
            $query->with(['prefecture']);
        }])
        // 購入日が最新順を保って取得
        ->orderByRaw('FIELD(id, "'.$boughtProducts->implode('","').'")')
        ->paginate(9);

        // 取得した商品の数けループ
        foreach ($products as $product) {
            // 購入フラグ付与
            $this->addBuyflg($product, $userId);
        }

        return $products;
    }

    /**
     * 出品した商品一覧
     */
    public function showSellProductList(Request $request)
    {
        $params = $request->query();

        // 出品した商品リスト
        $sellProducts = [];
        // ログインユーザーのID保持
        $userId = $request->userId;

        // 商品モデルから出品商品を最近出品した順に取得
        $sellProducts = Product::where('shop_id', $userId)->where('deleted_at', NULL)->filter($params)->with(['users', 'shop' => function ($query) {
            $query->with(['prefecture']);
        }])->orderBy('created_at', 'desc')->paginate(9);

        // 取得した商品の数けループ
        foreach ($sellProducts as $product) {
            // 購入フラグ付与
            $this->addBuyflg($product, $userId);
        }

        return $sellProducts;
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
        if(!empty($product)) {
            // 購入フラグ付与
            $this->addBuyFlg($product, $loginUserId);
        }
        

        return $product;
    }

    /**
     * 出品した商品詳細
     */
    public function showSellProduct($id)
    {
        // ログインユーザー
        $userId = Auth::user()->id;

        $product = Product::with('shop')->find($id);
        // 購入フラグ付与
        $this->addBuyflg($product, $userId);

        return $product;
    }

    /**
     * 商品出品
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function sellProduct(Request $request)
    {
        // 送られてきた内容のバリデーション
        $this->validator($request->all())->validate();

        // レスポンスで返す配列
        $response = array(
            'message' => '',
            'product' => ''
        );
        
        $product = new Product();
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

        // レスポンスで返す配列
        $response = array(
            'message' => '商品出品が正常に完了しました。',
            'product' => $product
        );

        // 新規作成のためレスポンスコードは201(CREATED)を返却
        return response($response, 201);
    }

    /**
     * 商品編集
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function editProduct(Request $request, $id)
    {
        // 送られてきた内容の画像以外、バリデーション実行
        $this->validator($request->only(['product_name', 'price', 'best_day', 'best_time']))->validate();

        $product = Product::find($id);
        // レスポンスで返す配列
        $response = array(
            'message' => '',
            'product' => ''
        );

        // 売り手ログインユーザー取得
        $shop = Auth::guard('shop')->user();
        // リクエストされたユーザーIDとログインユーザ-IDが一致しない場合
        if($shop->id !== $product->shop_id ) {
            $response["message"] = '不正な操作です。';
            return response($response, 400);
        }
        // 購入フラグ判定
        $this->addBuyFlg($product, $shop->id);
        // 購入済み商品の場合、
        if($product->buy_flg["buy"]) {
            // 編集できない
            $response["message"] = '購入済みのため編集できません。';
            return response($response, 400);
        }
        // 購入フラグ削除
        unset($product["buy_flg"]);

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

        // 登録可能画像数だけループ
        for ($i = 0; $i < 5; $i++) {
            $imgNumber = $i + 1;
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
                    // 新たに登録する画像なため、画像バリデーション実施
                    $validator = Validator::make($request->all(), [
                        "image_{$imgNumber}" => ['file','mimes:jpg,jpeg,png', new MegaBytes(1)],
                    ]);
                    // バリデーション失敗の場合
                    if ($validator->fails()) {
                        $errors["errors"] = '';
                        // 削除予定画像の数だけループ
                        for($i = 0; $i < count($deleteImages); $i++) {
                            // クラウドのストレージデータから変更したファイル名を元に戻す
                            $storage->move($deleteImages[$i], preg_replace('/delete_/', '', $deleteImages[$i]));
                        }
                         $errors["errors"] =  $validator->messages();
                        return response()->json($errors, 422);
                    }
                    
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

        /* データベースエラー時にファイル削除を行うため
           トランザクションを利用する */
        DB::beginTransaction();

        try {
            // productsテーブルの「shop_id」にログイン中の売り手ユーザーIDを紐付けて登録
            $shop->products()->save($product);
            // データーベースへ保存実行
            DB::commit();
            // クラウドのストレージデータから削除
            $storage->delete($deleteImages);
        } catch (\Exception $exception) {
            // データベースを保存前に戻す
            DB::rollBack();
            // 登録可能画像数だけループ
            for($i = 0; $i < count($createdImages); $i++) {
                // DBとの不整合を避けるためアップロードしたファイルをクラウドからも削除
                $storage->delete('product_images/'. $createdImages[$i]);
            }
            // 削除した画像の数だけループ
            for($i = 0; $i < count($deleteImages); $i++) {
                // クラウドのストレージデータから変更したファイル名を元に戻す
                $storage->move($deleteImages[$i], preg_replace('/delete_/', '', $deleteImages[$i]));
            }
            throw $exception;
        }

        // レスポンスで返す配列
        $response = array(
            'message' => '商品編集が正常に完了しました。',
            'product' => $product
        );

        // レスポンスコードは200(OK)を返却
        return response($response, 200);
    }

    /**
     * 商品削除
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function deleteProduct(Request $request, $id)
    {
        $product = Product::find($id);
         
        // 売り手ログインユーザー取得
        $shop = Auth::guard('shop')->user();
        // リクエストされたユーザーIDとログインユーザ-IDが一致しない場合
        if($shop->id !== $product->shop_id ) {
            return response('不正な操作です。', 400);
        }
        // 購入フラグ判定
        $this->addBuyFlg($product, $shop->id);
        // 購入済み商品の場合、
        if($product->buy_flg["buy"]) {
            // 削除できない
            return response('購入済みのため削除できません。', 400);
        }

        /* データベースエラー時にファイル削除を行うため
           トランザクションを利用する */
        DB::beginTransaction();

        try {
            // 商品を倫理削除
            $product->delete();
            // データーベースへ保存実行
            DB::commit();
        } catch (\Exception $exception) {
            // データベースを保存前に戻す
            DB::rollBack();
            throw $exception;
        }
       
        // レスポンスコード200(OK)とメッセージを返却
        return response('商品の削除が完了しました。再度出品する場合は、最初から出品手続きを行ってください。', 200);
    }

    /**
     * 商品購入
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function buyProduct(Request $request, $id)
    {
        // 購入する商品情報を取得
        $product = Product::where('id', $id)->with(['shop' => function ($query) {
            $query->with(['prefecture']);
        }])->first();

        // 購入テーブルにレコードが存在する場合
        if(!empty($product->users)) {

            // 購入テーブルの「updated_at」カラムの値を格納する配列
            $updatedAtArray = array();

            // 取得した商品を購入した買い手ユーザーの数だけループ
            foreach ($product->users as $user) {
                // 購入テーブルの「updated_at」カラムの値を追加
                array_push($updatedAtArray, $user->pivot->updated_at);
            }

            // 購入履歴がある場合
            if(!empty($updatedAtArray)) {
                // 最新の購入日時のみ取得
                $latestUpdatedAt = max($updatedAtArray);
            }
            
            // 取得した商品を購入した買い手ユーザーの数だけループ
            foreach ($product->users as $user) {
                // 最新購入日時のレコードにて、購入キャンセルされてない(購入済み)場合
                if($latestUpdatedAt === $user->pivot->updated_at && $user->pivot->deleted_at === null) {
                        return response('既に購入されています', 202);
                }
            }

        }
        // 出品(売り手)ユーザーのメールアドレスを変数に入れる
        $shopEmail = $product->shop->email;
        // 購入(買い手)ユーザーのメールアドレスを取得
        $userEmail = User::where('id', $request->userId)->first(['email'])->email;

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
        
        // レスポンスで返す商品データ
        $responseProduct = Product::where('id', $id)->with(['users', 'shop' => function ($query) {
            $query->with(['prefecture']);
        } ])->first();
        
        // 購入フラグ付与
        $this->addBuyflg($responseProduct, $request->userId);

        $response = array(
            'message' => '購入手続きが完了しました。ご注文内容を記載したメールを登録メールアドレス宛にお送りしましたのでご確認下さい。',
            'product' => $responseProduct
        );


        // 購入成功のため、レスポンスコード200(OK)を返却
        return $response;
    }

    /**
     * 商品購入キャンセル
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function buyCancelProduct(Request $request, $id) {
        // 購入キャンセルする商品情報を取得
        $product = Product::where('id', $id)->first();
        // トランザクション利用
        DB::beginTransaction();
        try {
            // 現在日時を取得
            $date = Carbon::now();
            // 購入テーブルに商品IDと買い手ユーザーIDを紐付けて、レコード登録
            $product->users()->attach($request->userId, ['deleted_at' => $date, 'created_at' => $date, 'updated_at' => $date]);
            // データーベースへ保存実行
            DB::commit();

        } catch (\Exception $exception) {
            // データベースを保存前に戻す
            DB::rollBack();
            throw $exception;
        }
        // レスポンスで返す商品データ
        $responseProduct = Product::where('id', $id)->with(['users', 'shop' => function ($query) {
            $query->with(['prefecture']);
        } ])->first();
        
        // 購入フラグ付与
        $this->addBuyflg($responseProduct, $request->userId);

        $response = array(
            'message' => '購入キャンセル手続きが完了しました。再購入する場合は、最初から購入手続きを行ってください。',
            'product' => $responseProduct
        );
        
        // 購入キャンセル成功のため、レスポンスコード200(OK)を返却
        return $response;
    }

    /**
     * 選択可能な都道府県リスト取得
     * @return \Illuminate\Http\Response
     */
    public function getPrefectureList() {
        $prefectures = Prefecture::whereHas('shops.products', function ($query) {
            $query->where('products.deleted_at', NULL)->where('shops.deleted_at', NULL);
        })->get();

        // 取得した都道府県を地方名ごとに分ける
        $selectPrefectures = [
            '北海道' => [],'東北' => [], '関東' => [], '北陸' => [], '甲信越' => [], '東海' => [],
            '関西' => [],'中国' => [], '四国' => [], '九州' => [], '沖縄' => []
        ];

        // 取得した都道府県の数だけループ
        foreach ($prefectures as $prefecture) {
            // 都道府県IDの範囲によって、地方ごとに分ける
            if($prefecture->id === 1) {
                array_push($selectPrefectures["北海道"], $prefecture);
            } else if($prefecture->id >= 2 && $prefecture->id <= 7 ) {
                array_push($selectPrefectures["東北"], $prefecture);
            } else if($prefecture->id >= 8 && $prefecture->id <= 14 ) {
                array_push($selectPrefectures["関東"], $prefecture);
            } else if($prefecture->id >= 15 && $prefecture->id <= 17 ) {
                array_push($selectPrefectures["北陸"], $prefecture);
            } else if($prefecture->id >= 18 && $prefecture->id <= 20 ) {
                array_push($selectPrefectures["甲信越"], $prefecture);
            } else if($prefecture->id >= 21 && $prefecture->id <= 24 ) {
                array_push($selectPrefectures["東海"], $prefecture);
            } else if($prefecture->id >= 25 && $prefecture->id <= 30 ) {
                array_push($selectPrefectures["関西"], $prefecture);
            } else if($prefecture->id >= 31 && $prefecture->id <= 35 ) {
                array_push($selectPrefectures["中国"], $prefecture);
            } else if($prefecture->id >= 36 && $prefecture->id <= 39 ) {
                array_push($selectPrefectures["四国"], $prefecture);
            } else if($prefecture->id >= 40 && $prefecture->id <= 46 ) {
                array_push($selectPrefectures["九州"], $prefecture);
            } else if($prefecture->id === 47) {
                array_push($selectPrefectures["沖縄"], $prefecture);
            }
        }

        // 最初に定義した地方の数だけループ
        foreach($selectPrefectures as $region => $prefectures) {
            // 地方の配列の中に取得した都道府県が1つも入ってない場合
            if(empty($prefectures)) {
                // 地方の要素ごと削除
                unset($selectPrefectures[$region]);
            }
        }

        return $selectPrefectures;

    }


    /**
     * バリデーション
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
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
        // 購入した(購入キャンセル含む)商品IDに紐づくユーザー情報をループ処理
        foreach ($product->users as $user) {
            // 購入テーブルの「updated_at」カラムの値を追加
            array_push($updatedAtArray, $user->pivot->updated_at);
        }
        // 購入履歴がある場合
        if(!empty($updatedAtArray)) {
            // 最新の購入日時のみ取得
            $latestUpdatedAt = max($updatedAtArray);
        }

        foreach ($product->users as $user) {
            // 最新購入日時のレコードにて、購入キャンセルされてない(購入済み)場合
            if($latestUpdatedAt === $user->pivot->updated_at && $user->pivot->deleted_at === null) {
                // 購入済みフラグをtrue
                $product->buy_flg = array('buy' => true, 'myBuy' => false);
                // ログインユーザーが購入している場合
                if($user->id === $loginUserId) {
                    // 自分の購入フラグをtrue
                    $product->buy_flg = array('buy' => true, 'myBuy' => true);
                }
            }
        }
        unset($product['users']);
    }
}

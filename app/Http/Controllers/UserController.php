<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth; //認証に関わる物を使う
use Illuminate\Support\Facades\DB; // データベースの操作
use Illuminate\Support\Facades\Hash; // ハッシュ化
use Illuminate\Support\Facades\Log; //ログ取得
use App\Models\BoughtProduct; // 購入モデル
use App\Models\Product; // 商品モデル
use App\Models\Prefecture; // 都道府県モデル
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // バリデーション作成
use Illuminate\Pagination\LengthAwarePaginator; // ページネーションクラス


class UserController extends Controller
{
    // ログイン中のユーザー情報を取得
    public function isLogin() {
      // 買い手ログインユーザー取得
      $user = Auth::user();
      // 買い手ログインユーザーがいない場合
      if(empty($user)) {
        // 売り手ログインユーザー取得
        $user = Auth::guard('shop')->user();
        // 売り手、買い手どちらも空の場合
        if(empty($user)) {
          // 空のユーザー情報を返す
          return $user;
        }
        // 都道府県情報一緒に返す
        $user["prefecture"] = $user->prefecture;
        // メールアドレスもjson形式で渡す
        $user->makeVisible(['email']);
        // 売り手ユーザー情報取得できた場合、{'type': 'shop'}を追加
        $user->type = 'shop';
        return $user;
      }
      // メールアドレスもjson形式で渡す
      $user->makeVisible(['email']);
      // 買い手ユーザー情報取得できた場合、{'type': 'user'}を追加
      $user->type = 'user';
      // ログインユーザー情報を返す
      return $user;
    }

    /**
     * マイページ表示
     * @param Request $request
     */
    public function showMypage(Request $request) {
      // レスポンスの商品データ
      $products = [];
      
      // ログインユーザーが買い手ユーザーの場合
      if($request->type === 'user') {
        // 購入商品リスト
        $buyProducts = [];
        // ログインユーザーのID保持
        $userId = $request->userId;
        // 購入モデルから購入商品を取得
        $buyProducts = BoughtProduct::select('product_id')->whereIn('updated_at', function($q) use($userId) {
            return $q->from('bought_products')
                ->where('user_id', $userId) // 購入された全商品からログインユーザーが購入した商品を絞り込み
                ->selectRaw('MAX(updated_at) as updated_at') // 購入日が最新の商品のみ取得
                ->groupBy('product_id'); // 重複した購入商品がある場合、1レコードのみ取得
        })
        ->where('deleted_at', NULL) // 削除フラグが無い(購入キャンセルしていない)購入商品のみ取得
        ->with(['product', 'product.shop' => function ($query) { // 商品詳細情報も併せて取得
            $query->with(['prefecture']);
        }])
        ->orderBy('updated_at', 'DESC') // 購入商品を購入日の降順(日付の最新順)に並び替え
        ->paginate(10);
        $products = $buyProducts;
      // ログインユーザーが売り手ユーザーの場合
      } else if($request->type === 'shop') {
        // 出品商品リスト
        $sellProducts = [];
        // 購入された商品リスト
        $boughtProducts = [];
        // ログインユーザーのID保持
        $userId = $request->userId;
        // 商品モデルから出品商品を最近出品した順に5件取得
        $sellProducts = Product::where('shop_id', $userId)
          ->where('deleted_at', NULL)
          ->orderBy('created_at','desc')
          ->take(5)
          ->get();
        if(!empty($sellProducts)) {
          $productController = new ProductController;
          // 取得した出品商品の数だけループ
          foreach ($sellProducts as $product) {
              $product->makeHidden(['best_time', 'best_day']);
              // 購入フラグ付与
              $productController->addBuyFlg($product, $userId);
          }
        }


        // 購入商品モデルから出品商品を最近出品した順に5件取得
        $boughtProducts = BoughtProduct::select('product_id')->whereIn('created_at', function($q)  {
            return $q->from('bought_products')
                ->selectRaw('MAX(created_at) as created_at') // 購入日が最新の商品のみ取得
                ->groupBy('product_id'); // 重複した購入商品がある場合、1レコードのみ取得
        })
        ->where('deleted_at', NULL) // 削除フラグが無い(購入キャンセルしていない)購入商品のみ取得
        // 購入された商品の名からから、出品者IDがログイン中の売り手ユーザーIDと同じ商品のみ取得
        ->whereHas('product.shop', function($query) use($userId) {
            $query->where('id', $userId);
        })
        ->with('product:id,name,price,image_1') // 商品詳細情報も併せて取得
        ->orderBy('created_at', 'DESC') // 購入された商品を購入日の降順(日付の最新順)に並び替え
        ->take(5) // 5件取得
        ->get();

        $products = [ 'sellProducts' => $sellProducts, 'boughtProducts' => $boughtProducts ];

      } else {
        // アクセス権なしのレスポンスを返す
        return response('', 404);
      }

      // ログインユーザーに紐づく商品情報を返す
      return $products;
    }

    /**
     * 買い手ユーザープロフィール編集
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function editProfile(Request $request)
    {
        // 買い手ログインユーザー取得
        $user = Auth::user();
        // リクエストされたユーザーIDとログインユーザ-IDが一致しない場合
        if($user->id != $request->id ) {
          return response('不正な操作です。', 400);
        }
        Log::debug("requestの中身");
        Log::debug($request);
        // バリデーション実行
        $request->validate([
            'name' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email,'.$request->id.',id',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;

        if($request->has('password')) {
          Log::debug("パスワードあり");
          $request->validate([
              'password' => 'required|string|min:6|confirmed',
          ]);
          $user->password = Hash::make($request->password);
        }
        Log::debug("バリデーションクリア");

        /* トランザクションを利用 */
        DB::beginTransaction();

        try {
            // データを登録
            $user->save();
            // データーベースへ保存実行
            DB::commit();
        } catch (\Exception $exception) {
            Log::debug('例外発生');
            // データベースを保存前に戻す
            DB::rollBack();
        
            throw $exception;
        }
        // ユーザー種別を渡す
        $user->type = 'user';
        // レスポンスで返す配列
        $response = array(
            'message' => 'プロフィールが正常に変更完了しました。',
            'user' => $user
        );

        // 登録したユーザー情報を返す
        return $response;
    }
    /**
     * 売り手ユーザープロフィール編集
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function editShopProfile(Request $request)
    {
        // 売り手ログインユーザー取得
        $shop = Auth::guard('shop')->user();
        // リクエストされたユーザーIDとログインユーザ-IDが一致しない場合
        if($shop->id != $request->id ) {
          return response('不正な操作です。', 400);
        }
        Log::debug("requestの中身");
        Log::debug($request);
        // バリデーション実行
        $request->validate([
            'name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'prefecture' => 'required|integer',
            'city' => 'required|string|max:255',
            'other_address' => 'nullable|string|max:255',
            'profile' => 'nullable|string|max:200',
            'email' => 'required|string|email|max:255|unique:shops,email,'.$request->id.',id',
        ]);
        $shop->name = $request->name;
        $shop->branch_name = $request->branch_name;
        $shop->prefecture_id = $request->prefecture;
        $shop->city = $request->city;
        $shop->other_address = $request->other_address;
        $shop->profile = $request->profile;
        $shop->email = $request->email;

        if($request->has('password')) {
          $request->validate([
              'password' => 'required|string|min:6|confirmed',
          ]);
          $shop->password = Hash::make($request->password);
        }
        Log::debug("バリデーションクリア");

        /* トランザクションを利用 */
        DB::beginTransaction();

        try {
            // データを登録
            $shop->save();
            // データーベースへ保存実行
            DB::commit();
        } catch (\Exception $exception) {
            Log::debug('例外発生');
            // データベースを保存前に戻す
            DB::rollBack();
        
            throw $exception;
        }
        // 都道府県情報一緒に返す
        $shop["prefecture"] = $shop->prefecture;
        // ユーザー種別を渡す
        $shop->type = 'shop';
        // レスポンスで返す配列
        $response = array(
            'message' => 'プロフィールが正常に変更完了しました。',
            'user' => $shop
        );

        // 登録したユーザー情報を返す
        return $response;
    }

    /**
     * 選択可能な都道府県リスト取得
     * @return \Illuminate\Http\Response
     */
    public function getAllPrefectureList() {
        Log::debug('都道府県リスト取得API実行');
        $prefectures = Prefecture::all();

        return $prefectures;

    }

    /**
     * バリデーション
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        Log::debug('バリデーションにかけるデータの中身');
        Log::debug($data);
        return Validator::make($data, [
            'name' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }


    
}

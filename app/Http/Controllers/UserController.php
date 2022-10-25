<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth; //認証に関わる物を使う
use Illuminate\Support\Facades\Log; //ログ取得
use App\Models\BoughtProduct; // 購入モデル
use App\Models\Product; // 商品モデル
use Illuminate\Http\Request;
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
        // 売り手ユーザー情報取得できた場合、{'type': 'shop'}を追加
        $user->type = 'shop';
        return $user;
      }
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
        ->paginate(5);
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
        $sellProducts = Product::where('shop_id', $userId)->where('deleted_at', NULL)->orderBy('created_at','desc')->take(5)->get();
        // 商品モデルから出品商品を最近出品した順に5件取得
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
}

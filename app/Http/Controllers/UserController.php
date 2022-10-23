<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth; //認証に関わる物を使う
use Illuminate\Support\Facades\Log; //ログ取得
use App\Models\BoughtProduct; // 購入モデル
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
      // 購入商品リスト
      $buyProducts = [];
      // ログインユーザーが買い手ユーザーの場合
      if($request->type === 'user') {
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
        ->with('product:id,name,price,image_1') // 商品詳細情報も併せて取得
        ->orderBy('updated_at', 'DESC') // 購入商品を購入日の降順(日付の最新順)に並び替え
        ->paginate(2);
      } else if($request->type === 'shop') {
        $products = [];
      } else {
        // アクセス権なしのレスポンスを返す
        return response('', 404);
      }
      $products = $buyProducts;

      // ログインユーザーに紐づく商品情報を返す
      return $products;
    }
}

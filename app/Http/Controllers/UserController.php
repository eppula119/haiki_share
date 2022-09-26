<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth; //認証に関わる物を使う
use Illuminate\Support\Facades\Log; //ログ取得
use Illuminate\Http\Request;


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
}

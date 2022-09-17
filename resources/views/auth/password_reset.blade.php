@extends('app')

@section('title', 'パスワード再発行(買い手)')

@section('content')
  <div class="p-authContainer">
    <h1 class="p-authContainer__title">
      パスワード再発行
    </h1>
    <form action="#" class="p-authContainer__form" method="post">
      <!------------------------ パスワード欄 ---------------------------->
      <label class="p-authLabel">パスワード(6文字以上)</label>
      <span class="p-authAttention">※必須</span>
      <!-- 入力フォーム -->
      <input type="text" class="p-authInput" type="password" name="password">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate">
        <ul>
          <li>※入力必須</li>
        </ul>
      </div>
      <!------------------------ 確認用パスワード欄 ---------------------------->
      <label class="p-authLabel">確認用パスワード</label>
      <span class="p-authAttention">※必須</span>
      <!-- 入力フォーム -->
      <input type="text" class="p-authInput" type="password" name="password_confirmation">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate">
        <ul>
          <li>※入力必須</li>
        </ul>
      </div>
      <!------------------------ 再設定ボタン ---------------------------->
      <input type="submit" class="p-authMainButton c-button c-button--bgBlue" value="パスワード再発行">
    </form>
  </div>
@endsection
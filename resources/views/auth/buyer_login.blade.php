@extends('app')

@section('title', 'ログイン(買い手)')

@section('content')
  <div class="p-authContainer">
    <h1 class="p-authContainer__title">
      ログイン(買い手)
    </h1>
    <form action="#" class="p-authContainer__form" method="post">
      <!------------------------ メールアドレス欄 ---------------------------->
      <label class="p-authLabel">メールアドレス</label>
      <span class="p-authAttention">※必須</span>
      <!-- 入力フォーム -->
      <input type="text" class="p-authInput" type="email" name="email">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate">
        <ul>
          <li>※入力必須</li>
        </ul>
      </div>
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
      <!------------------------ ログインボタン ---------------------------->
      <input type="submit" class="p-authMainButton c-button c-button--bgBlue" value="ログイン">
      <a href="#" class="p-passwordResetLink">パスワードを忘れた方</a>
      <p class="p-noAccountText">アカウントをお持ちでない方</p>
      <button class="p-authSubButton c-button c-button--bgWhite">新規登録(買い手)</button>
      <div class="p-otherPage">
        <a href="#" class="p-otherPage__link">→売り手のログインはこちら</a>
      </div>
    </form>
  </div>
@endsection
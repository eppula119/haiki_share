@extends('app')

@section('title', 'ログイン(売り手)')

@section('content')
  <div class="p-authContainer">
    <h1 class="p-authContainer__title">
      ログイン(売り手)
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
      <input type="submit" class="p-loginButton c-button" value="ログイン">
      <a href="#" class="p-passwordResetLink">パスワードを忘れた方</a>
      <p class="p-noAccountText">アカウントをお持ちでない方</p>
      <button class="p-registerButton c-button">新規登録(売り手)</button>
      <div class="p-otherPage">
        <a href="#" class="p-otherPage__link">→買い手のログインはこちら</a>
      </div>
    </form>
  </div>
@endsection
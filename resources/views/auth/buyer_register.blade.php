@extends('app')

@section('title', '新祈登録(買い手)')

@section('content')
  <div class="p-authContainer">
    <h1 class="p-authContainer__title">
      新規登録(買い手)
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
      <!------------------------ 新規登録ボタン ---------------------------->
      <input type="submit" class="p-authMainButton c-button c-button--bgBlue" value="新規登録">
      <div class="p-otherPage">
        <a href="#" class="p-otherPage__link">→売り手の新規登録はこちら</a>
      </div>
    </form>
  </div>
@endsection
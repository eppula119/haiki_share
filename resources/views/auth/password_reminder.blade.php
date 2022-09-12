@extends('app')

@section('title', 'パスワードリマインダー(買い手)')

@section('content')
  <div class="p-authContainer">
    <h1 class="p-authContainer__title">
      パスワード再発行
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
      <!------------------------ パスワード再発行メール送信ボタン ---------------------------->
      <input type="submit" class="p-passwordReminderButton c-button" value="送信">
      <div class="p-otherPage">
        <a href="#" class="p-otherPage__link">→戻る</a>
      </div>
    </form>
    <!------------------------ パスワード再発行メール送信後の画面 ---------------------------->
    <p class="p-authContainer__sendmailText">
      お送りしたメールのパスワード再発行リンクからパスワード再設定手続きを行ってください。
    </p>
  </div>
@endsection
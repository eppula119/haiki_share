@extends('app')

@section('title', 'プロフィール編集')

@section('content')
  <div class="p-userMainContainer">
    <h1 class="p-userMainContainer__title">
      プロフィール編集
    </h1>
    <div class="p-userMainContainer__formWrap">
      <form action="#" class="p-form" method="post">
      <!------------------------ プロフィール画像欄 ---------------------------->
      <div class="p-imgFormWrap">
        <img class="p-imgFormWrap__img" src="#">
      </div>
      <button class="p-imgChangeButton c-button">変更</button>
      <!------------------------ メールアドレス欄 ---------------------------->
      <label class="p-formLabel">メールアドレス</label>
      <span class="p-formAttention">※必須</span>
      <!-- 入力フォーム -->
      <input type="text" class="p-formInput" type="email" name="email">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-formValidate">
        <ul>
          <li>※入力必須</li>
        </ul>
      </div>
      <!------------------------ パスワード欄 ---------------------------->
      <button class="p-passwordChangeButton c-button">パスワード変更</button>
      <!------------------------ 現在のパスワード欄 ---------------------------->
      <label class="p-formLabel">現在のパスワード)</label>
      <span class="p-formAttention">※必須</span>
      <!-- 入力フォーム -->
      <input type="text" class="p-formInput" type="password" name="password-current">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-formValidate">
        <ul>
          <li>※入力必須</li>
        </ul>
      </div>
      <!------------------------ 新規パスワード欄 ---------------------------->
      <label class="p-formLabel">新規パスワード(6文字以上)</label>
      <span class="p-formAttention">※必須</span>
      <!-- 入力フォーム -->
      <input type="text" class="p-formInput" type="password" name="password-new">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-formValidate">
        <ul>
          <li>※入力必須</li>
        </ul>
      </div>
      <!------------------------ 確認用パスワード欄 ---------------------------->
      <label class="p-formLabel">確認用パスワード</label>
      <span class="p-formAttention">※必須</span>
      <!-- 入力フォーム -->
      <input type="text" class="p-formInput" type="password" name="password_confirmation">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-formValidate">
        <ul>
          <li>※入力必須</li>
        </ul>
      </div>
      <!------------------------ 自己紹介欄 ---------------------------->
      <label class="p-formLabel">自己紹介</label>
      <span class="p-formAttention">1/500</span>
      <!-- 入力フォーム -->
      <textarea class="p-formTextArea" name="introduction" cols="30" rows="10"></textarea>
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-formValidate">
        <ul>
          <li>※入力必須</li>
        </ul>
      </div>
      <!------------------------ プロフィール更新ボタン ---------------------------->
      <input type="submit" class="p-profileEditButton c-button" value="プロフィール更新">
    </form>
    </div>
    
  </div>
@endsection
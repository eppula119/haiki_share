@extends('app')

@section('title', '新祈登録(売り手)')

@section('content')
  <div class="p-authContainer">
    <h1 class="p-authContainer__title">
      新規登録(売り手)
    </h1>
    <form action="#" class="p-authContainer__form" method="post">
      <!------------------------ コンビニ名欄 ---------------------------->
      <label class="p-authLabel">コンビニ名</label>
      <span class="p-authAttention">※必須</span>
      <!-- 入力フォーム -->
      <input type="text" class="p-authInput" type="text" name="store-name">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate">
        <ul>
          <li>※入力必須</li>
        </ul>
      </div>
      <!------------------------ 支店名欄 ---------------------------->
      <label class="p-authLabel">支店名</label>
      <span class="p-authAttention">※必須</span>
      <!-- 入力フォーム -->
      <input type="text" class="p-authInput" type="text" name="branch-name">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate">
        <ul>
          <li>※入力必須</li>
        </ul>
      </div>
      <!------------------------ 都道府県欄 ---------------------------->
      <label class="p-authLabel">都道府県</label>
      <span class="p-authAttention">※必須</span>
      <!-- 入力フォーム -->
      <select class="p-authSelect" name="prefecture">
        <option value="北海道">北海道</option>
        <option value="青森県">青森県</option>
        <option value="岩手県">岩手県</option>
      </select>
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate">
        <ul>
          <li>※入力必須</li>
        </ul>
      </div>
      <!------------------------ 市町村名・番地欄 ---------------------------->
      <label class="p-authLabel">市町村名・番地</label>
      <span class="p-authAttention">※必須</span>
      <!-- 入力フォーム -->
      <input type="text" class="p-authInput" type="text" name="city">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate">
        <ul>
          <li>※入力必須</li>
        </ul>
      </div>
      <!------------------------ 建物名・部屋番号欄 ---------------------------->
      <label class="p-authLabel">建物名・部屋番号</label>
      <!-- 入力フォーム -->
      <input type="text" class="p-authInput" type="text" name="building-name">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate">
        <ul>
          <li>※入力必須</li>
        </ul>
      </div>
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
        <a href="#" class="p-otherPage__link">→買い手の新規登録はこちら</a>
      </div>
    </form>
  </div>
@endsection
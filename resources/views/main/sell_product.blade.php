@extends('app')

@section('title', '商品出品')

@section('content')
  <div class="p-userMainContainer">
    <h1 class="p-userMainContainer__title">
      商品出品
    </h1>
    <div class="p-userMainContainer__formWrap">
      <form action="#" class="p-form" method="post">
        <!------------------------ 出品画像欄 ---------------------------->
        <p class="p-sellDesc">出品画像(最大5枚)</p>
        @for ($i = 0; $i < 5; $i++)
          <div class="p-uploadedImgBlock">
            <button class="p-uploadedImgBlock__closeButton c-button">+</button>
            <img src="#" class="p-uploadedImgBlock__img">
          </div>
        @endfor
        <div class="p-uploadPointBlock">
          <input type="file" class="p-uploadPointBlock__button c-button" name="product-img">
          <p class="p-uploadPointBlock__text">ファイルをドラッグ＆ドロップもしくは</p>
        </div>
        <h2 class="p-formDesc">商品詳細</h2>
        <!------------------------ 商品名欄 ---------------------------->
        <label class="p-formLabel">商品名</label>
        <span class="p-formAttention">※必須</span>
        <!-- 入力フォーム -->
        <input type="text" class="p-formInput" type="text" name="product-name">
        <!-- バリデーションエラーメッセージ表示箇所 -->
        <div class="p-formValidate">
          <ul>
            <li>※入力必須</li>
          </ul>
        </div>
        <!------------------------ 価格欄 ---------------------------->
        <label class="p-formLabel">価格(¥300 ~ 9,999,999)</label>
        <span class="p-formAttention">※必須</span>
        <!-- 入力フォーム -->
        <input type="number" class="p-formInput" type="text" name="price" placeholder="¥">
        <!-- バリデーションエラーメッセージ表示箇所 -->
        <div class="p-formValidate">
          <ul>
            <li>※入力必須</li>
          </ul>
        </div>
        <!------------------------ 賞味期限欄 ---------------------------->
        <label class="p-formLabel">賞味期限</label>
        <span class="p-authAttention">※必須</span>
        <!-- 入力フォーム -->
        <input type="text" id="datepicker"  class="p-formInput"name="best-before">
        <!-- バリデーションエラーメッセージ表示箇所 -->
        <div class="p-formValidate">
          <ul>
            <li>※入力必須</li>
          </ul>
        </div>
        <!------------------------ 出品ボタン ---------------------------->
        <input type="submit" class="p-sellButton c-button" value="出品する">
      </form>
    </div>
  </div>
@endsection
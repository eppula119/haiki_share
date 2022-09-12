@extends('app')

@section('title', 'マイページ(売り手)')

@section('content')
  <div class="p-userMainContainer">
    <h1 class="p-userMainContainer__title">
      マイページ
    </h1>
    <!------------------------ プロフィール編集画面遷移欄 ---------------------------->
    <section class="p-userMainContainer__profileEditWrap">
      <a class="p-profileLink c-routerLink" href="#">プロフィール編集</a>
    </section>
    <!------------------------ 出品した商品一覧画面遷移欄 ---------------------------->
    <section class="p-userMainContainer__productWrap">
      <p class="p-subTitle">出品した商品一覧</p>
      @for ($i = 0; $i < 5; $i++)
        <div class="p-productWrap">
          <img src="#" class="p-productWrap__img">
          <div class="p-productWrap__detail">
            <p class="p-productTitle">ガリガリ君リッチ！</p>
            <p class="productPrice"><span class="c-textRed">¥</span>2,000</p>
          </div>
          <div class="p-buttonWrap">
            <a class="p-buttonWrap__button c-button" href="#">詳細を見る</a>
            <a class="p-buttonWrap__button c-button" href="#">商品情報を編集</a>
          </div>
        </div>
      @endfor
      <a href="#" class="p-allShowLink c-button">全件表示</a>
    </section>
    <!------------------------ 購入された商品一覧画面遷移欄 ---------------------------->
    <section class="p-userMainContainer__productWrap">
      <p class="p-subTitle">購入された商品一覧</p>
      @for ($i = 0; $i < 5; $i++)
        <a class="p-productLink" href="#">
          <div class="p-productWrap">
            <img src="#" class="p-productWrap__img">
            <div class="p-productWrap__detail">
              <p class="p-productTitle">ガリガリ君リッチ！</p>
              <p class="productPrice"><span class="c-textRed">¥</span>2,000</p>
            </div>
            <span class="p-linkText">＞</span>
          </div>
        </a>
      @endfor
      <a href="#" class="p-allShowLink c-button">全件表示</a>
    </section>
    <!------------------------ 出品画面遷移欄 ---------------------------->
    <div class="p-userMainContainer__floatingButtonWrap">
      <a href="#" class="p-sellLink">出品</a>
    </div>
  </div>
@endsection
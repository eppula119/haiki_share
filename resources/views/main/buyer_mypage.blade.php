@extends('app')

@section('title', 'マイページ(買い手)')

@section('content')
  <div class="p-userMainContainer">
    <h1 class="p-userMainContainer__title">
      マイページ
    </h1>
    <!------------------------ プロフィール編集画面遷移欄 ---------------------------->
    <section class="p-userMainContainer__profileEditWrap">
      <a class="p-profileLink c-routerLink" href="#">プロフィール編集</a>
    </section>
    <!------------------------ 購入した商品一覧画面遷移欄 ---------------------------->
    <section class="p-userMainContainer__productWrap">
      <p class="p-subTitle">購入した商品一覧</p>
      @for ($i = 0; $i < 5; $i++)
        <div class="p-productWrap">
          <img src="{{ asset('images/item.png') }}" class="p-productWrap__img">
          <div class="p-productWrap__detail">
            <p class="p-productTitle">ガリガリ君リッチ！</p>
            <p class="productPrice"><span class="c-textRed">¥</span>2,000</p>
          </div>
          <div class="p-buttonWrap">
            <a class="p-buttonWrap__button c-button c-button--bgBlue" href="#">詳細を見る</a>
            <a class="p-buttonWrap__button c-button c-button--bgWhite" href="#">購入をキャンセル</a>
          </div>
        </div>
      @endfor
    </section>
    <!------------------------ ページネーション欄 ---------------------------->
    <div class="p-userMainContainer__paginationWrap">
        <ul class="c-paginationWrap">
        <li class="c-paginationWrap__number c-paginationWrap__number--inactive">
          <a href="#" class="c-paginationLink">«</a>
        </li>
        @for ($i = 0; $i < 1; $i++)
          <li class="c-paginationWrap__number">
            <a href="#" class="c-paginationLink">{{ $i }}</a>
          </li>
        @endfor
        <!-- <li class="c-paginationWrap__number  c-paginationWrap__number--inactive c-paginationWrap__number--disabled">
          <a href="#" class="c-paginationLink">...</a>
        </li> -->
        <li class="c-paginationWrap__number">
          <a href="#" class="c-paginationLink c-paginationLink--active">7</a>
        </li>
        <!-- <li class="c-paginationWrap__number c-paginationWrap__number--inactive c-paginationWrap__number--disabled">
          <a href="#" class="c-paginationLink">...</a>
        </li> -->
        @for ($i = 3; $i < 4; $i++)
          <li class="c-paginationWrap__number">
            <a href="#" class="c-paginationLink">{{ $i }}</a>
          </li>
        @endfor
        <li class="c-paginationWrap__number c-paginationWrap__number--inactive">
          <a href="#" class="c-paginationLink">»</a>
        </li>
      </ul>
    </div>
  </div>
@endsection
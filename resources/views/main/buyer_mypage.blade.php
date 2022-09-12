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
          <img src="#" class="p-productWrap__img">
          <div class="p-productWrap__detail">
            <p class="p-productTitle">ガリガリ君リッチ！</p>
            <p class="productPrice"><span class="c-textRed">¥</span>2,000</p>
          </div>
          <div class="p-buttonWrap">
            <a class="p-buttonWrap__button c-button" href="#">詳細を見る</a>
            <a class="p-buttonWrap__button c-button" href="#">購入をキャンセル</a>
          </div>
        </div>
      @endfor
    </section>
    <!------------------------ ページネーション欄 ---------------------------->
    <div class="p-userMainContainer__paginationWrap">
        <ul class="c-paginationWrap">
          <li class="c-paginationWrap__number c-paginationWrap__number--inactive">«
          </li>
          @for ($i = 0; $i < 5; $i++)
            <li class="c-paginationWrap__number">{{ $i }}
            </li>
          @endfor
          <li class="c-paginationWrap__number  c-paginationWrap__number--inactive c-paginationWrap__number--disabled">...
          </li>
          <li class="c-paginationWrap__number">{{ $i }}
          </li>
          <li class="c-paginationWrap__number c-paginationWrap__number--inactive c-paginationWrap__number--disabled">...
          </li>
          @for ($i = 8; $i < 12; $i++)
            <li class="c-paginationWrap__number">{{ $i }}
            </li>
          @endfor
          <li class="c-paginationWrap__number c-paginationWrap__number--inactive">»
          </li>
        </ul>
    </div>
  </div>
@endsection
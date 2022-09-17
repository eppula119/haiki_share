@extends('app')

@section('title', '商品一覧')

@section('content')
  <!------------------------ 絞り込み欄 ---------------------------->
  <section class="c-filterWrap">
    <!------------------------ モーダル欄 ---------------------------->
    <div class="c-modalOverlay"></div>
    <div class="c-modal">
      <div class="c-modal__content">
        <!------------------------ 都道府県の絞り込みモーダル欄 ---------------------------->
        <span class="c-contentHead">都道府県</span>
        <div class="c-pullDownCheckWrap">
          <div class="c-pullDownCheckWrap__block">
            <label for="c-pullDownCheckItem1" class="c-pullDownCheckItem">
              <input type="checkbox" id="c-pullDownCheckItem1" class="c-contentCheckbox">北海道
            </label>
            <span class="c-pullDownIcon">↓</span>
          </div>
          <label for="c-pullDownCheckItem2" class="c-pullDownCheckWrap__childItem">
            <input type="checkbox" id="c-pullDownCheckItem2" class="c-contentCheckbox">北海道
          </label>
          <div class="c-pullDownCheckWrap__block">
            <label for="c-pullDownCheckItem3" class="c-pullDownCheckItem">
              <input type="checkbox" id="c-pullDownCheckItem3" class="c-contentCheckbox">東北
            </label>
            <span class="c-pullDownIcon">↓</span>
          </div>
          <label for="c-pullDownCheckItem4" class="c-pullDownCheckWrap__childItem">
            <input type="checkbox" id="c-pullDownCheckItem4" class="c-contentCheckbox">青森県
          </label>
          <label for="c-pullDownCheckItem5" class="c-pullDownCheckWrap__childItem">
            <input type="checkbox" id="c-pullDownCheckItem5" class="c-contentCheckbox">岩手県
          </label>
        </div>

        <!------------------------ 価格の絞り込みモーダル欄 ---------------------------->
        <span class="c-contentHead">価格</span>
        <div class="c-contentWrap">
          <input type="text" class="c-contentWrap__input" value="">
          <span class="c-contentWrap__text">-</span>
          <input type="text" class="c-contentWrap__input" value="">
        </div>
        <!------------------------ 賞味期限の絞り込みモーダル欄 ---------------------------->
        <span class="c-contentHead">賞味期限</span>
        <div class="c-checkboxWrap">
          <label for="expired" class="c-checkboxWrap__label">
            <input type="checkbox" id="expired" class="c-checkboxInput">賞味期限切れ
          </label>
          <label for="bestBefore" class="c-checkboxWrap__label">
            <input type="checkbox" id="bestBefore" class="c-checkboxInput">賞味期限内
          </label>
        </div>
      </div>
      <div class="c-modal__buttonWrap">
        <button class="c-modalDoButton c-button c-button--bgGray">キャンセル</button>
        <button class="c-modalDoButton c-button c-button--bgBlue">保存</button>
      </div>
    </div>
    <!------------------------ 検索欄 ---------------------------->
    <button class="c-filterWrap__showFilterButton c-button c-button--bgWhite">商品検索</button>
    <form class="c-filterWrap__keywordForm" method="POST">
      <input type="text" class="c-searchInput" name="keyword">
      <input type="submit" class="c-doSearchButton c-button c-button--bgWhite" value="検索">
    </form>
    <div class="c-filterWrap__categoryWrap">
      <div class="c-filterRow">
        <span class="c-filterLead">絞り込み</span>
        <button class="c-showFilterButton">都道府県</button>
        <button class="c-showFilterButton">価格</button>
        <button class="c-showFilterButton">賞味期限</button>
      </div>
    </div>
  </section>
  <!------------------------ 商品欄 ---------------------------->
  <section class="p-productContainer">
    <p class="p-productContainer__lead">東京都、1200円~1500円、賞味期限切れの検索結果：50件
    </p>
    <div class="p-productContainer__wrap">
      @for ($i = 0; $i < 10; $i++)
      <div class="p-itemWrap">
        <img src="{{ asset('images/item.png') }}" class="p-itemWrap__img">
        <div class="p-itemWrap__detailWrap">
          <p class="p-itemTitle">
            ガリガリ君リッチ！
          </p>
          <p class="p-itemPrefectureText">↑ 北海道</p>
          <p class="p-itemBestBeforeText">賞味期限  2022年9月14日まで</p>
          <p class="p-itemPrice">5,900<span class="p-itemPrice__label">円(税込)</span>
          </p>
        </div>
        <a href="#" class="p-itemWrap__link"><span class="p-itemLinkText">詳細を見る＞</span></a>
      </div>
      @endfor
    </div>
    <!------------------------ ページネーショ欄 ---------------------------->
    <div class="p-productContainer__paginationWrap">
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
  </section>
@endsection
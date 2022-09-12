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

        <input type="checkbox" class="c-contentCheckbox">
        <label for="js-dropTarget">北海道</label>
          <input type="checkbox" id="js-dropTarget" autocomplete="off" style="display: none;">
          <div class="c-itemWrap">
            <input type="checkbox" id="js-checkTarget" autocomplete="off">
            <label for="c-itemWrap__item">北海道</label>
            <input type="checkbox" id="c-itemWrap__item" style="display: none;">
          </div>

        <input type="checkbox" class="c-contentCheckbox">
        <label for="js-dropTarget">東北</label>
          <input type="checkbox" id="js-dropTarget" autocomplete="off" style="display: none;">
          <div class="c-itemWrap">
            <input type="checkbox" id="js-checkTarget" autocomplete="off">
            <label for="c-itemWrap__item">青森県</label>
            <input type="checkbox" id="c-itemWrap__item" style="display: none;">
            <input type="checkbox" id="js-checkTarget" autocomplete="off">
            <label for="c-itemWrap__item">岩手県</label>
            <input type="checkbox" id="c-itemWrap__item" style="display: none;">
          </div>

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
      <input type="checkbox" id="js-checkTarget" autocomplete="off">
      <label for="expired">賞味期限切れ</label>
      <input type="checkbox" id="expired" style="display: none;">
      <input type="checkbox" id="js-checkTarget" autocomplete="off">
      <label for="bestBefore">賞味期限内</label>
      <input type="checkbox" id="bestBefore" style="display: none;">

      <div class="c-modal__buttonWrap">
        <button class="c-modalDoButton c-button">キャンセル</button>
        <button class="c-modalDoButton c-button">保存</button>
      </div>
    </div>
    <!------------------------ 検索欄 ---------------------------->
    <button class="c-filterWrap__showFilterButton c-button">商品検索</button>
    <form class="c-filterWrap__keywordForm" method="POST">
      <input type="text" class="c-searchInput" name="keyword">
      <input type="submit" class="c-doSearchButton c-button" value="検索">
    </form>
    <div class="c-filterWrap__categoryWrap">
      <span class="c-filterLead">絞り込み</span>
      <button class="c-showFilterButton">都道府県</button>
      <button class="c-showFilterButton">価格</button>
      <button class="c-showFilterButton">賞味期限</button>
    </div>
  </section>
  <!------------------------ 商品欄 ---------------------------->
  <section class="p-productContainer">
    <p class="p-productContainer__lead">東京都、1200円~1500円、賞味期限切れの検索結果：50件
    </p>
    @for ($i = 0; $i < 5; $i++)
    <div class="p-itemWrap">
      <a href="#" class="p-itemWrap__link">詳細を見る＞</a>
      <img src="#" class="p-itemWrap__img">
      <div class="p-itemWrap__detailWrap">
        <p class="p-itemTitle">
          ガリガリ君リッチ！
        </p>
        <p class="p-itemPrefectureText">↑ 北海道</p>
        <p class="p-itemBestBeforeText">賞味期限  2022年9月14日まで</p>
        <p class="p-itemPrice">5,900円
          <span class="p-itemPrice__label">円(税込)</span>
        </p>
      </div>
    </div>
    @endfor
    <!------------------------ ページネーショ欄 ---------------------------->
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
  </section>
@endsection
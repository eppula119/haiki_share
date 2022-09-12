@extends('app')

@section('title', 'トップ')

@section('content')
  <!------------------------ ヒーローイメージ欄 ---------------------------->
  <section class="p-heroImg">
    <p class="p-heroImg__text">
      すべての「食べて」を、
      食べ手につなぐ。<br /><br />
      つくり手の想いを。最後の１食
      まで食べ手に届ける。
      コンビニフードシェアリングサービス
      haiki-shareが、コンビニ業界の食品ロスを
      おいしく解消します。
    </p>
  </section>
  <!------------------------ サービス紹介欄 ---------------------------->
  <section class="p-introduction">
    <h2 class="p-introduction__name">Haiki Share</h2>
    <p class="p-introduction__summary">コンビニ廃棄シェアサービス</p>
    <p class="p-introduction__desc">コンビニ廃棄商品にて「もったいない」、「買いたい」を叶えたサービスです。</p>
  </section>
  <!------------------------ 商品リスト欄 ---------------------------->
  <section class="p-productList">
    <h2 class="p-productList__title">Pick Up！</h2>
    @for ($i = 0; $i < 3; $i++)
    <div class="p-productWrap">
      <a href="#" class="p-productWrap__link">詳細を見る＞</a>
      <img src="#" class="p-productWrap__img">
      <div class="p-productWrap__detailWrap">
        <p class="p-productTitle">
          ガリガリ君リッチ！
        </p>
        <p class="p-productPrefectureText">↑ 北海道</p>
        <p class="p-productBestBeforeText">賞味期限  2022年9月14日まで</p>
        <p class="p-productPrice">5,900円
          <span class="p-productPrice__label">円(税込)</span>
        </p>
      </div>
    </div>
    @endfor
    <button class="p-productList__button c-button">商品一覧を見る</button>
  </section>
  <!------------------------ サービス概要欄 ---------------------------->
  <section class="p-about">
    <div class="p-about__card">
      <img src="#" class="p-aboutImg">
      <h2 class="p-aboutHead">お近くのコンビニからお届け</h2>
      <p class="p-aboutText">大手のコンビニから地元の<br />コンビニまで幅広く登録料無料で<br />利用可能です。</p>
    </div>
    <div class="p-about__card">
      <img src="#" class="p-aboutImg">
      <h2 class="p-aboutHead">スマホから簡単注文！</h2>
      <p class="p-aboutText">パソコンからはもちろん、<br />スマートフォンからも簡単操作で<br />気軽に注文ができます。</p>
    </div>
    <div class="p-about__card">
      <img src="#" class="p-aboutImg">
      <h2 class="p-aboutHead">コンビニ側も嬉しい！</h2>
      <p class="p-aboutText">コンビニ側も登録料無料で簡単に<br />利用可能です。<br />今まで捨てるのみだった、<br />廃棄食品で売り上げUP!</p>
    </div>
  </section>
  <!------------------------ サブイメージ欄 ---------------------------->
  <section class="p-subImg">
    <h2 class="p-subImg__text">
      もったいないを<br />
      たべて<br />
      うれしい<br />
    </h2>
    <p class="p-subImgDesc">
      今まで廃棄していた食品がどんどん<br />
      人々を笑顔に。廃棄食品を通して<br />
      人の輪をつなげ地球を救う。
    </p>
  </section>
  <!------------------------ 利用ボタン欄 ---------------------------->
  <section class="p-startBlock">
    <h1 class="p-startBlock__title">Haiki Share</h1>
    <span class="p-startBlock__summary">〜廃棄食品で世界を救う〜</span>
    <button class="p-startBlock__button c-button">買い手として利用</button>
    <button class="p-startBlock__button c-button">売り手として利用</button>
  </section>
@endsection
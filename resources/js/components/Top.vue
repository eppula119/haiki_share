<template>
  <div>
    <!------------------------ ヒーローイメージ欄 ---------------------------->
  <section class="p-heroImg">
    <p class="p-heroImg__text">
      すべての「食べて」を<br />
      食べ手につなぐ。
      <span class="p-heroImgTextSmall">
        つくり手の想いを。最後の１食まで食べ手に届ける。<br />
        コンビニフードシェアリングサービスhaiki-shareが<br />
        コンビニ業界の食品ロスを<br />
        おいしく解消します。
      </span>
    </p>
  </section>
  <!------------------------ サービス紹介欄 ---------------------------->
  <section class="p-introduction">
    <h2 class="p-introduction__name">Haiki Share</h2>
    <p class="p-introduction__summary">コンビニ廃棄シェアサービス</p>
    <p class="p-introduction__desc">コンビニ廃棄商品にて「もったいない」、「買いたい」を叶えたサービスです。</p>
  </section>
  <!------------------------ 商品リスト欄 ---------------------------->
  <section class="p-productContainerTop p-productContainer">
    <h2 class="p-productContainerTop__title">Pick Up！</h2>
    <div class="p-productContainer__wrap p-productContainerTop__wrap">
      
      <div class="p-itemWrap" v-for="n of 3" :key="n">
        <img src="/images/item.png" class="p-itemWrap__img">
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

    </div>
    <div class="p-productContainerTop__buttonBlock">
      <button class="p-productTopButton c-button c-button--bgBlue">商品一覧を見る</button>
    </div>
  </section>
  <!------------------------ サービス概要欄 ---------------------------->
  <section class="p-about">
    <div class="p-about__card">
      <img src="/images/top_convini.jpeg" class="p-aboutImg">
      <h2 class="p-aboutHead">お近くのコンビニからお届け</h2>
      <p class="p-aboutText">大手のコンビニから地元のコンビニまで幅広く登録料無料で利用可能です。</p>
    </div>
    <div class="p-about__card">
      <img src="/images/top_order.jpeg" class="p-aboutImg">
      <h2 class="p-aboutHead">スマホから簡単注文！</h2>
      <p class="p-aboutText">パソコンからはもちろん、スマートフォンからも簡単操作で気軽に注文ができます。</p>
    </div>
    <div class="p-about__card">
      <img src="/images/top_shopper.jpeg" class="p-aboutImg">
      <h2 class="p-aboutHead">コンビニ側も嬉しい！</h2>
      <p class="p-aboutText">コンビニ側も登録料無料で簡単に利用可能です。今まで捨てるのみだった、廃棄食品で売り上げUP!</p>
    </div>
  </section>
  <!------------------------ サブイメージ欄 ---------------------------->
  <section class="p-subImg">
    <p class="p-subImg__text">
      もったいないを<br />
      たべて<br />
      うれしい<br />
      <span class="p-subImgTextSmall">
        今まで廃棄していた食品がどんどん<br />
        人々を笑顔に。廃棄食品を通して<br />
        人の輪をつなげ地球を救う。
      </span>
    </p>
  </section>
  <!------------------------ 利用ボタン欄 ---------------------------->
  <section class="p-startBlock">
    <h1 class="p-startBlock__title">Haiki Share</h1>
    <span class="p-startBlock__summary">〜廃棄食品で世界を救う〜</span>
    <button class="p-startBlock__button c-button c-button--bgBlue">買い手として利用</button>
    <button class="p-startBlock__button c-button c-button--bgBlue">売り手として利用</button>
  </section>
  </div>
</template>

<script>


export default {
  data: function() {
    return {
    };
  },
  methods: {
  }
};
</script>
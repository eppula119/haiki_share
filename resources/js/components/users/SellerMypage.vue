<template>
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
        <div class="p-productWrap" v-for="n of 5" :key="n">
          <img src="/images/item.png" class="p-productWrap__img">
          <div class="p-productWrap__detail">
            <p class="p-productTitle">ガリガリ君リッチ！</p>
            <p class="productPrice"><span class="c-textRed">¥</span>2,000</p>
          </div>
          <div class="p-buttonWrap">
            <a class="p-buttonWrap__button c-button c-button--bgBlue" href="#">詳細を見る</a>
            <a class="p-buttonWrap__button c-button c-button--bgWhite" href="#">商品情報を編集</a>
          </div>
        </div>
      <div class="p-allShowLink">
        <a href="#" class="p-allShowLink__button c-button c-button--bgWhite">全件表示</a>
      </div>
    </section>
    <!------------------------ 購入された商品一覧画面遷移欄 ---------------------------->
    <section class="p-userMainContainer__productWrap">
      <p class="p-subTitle">購入された商品一覧</p>
        <a class="p-productLink" href="#" v-for="n of 5" :key="n">
          <div class="p-productWrap">
            <img src="/images/item.png" class="p-productWrap__img">
            <div class="p-productWrap__detail">
              <p class="p-productTitle">ガリガリ君リッチ！</p>
              <p class="productPrice"><span class="c-textRed">¥</span>2,000</p>
            </div>
            <span class="p-linkText">＞</span>
          </div>
        </a>
      <div class="p-allShowLink">
        <a href="#" class="p-allShowLink__button c-button c-button--bgWhite">全件表示</a>
      </div>
    </section>
    <!------------------------ 出品画面遷移欄 ---------------------------->
    <div class="p-userMainContainer__floatingButtonWrap">
      <a href="#" class="p-sellLink c-button c-button--bgWhite">出品</a>
    </div>
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
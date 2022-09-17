<template>
  <div>
    <!------------------------ 絞り込み欄 ---------------------------->
    <section class="c-filterWrap">
      <!------------------------ モーダル欄 ---------------------------->
      <Modal></Modal>
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
        <div class="p-itemWrap" v-for="n of 10" :key="n">
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
      <!------------------------ ページネーショ欄 ---------------------------->
      <div class="p-productContainer__paginationWrap">
        <ul class="c-paginationWrap">
          <li class="c-paginationWrap__number c-paginationWrap__number--inactive">
            <a href="#" class="c-paginationLink">«</a>
          </li>
            <li class="c-paginationWrap__number" v-for="n of 1" :key="n">
              <a href="#" class="c-paginationLink">{{ n }}</a>
            </li>
          <li class="c-paginationWrap__number">
            <a href="#" class="c-paginationLink c-paginationLink--active">7</a>
          </li>
            <li class="c-paginationWrap__number" v-for="n of 2" :key="n">
              <a href="#" class="c-paginationLink">{{ n + 2 }}</a>
            </li>
          <li class="c-paginationWrap__number c-paginationWrap__number--inactive">
            <a href="#" class="c-paginationLink">»</a>
          </li>
        </ul>
      </div>
    </section>
  </div>
</template>

<script>
// ページネーションコンポーネント読み込み
import Modal from "../Modal.vue";

export default {
  components: {
    Modal,
  },
  data: function() {
    return {
    };
  },
  methods: {
  }
};
</script>
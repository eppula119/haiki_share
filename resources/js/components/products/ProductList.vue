<template>
  <div>
    <!------------------------ 絞り込み欄 ---------------------------->
    <section class="c-filterWrap">
      <!------------------------ モーダル欄 ---------------------------->
      <Modal type="filter" ref="modal"></Modal>
      <!------------------------ 検索欄 ---------------------------->
      <button class="c-filterWrap__showFilterButton c-button c-button--bgWhite">商品検索</button>
      <form class="c-filterWrap__keywordForm" method="POST">
        <input type="text" class="c-searchInput" name="keyword">
        <input type="submit" class="c-doSearchButton c-button c-button--bgWhite" value="検索">
      </form>
      <div class="c-filterWrap__categoryWrap">
        <div class="c-filterRow">
          <span class="c-filterLead">絞り込み</span>
          <button class="c-showFilterButton" @click="openModal('prefecture')">都道府県</button>
          <button class="c-showFilterButton" @click="openModal('price')">価格</button>
          <button class="c-showFilterButton" @click="openModal('bestBefore')">賞味期限</button>
        </div>
      </div>
    </section>
    <!------------------------ 商品欄 ---------------------------->
    <section class="p-productContainer">
      <p class="p-productContainer__lead">東京都、1200円~1500円、賞味期限切れの検索結果：50件
      </p>
      <div class="p-productContainer__wrap">
        <div class="p-itemWrap" :class="{ 'is-disabled': product.buy_flg.buy }" v-for="(product, index) of productList" :key="index">
          <img :src="product.images.image_1" class="p-itemWrap__img">
          <div class="p-itemWrap__detailWrap">
            <p class="p-itemTitle">
              {{ product.name }}
            </p>
            <p class="p-itemPrefectureText"><i class="fa-solid fa-location-dot"></i> {{ product.shop.prefecture.name }}</p>
            <p class="p-itemBestBeforeText">
              賞味期限<span class="p-itemBestBeforeText__date">{{ product.best_day}} {{ product.best_time }}まで</span>
            </p>
            <p class="p-itemPrice">{{ product.price.toLocaleString() }}<span class="p-itemPrice__label">円(税込)</span>
            </p>
          </div>
          <!-- <a href="#" class="p-itemWrap__link"><span class="p-itemLinkText">詳細を見る＞</span></a> -->
          <RouterLink
            class="p-itemWrap__link"
            :to="`/product_list/${product.id}`"><span class="p-itemLinkText">詳細を見る＞</span>
          </RouterLink>
        </div>
      </div>
      <!------------------------ ページネーション欄 ---------------------------->
      <div class="p-productContainer__paginationWrap">
        
      </div>
      <Pagination
        :current-page="current_page"
        :last-page="last_page"
        @changePage="changePage">
      </Pagination>
    </section>
  </div>
</template>

<script>
// 定義したステータスコードをインポート
import { OK } from '../../util'
// storeフォルダ内のファイルで定義した「state」を参照
import { mapState, mapGetters } from "vuex";
// モーダルコンポーネント読み込み
import Modal from '../Modal/Modal.vue'
// ページネーションコンポーネント読み込み
import Pagination from "../Pagination";

export default {
  components: {
    Modal,
    Pagination
  },
  data() {
    return {
      current_page: 1, // 現在のページ
      last_page: "", // 最後のページ番号
    };
  },
  computed: {
    // stepストアのstepsを参照
    ...mapState({
      productList: (state) => state.product.productList,
    }),
    // stepストアのfilteredProductList(絞り込み後のproduct)を参照
    // ...mapGetters({
    //   filteredProductList: "product/filteredProducts",
    // }),
  },
  methods: {
    // 商品リスト取得
    async getProductList () {
      // 商品リスト取得API実行
      const response = await axios.get(`/api/product_list?page=${this.current_page}`)
      // api通信失敗の場合
      if (response.status !== OK) {
        // エラーストアにステータスコードを渡す
        this.$store.commit('error/setCode', response.status)
        return false
      }
      console.log('response:', response);
      // api通信成功の場合、商品ストアへ取得した商品リストデータを渡す
      this.$store.dispatch("product/setProductList", response.data.data);
      // 現在のページ番号をデータへ渡す
      this.current_page = response.data.current_page
      // 最後のページ番号をデータへ渡す
      this.last_page = response.data.last_page;
    },
    // ページ切り替え
    changePage(page) {
      // 遷移ページが最後のページ以下かつ、1以上の場合
      if (page > 0 && page <= this.last_page) {
        this.current_page = page;
        // 商品リスト取得メソッド実行
        this.getProductList();
      }
    },
    // 購入確認モーダルを開く
    openModal(type) {
      this.$refs.modal.openModal({ type: 'filter', childType: type, data: '' })
    },
  },
  watch: {
    //created内では、商品一覧ページの2ページ目表示時は呼ばれないため、ルーティング監視
    $route: {
      async handler () {
        // 商品リスト取得メソッド実行
        await this.getProductList()
      },
      
      immediate: true // 起動時にも実行
    }
  }
};
</script>
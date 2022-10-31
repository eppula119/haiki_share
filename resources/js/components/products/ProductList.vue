<template>
  <div>
    <!------------------------ 絞り込み欄 ---------------------------->
    <section class="c-filterWrap">
      <!------------------------ モーダル欄 ---------------------------->
      <Modal
        type="filter"
        ref="modal"
        :prefecture-list="prefectureList"
        @get-filter-products="getFilterProducts"></Modal>
      <!------------------------ 検索欄 ---------------------------->
      <!-- <button class="c-filterWrap__showFilterButton c-button c-button--bgWhite">商品検索</button>
      <form class="c-filterWrap__keywordForm" method="POST">
        <input type="text" class="c-searchInput" name="keyword">
        <input type="submit" class="c-doSearchButton c-button c-button--bgWhite" value="検索">
      </form> -->
      <div class="c-filterWrap__categoryWrap">
        <div class="c-filterRow">
          <span class="c-filterLead">絞り込み</span>
          <button class="c-showFilterButton" @click="openModal('prefecture')" v-if="isPrefectureList">都道府県</button>
          <button class="c-showFilterButton" @click="openModal('price')">価格</button>
          <button class="c-showFilterButton" @click="openModal('bestBefore')">賞味期限</button>
        </div>
      </div>
    </section>
    <!------------------------ 商品欄 ---------------------------->
    <section class="p-productContainer">
      <p class="p-productContainer__lead">
        {{ pageName }}
        <template v-if="params.prefecture">
          {{ params.prefecture.length === 0 ? '全ての都道府県、' : params.prefecture.toString() + ','}}
        </template>
        {{ params.min ? params.min + '円~' : '' }}
        {{ params.max ? params.max + '円~、' : '' }}
        <template v-if="params.bestBefore != null">
        {{ params.bestBefore ? '賞味期限内' : '賞味期限切れ' }}
        </template>
         検索結果：
        <template v-if="total === 0">
          {{ total }}件
        </template>
        <template v-else>
          {{ from + '~' + to + '件/' + total + '件'}}
        </template>
      </p>
      <div class="p-productContainer__wrap">
        <div class="p-itemWrap"
          :class="{ 'is-disabled': $route.name === 'productList' && product.buy_flg.buy }"
          v-for="(product, index) of productList"
          :key="index">
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
            <template v-if="$route.name === 'sellProductList' && !pcLayoutFlg">
              <RouterLink
                class="p-itemButton c-button c-button--bgBlue"
                :to="`/product_list/${product.id}`">詳細を見る
              </RouterLink>
              <RouterLink
                class="p-itemButton c-button c-button--bgWhite"
                :to="`/sell_product/${product.id}`">商品情報を編集
              </RouterLink>
            </template>
          </div>
          <template v-if="$route.name === 'sellProductList' && pcLayoutFlg">
            <div class="p-itemLinkWrap"
              :class="{'is-active': hoverProduct === product.id} "
              @mouseover="hoverProduct=product.id" @mouseout="hoverProduct=''">
              <RouterLink
                class="p-itemLink"
                :to="`/product_list/${product.id}`"><span>詳細を見る＞</span>
              </RouterLink>
              <RouterLink
                class="p-itemLink"
                :to="`/sell_product/${product.id}`"><span>商品情報を編集＞</span>
              </RouterLink>
            </div>
            <div class="p-itemWrap__overlay" @mouseover="hoverProduct=product.id" @mouseout="hoverProduct=''"></div>
          </template>
          <template v-else-if="$route.name !== 'sellProductList'">
            <div class="p-itemDetailLinkWrap" :class="{'is-active': hoverProduct === product.id} ">
              <RouterLink
                class="p-itemLink"
                :to="`/product_list/${product.id}`"><span>詳細を見る＞</span>
              </RouterLink>
            </div>
          </template>
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
  created () {
    // 選択可能な都道府県リスト取得(絞り込み検索時に必要)
    this.getPrefectureList()
    // 現在の画面幅を取得
    this.handleResize()
  },
  mounted() {
    // 画面幅変更時、現在の画面幅を取得
    window.addEventListener('resize', this.handleResize)
  },
  computed: {
    // 商品ストアのproductListを参照
    ...mapGetters({
      productList: "product/productList",
    }),
    // 商品ストアのparamsを参照
    ...mapGetters({
      params: "product/params",
    }),
    ...mapGetters({
      // 認証ユーザーストアのユーザー情報を参照
      user: "auth/user",
    })
  },
  data() {
    return {
      current_page: 1, // 現在のページ
      last_page: "", // 最後のページ番号
      total: "", // 取得した全ての商品数
      from: "", // 表示させる最初の商品が、取得した全ての商品数の内、何件目か
      to: "", // 表示させる最後の商品が、取得した全ての商品数の内、何件目か
      prefectureList: {}, // 絞り込み可能な都道府県リスト
      isPrefectureList: false, // 都道府県リストを取得完了フラグ
      pageName: '', // 現在ページ
      pcLayoutFlg: false, // pc画面フラグ
      hoverProduct: '' // マウスホバーしている商品
    };
  },
  methods: {
    // 商品リスト取得
    async getProductList () {
      const pathName = this.$route.name
      let response = {}
      let params = this.params
      switch (pathName) {
        case 'boughtProductList':
          this.pageName = '購入された商品、'
          console.log('購入された商品チイラン取得');
          // 購入された商品リスト取得API実行
          params.userId = this.user.id
          response = await axios.get(`/api/bought_product_list?page=${this.current_page}`, { params })
          break;
        case 'sellProductList':
          this.pageName = '出品した商品、'
          console.log('出品した商品チイラン取得');
          // 購入された商品リスト取得API実行
          params.userId = this.user.id
          response = await axios.get(`/api/sell_product_list?page=${this.current_page}`, { params })
          break;
        default:
          this.pageName = ''
          console.log('全商品チイラン取得');
          // 商品リスト取得API実行
          response = await axios.get(`/api/product_list?page=${this.current_page}`, { params })
          break;
      }
      
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
      // 取得した全ての商品件数をデータへ渡す
      this.total = response.data.total;
      // 表示させる最初の商品が、取得した全ての商品数の内、何件目かデータへ渡す
      this.from = response.data.from;
      // 表示させる最後の商品が、取得した全ての商品数の内、何件目かデータへ渡す
      this.to = response.data.to;
      // モーダルを閉じた状態にする
      this.closeModal()
    },
    // 絞り込み商品リスト取得
    getFilterProducts () {
      this.current_page = 1;
      this.getProductList()
    },
    // 選択可能な都道府県リスト取得
    async getPrefectureList() {
      // 都道府県リストを取得完了フラグをfalse
      this.isPrefectureList = false
      // 都道府県リスト取得API実行
      const response = await axios.get('/api/prefecture_list')
      // api通信失敗の場合
      if (response.status !== OK) {
        // エラーストアにステータスコードを渡す
        this.$store.commit('error/setCode', response.status)
        return false
      }
      // 取得した都道府県リストをデータへ渡す
      this.prefectureList = response.data
      // 都道府県リストを取得完了フラグをtrue
      this.isPrefectureList = true
      
      console.log('response:', response);
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
    // 絞り込み関連モーダルを開く
    openModal(type) {
      this.$refs.modal.openModal({ type: 'filter', childType: type, data: '' })
    },
    // モーダルを閉じる
    closeModal() {
      this.$refs.modal.closeModal()
    },
    // pcの画面幅か判定
    handleResize() {
      this.pcLayoutFlg = window.matchMedia(`(min-width: 1024px)`).matches
    }
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
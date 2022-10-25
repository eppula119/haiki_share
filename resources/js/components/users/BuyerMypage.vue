<template>
  <div class="p-userMainContainer">
    <!------------------------ モーダル欄 ---------------------------->
    <Modal ref="modal" @update-page="getShowMypage"></Modal>
    <h1 class="p-userMainContainer__title">
      マイページ
    </h1>
    <!------------------------ プロフィール編集画面遷移欄 ---------------------------->
    <section class="p-userMainContainer__profileEditWrap">
      <RouterLink
        class="p-profileLink c-routerLink"
        to="/buyer_edit_profile">プロフィール編集
      </RouterLink>
    </section>
    <!------------------------ 購入した商品一覧画面遷移欄 ---------------------------->
    <section class="p-userMainContainer__productWrap">
      <p class="p-subTitle">購入した商品一覧</p>
        <div class="p-productWrap" v-for="(product, index) of products" :key="index">
          <img :src="product.product.images.image_1" class="p-productWrap__img">
          <div class="p-productWrap__detail">
            <p class="p-productTitle">{{ product.product.name }}</p>
            <p class="productPrice"><span class="c-textRed">¥</span>{{ product.product.price }}</p>
          </div>
          <div class="p-buttonWrap">
            <RouterLink
              class="p-buttonWrap__button c-button c-button--bgBlue"
              :to="`/product_list/${product.product.id}`"><span class="p-itemLinkText">詳細を見る＞</span>
            </RouterLink>
            <a class="p-buttonWrap__button c-button c-button--bgWhite" @click.prevent="openModal('cancel', product.product)">購入をキャンセル</a>
          </div>
        </div>
    </section>
    <!------------------------ ページネーション欄 ---------------------------->
    <div class="p-userMainContainer__paginationWrap">
      <Pagination
        :current-page="current_page"
        :last-page="last_page"
        @changePage="changePage">
      </Pagination>
    </div>
  </div>
</template>

<script>
// 定義したステータスコードをインポート
import { OK } from '../../util'
// storeフォルダ内のファイルで定義した「getters」を参照
import { mapGetters } from "vuex";
// ページネーションコンポーネント読み込み
import Pagination from "../Pagination";
// モーダルコンポーネント読み込み
import Modal from '../Modal/Modal.vue'

export default {
  components: {
    Pagination,
    Modal
  },
  data() {
    return {
      products: [], // 購入した商品リスト
      current_page: 1, // 現在のページ
      last_page: "", // 最後のページ番号
    };
  },
  computed: {
    ...mapGetters({
      // 認証ユーザーストアのユーザー情報を参照
      user: "auth/user",
    })
  },
  methods: {
    // マイページ表示(購入商品取得)
    async getShowMypage(message) {
        // マイページ表示(購入商品取得)API実行
        console.log("get通信開始");
        const userData = {userId: this.user.id, type: this.user.type}

        const response = await axios.get(`/api/mypage?page=${this.current_page}`, { params: userData });
        // api通信失敗の場合
        if (response.status !== OK) {
          // エラーストアにステータスコードを渡す
          this.$store.commit("error/setCode", response.status);
          return false;
        }
        // フラッシュメッセージの表示が必要な場合は表示させる
        message ? this.showMessage(message) : false
        // モーダルを閉じた状態にする
        this.closeModal()

        console.log('response:', response);
        // api通信成功の場合、購入商品リストデータを渡す
        this.products = response.data.data;
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
        // マイページ表示メソッド実行
        this.getShowMypage(null);
      }
    },
    // 購入関連モーダルを開く
    openModal(type, product) {
      this.$refs.modal.openModal({ type: type, childType: '', data: product })
    },
    // 購入関連モーダルを閉じる
    closeModal() {
      this.$refs.modal.closeModal()
    },
    // フラッシュメッセージを表示
    showMessage(message) {
      // MESSAGEストアに購入キャンセル成功メッセージを渡す
      this.$store.commit("message/setContent", {
        content: message,
        timeout: 5000
      })
    }
  },
  watch: {
    // ルーティング監視
    $route: {
      async handler () {
        // マイページ表示メソッド実行
        await this.getShowMypage(null)
      },
      immediate: true // 起動時にも実行
    }
  }
};
</script>
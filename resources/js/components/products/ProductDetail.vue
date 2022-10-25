<template>
  <div v-if="product !== {}">
    <!------------------------ モーダル欄 ---------------------------->
    <Modal ref="modal" @update-page="getProduct"></Modal>
    <!------------------------ 商品説明・商品画像欄 ---------------------------->
    <div class="p-productMainContainer">
      <!------------------------ 商品詳細説明欄 ---------------------------->
      <section class="p-productDetailContainer">
        <p class="p-productDetailContainer__title">{{ product.name }}</p>
        <p class="p-productDetailContainer__bestBeforeText">
          賞味期限<span class="p-bestBeforeDate">{{ product.best_day }} {{ product.best_time }}まで</span>
        </p>
        <p class="p-productDetailContainer__price">
          価格
          <span class="p-priceBigText">¥{{ product.price.toLocaleString() }}</span>
          税込
        </p>
        <button class="p-productDetailContainer__button c-button c-button--bgBlue"
          :class="{ 'is-disabled': product.buy_flg.buy }" @click="openModal('buy')">購入する</button>
        <button class="p-productDetailContainer__button c-button c-button--bgGray"
          :class="{ 'is-disabled': !product.buy_flg.buy }">購入済み</button>
        <button class="p-productDetailContainer__button c-button c-button--bgBlue"
          :class="{ 'is-disabled': !product.buy_flg.myBuy }" @click="openModal('cancel')">購入をキャンセル</button>
        <div class="p-productDetailContainer__info">
          <dl class="p-infoRow">
            <dt class="p-infoRow__title">コンビニ名</dt>
            <dd class="p-infoRow__detail">
              {{ product.shop.name }} | {{ product.shop.branch_name }}
            </dd>
          </dl>
          <dl class="p-infoRow">
            <dt class="p-infoRow__title">住所</dt>
            <dd class="p-infoRow__detail">
              {{ product.shop.prefecture.name }}
              {{ product.shop.city }}
              {{ product.shop.other_address}}
            </dd>
          </dl>
          <dl class="p-infoRow">
            <dt class="p-infoRow__title">詳細</dt>
            <dd class="p-infoRow__detail">
              {{ product.shop.profile }}
            </dd>
          </dl>
        </div>
      </section>
      <!------------------------ 商品画像欄 ---------------------------->
      <section class="p-productImgContainer">
        <img :src="mainImg ? mainImg : product.images.image_1" class="p-productImgContainer__mainImg">
        <div class="p-productImgContainer__subBlock">
            <img :src="url" class="p-productSubImg" @click="selectImg(url)" v-for="(url, imgKey, index) in product.images" :key="index">
        </div>
        <button
          class="p-productImgContainer__shareButton c-button c-button--bgWhite"
          @click="twitterShare(product.name, product.price, product.shop.name, product.shop.branch_name)">
          <i class="fa-brands fa-twitter c-twitterButton"></i>Twitterでシェアする
        </button>
      </section>
    </div>
  </div>
</template>

<script>
// 定義したステータスコードをインポート
import { OK } from '../../util'
// storeフォルダ内のファイルで定義した「getters」を参照
import { mapGetters } from "vuex";
// モーダルコンポーネント読み込み
import Modal from '../Modal/Modal.vue'

export default {
  components: {
    Modal
  },
  data() {
    return {
      product: { // 表示中の商品情報
        id: '',
        name: '',
        price: '',
        images: {},
        best_day: '',
        best_time: '',
        buy_flg : {},
        shop: {
          branch_name: '',
          city: '',
          name: '',
          other_address: '',
          profile: '',
          prefecture: {
            name: ''
          }
        }
      },
      mainImg: '',// メイン画像パス
    };
  },
  computed: {
    ...mapGetters({
      // 商品ストアの商品リスト情報を参照
      productList: "product/productList",
    })
  },
  methods: {
    // 詳細表示させる商品情報取得
    async getProduct(message) {
      // ストアに保存した商品リストから、商品IDがidパラメーターと一致する商品を取得
      const product = this.productList.find((product) => product.id === this.$route.params.id);
      // 一致する商品がストアに保存されている場合
      if(product) {
        // 一致した商品をデータへ渡す
        this.product = product
      } else {
        // 一致した商品がない場合、商品情報取得API実行
        console.log("get通信開始");
        const response = await axios.get(`/api/product_list/${this.$route.params.id}`);
        // api通信失敗の場合
        if (response.status !== OK) {
          // エラーストアにステータスコードを渡す
          this.$store.commit("error/setCode", response.status);
          return false;
        }
        // api通信成功の場合、商品データを渡す
        this.product = response.data
      }
      // フラッシュメッセージの表示が必要な場合は表示させる
      message ? this.showMessage(message) : false
      // モーダルを閉じた状態にする
      this.closeModal()
    },
    // サブ画像を大きいサイズで見る
    selectImg(url) {
      this.mainImg = url
    },
    // Twitterシェア
    twitterShare(productName, price, shopName, branchName) {
      //シェアする画面を設定
      let shareURL =
        "https://twitter.com/intent/tweet?text=" +
        encodeURIComponent(`${shopName + branchName}から\n`) +
        encodeURIComponent(`『${productName}』が${price}で出品されています。\n\n`) +
        `%20%23${encodeURIComponent("haiki_share\n")}` +
        "&url=" +
        `http://localhost:3000/product_list/${this.$route.params.id}`;
      // 新規ウインドウでツイート画面を開く
      window.open(shareURL, '_blank')
    },
    // 購入関連モーダルを開く
    openModal(type) {
      this.$refs.modal.openModal({ type: type, childType: '', data: this.product })
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
    //created内では、商品一覧ページの2ページ目表示時は呼ばれないため、ルーティング監視
    $route: {
      async handler () {
        // 商品リスト取得メソッド実行
        await this.getProduct(null)
      },
      
      immediate: true // 起動時にも実行
    }
  }
};
</script>
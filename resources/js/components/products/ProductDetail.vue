<template>
  <div v-if="product !== {}">
    <!------------------------ モーダル欄 ---------------------------->
    <div class="c-modalOverlay"></div>
    <div class="c-modal c-modalBuy">
      <div class="c-modal__content">
        <!------------------------ 商品購入モーダル欄 ---------------------------->
          <div class="c-contentImgBlock">
            <img src="/images/item.png" class="c-contentImgBlock__img">
            <span class="c-contentImgBlock__title">ガリガリ君リッチ！</span>
          </div>
          <dl class="c-contentInfoRow">
            <dt class="c-contentInfoRow__title">価格</dt>
            <dd class="c-contentInfoRow__detail">¥1200円(税込)</dd>
          </dl>
          <dl class="c-contentInfoRow">
            <dt class="c-contentInfoRow__title">賞味期限</dt>
            <dd class="c-contentInfoRow__detail">2023年10月31日19時30分まで</dd>
          </dl>
          <dl class="c-contentInfoRow">
            <dt class="c-contentInfoRow__title">コンビニ名</dt>
            <dd class="c-contentInfoRow__detail">ローソン | 行田店</dd>
          </dl>
          <dl class="c-contentInfoRow">
            <dt class="c-contentInfoRow__title">住所</dt>
            <dd class="c-contentInfoRow__detail">東京都新宿区新宿x丁目x番地 hogehogeビル3F</dd>
          </dl>
          <p class="c-contentNote">
            ※上記の商品で間違いないか確認した上で「購入する」ボタンを押してください。
            ボタン押下後、決済が完了し登録メールアドレスへ購入確認メールが送信されます。
          </p>
      </div>
      <div class="c-modal__buttonWrap">
        <button class="c-modalDoButton c-button c-button--bgGray">キャンセル</button>
        <button class="c-modalDoButton c-button c-button--bgBlue">保存</button>
      </div>
    </div>
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
        <button class="p-productDetailContainer__button c-button c-button--bgBlue">購入する</button>
        <button class="p-productDetailContainer__button c-button c-button--bgGray">購入済み</button>
        <button class="p-productDetailContainer__button c-button c-button--bgBlue">購入をキャンセル</button>
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

export default {
  data() {
    return {
      product: { // 表示中の商品情報
        id: '',
        name: '',
        price: '',
        images: {},
        best_day: '',
        best_time: '',
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
      mainImg: '' // メイン画像パス
    };
  },
  computed: {
    ...mapGetters({
      // authストアのAPIステータスを参照
      productList: "product/productList",
    })
  },
  methods: {
    // 詳細表示させる商品情報取得
    async getProduct() {
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
  },
  watch: {
    //created内では、商品一覧ページの2ページ目表示時は呼ばれないため、ルーティング監視
    $route: {
      async handler () {
        // 商品リスト取得メソッド実行
        await this.getProduct()
      },
      
      immediate: true // 起動時にも実行
    }
  }
};
</script>
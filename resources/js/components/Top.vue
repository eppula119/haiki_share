<template>
  <div>
    <!------------------------ ヒーローイメージ欄 ---------------------------->
  <section class="p-heroImg js-sectionTarget">
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
  <section class="p-introduction js-sectionTarget">
    <h2 class="p-introduction__name">Haiki Share</h2>
    <p class="p-introduction__summary">コンビニ廃棄シェアサービス</p>
    <p class="p-introduction__desc">コンビニ廃棄商品にて「もったいない」、「買いたい」を叶えたサービスです。</p>
  </section>
  <!------------------------ 商品リスト欄 ---------------------------->
  <section class="p-productContainerTop p-productContainer js-sectionTarget">
    <h2 class="p-productContainerTop__title">Pick Up！</h2>
    <div class="p-productContainer__wrap p-productContainerTop__wrap">
      
      <div class="p-itemWrap" v-for="(product, index) of productList" :key="index">
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
          <div class="p-itemDetailLinkWrap">
            <RouterLink
              class="p-itemLink"
              :to="`/product_list/${product.id}`"><span>詳細を見る＞</span>
            </RouterLink>
          </div>
      </div>

    </div>
    <div class="p-productContainerTop__buttonBlock">
      <button class="p-productTopButton c-button c-button--bgBlue"
        @click="$router.push('product_list')">商品一覧を見る</button>
    </div>
  </section>
  <!------------------------ サービス概要欄 ---------------------------->
  <section class="p-about js-sectionTarget">
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
  <section class="p-subImg js-sectionTarget">
    <p class="p-subImg__text js-sectionTarget__subImg">
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
  <section class="p-startBlock js-sectionTarget">
    <h1 class="p-startBlock__title">Haiki Share</h1>
    <span class="p-startBlock__summary">〜廃棄食品で世界を救う〜</span>
    <button class="p-startBlock__button c-button c-button--bgBlue"
      @click="$router.push('buyer_register')">買い手として利用</button>
    <button class="p-startBlock__button c-button c-button--bgBlue"
      @click="$router.push('seller_login')">売り手として利用</button>
  </section>
  </div>
</template>

<script>
// 定義したステータスコードをインポート
import { OK } from '../util'
// storeフォルダ内のファイルで定義した「state」を参照
import { mapState, mapGetters } from "vuex";

export default {
  created () {
    // 商品リスト取得メソッド実行
    this.getProductList()
  },
  mounted() {
    // 画面スクロールアクションを検知
    window.addEventListener('scroll', this.animationByScroll, false);
  },
  destroyed() {
    // 別の画面へ遷移時に画面スクロールアクションを検知させない
    window.removeEventListener('scroll', this.animationByScroll, false);
  },
  computed: {
    // 商品ストアのproductListを参照
    ...mapGetters({
      productList: "product/productList",
    })
  },
  data() {
    return {
      hoverProduct: '' // マウスホバーしている商品
    };
  },
  methods: {
    // 商品リスト取得
    async getProductList () {
      // 商品リスト取得API実行
      const response = await axios.get(`/api/product_list?pageName=top`)
      // api通信失敗の場合
      if (response.status !== OK) {
        // エラーストアにステータスコードを渡す
        this.$store.commit('error/setCode', response.status)
        return false
      }
      // api通信成功の場合、商品ストアへ取得した商品リストデータを渡す
      this.$store.dispatch("product/setProductList", response.data);
    },
    // スクロール量によるクラス付与
    animationByScroll(e) {
      const sections = $(".js-sectionTarget")
      const imgText = $(".js-sectionTarget__subImg")
      const scrollY = window.scrollY //スクロール量
      const windowHeight = window.innerHeight //画面の高さ
      const INVIEW_HEIGHT = 200 //ずらす用の高さ

      sections.each(function (_, section) { // 各section要素に適用
      const sectionOffsetTop = section.offsetTop //要素の縦方向の位置
        if (scrollY + windowHeight > sectionOffsetTop + INVIEW_HEIGHT) {
          $(section).addClass("is-active")
        }
      })
      // サブイメージ欄のテキスト要素の縦方向の位置
      const imgTextOffsetTop = imgText[0].getBoundingClientRect().top + window.pageYOffset
      if (scrollY + windowHeight > imgTextOffsetTop + INVIEW_HEIGHT) {
        $(imgText).addClass("is-active")
      }

    }
  }
};
</script>
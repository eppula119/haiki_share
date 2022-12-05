<template>
  <div class="p-userMainContainer" :class="{ 'u-height--max': heightMax}">
    <h1 class="p-userMainContainer__title">
      マイページ
    </h1>
    <!------------------------ 出品画面遷移欄 ---------------------------->
    <section class="p-userMainContainer__sellButtonWrap">
      <RouterLink
        class="p-sellLink c-button c-button--bgBlue"
        to="/sell_product">出品する
      </RouterLink>
    </section>
    <!------------------------ プロフィール編集画面遷移欄 ---------------------------->
    <section class="p-userMainContainer__profileEditWrap">
      <RouterLink
        class="p-profileLink c-routerLink"
        to="/seller_edit_profile">プロフィール編集
      </RouterLink>
    </section>
    <!------------------------ 出品した商品一覧画面遷移欄 ---------------------------->
    <section class="p-userMainContainer__productWrap">
      <p class="p-subTitle">出品した商品一覧</p>
        <div class="p-productWrap" v-for="(product, index) of sellProdcuts" :key="index">
          <img :src="product.images.image_1 ? product.images.image_1 : '../images/no_img.jpeg'" class="p-productWrap__img">
          <div class="p-productWrap__detail">
            <p class="p-productTitle">{{ product.name }}</p>
            <p class="productPrice"><span class="c-textRed">¥</span>{{ product.price }}</p>
          </div>
          <div class="p-buttonWrap">
            <RouterLink
              class="p-buttonWrap__button c-button c-button--bgBlue"
              :to="`/product_list/${product.id}`">詳細を見る
            </RouterLink>
            <RouterLink
              v-if="!product.buy_flg.buy"
              class="p-buttonWrap__button c-button c-button--bgWhite"
              :to="`/sell_product/${product.id}`">商品情報を編集
            </RouterLink>
          </div>
        </div>
      <div class="p-allShowLink">
        <RouterLink
          class="p-allShowLink__button c-button c-button--bgWhite"
          to="/sell_product_list">全件表示
        </RouterLink>
      </div>
    </section>
    <!------------------------ 購入された商品一覧画面遷移欄 ---------------------------->
    <section class="p-userMainContainer__productWrap">
      <p class="p-subTitle">購入された商品一覧</p>
        <RouterLink class="p-productLink" :to="`/product_list/${product.product.id}`" v-for="(product, index) of boughtProducts" :key="index">
          <div class="p-productWrap p-boughtProductWrap">
            <img :src="product.product.images.image_1 ? product.product.images.image_1 : '../images/no_img.jpeg'" class="p-productWrap__img">
            <div class="p-productWrap__detail">
              <p class="p-productTitle">{{ product.product.name }}</p>
              <p class="productPrice"><span class="c-textRed">¥</span>{{ product.product.price }}</p>
            </div>
            <span class="p-linkText">＞</span>
          </div>
        </RouterLink>
      <div class="p-allShowLink">
        <RouterLink
          class="p-allShowLink__button c-button c-button--bgWhite"
          to="/bought_product_list">全件表示
        </RouterLink>
      </div>
    </section>
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
      sellProdcuts: null,
      boughtProducts: null,
      heightMax: false,
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
    async getShowMypage() {
        // マイページ表示(出品商品、購入された商品取得)API実行
        const userData = {userId: this.user.id, type: this.user.type}

        const response = await axios.get('/api/mypage', { params: userData });
        // api通信失敗の場合
        if (response.status !== OK) {
          // エラーストアにステータスコードを渡す
          this.$store.commit("error/setCode", response.status);
          return false;
        }
        // api通信成功の場合、購入された商品、出品商品データを渡す
        this.sellProdcuts = response.data.sellProducts;
        this.boughtProducts = response.data.boughtProducts;
        this.heightMax = false
        // DOM更新後に処理
        this.$nextTick(() => {
          this.getInnerHeight()
        });
    },
    // 画面の高さを取得
    getInnerHeight(e) {
      // 画面の高さを取得
      const windowHeight = window.innerHeight
      // html要素の高さ取得
      const htmlHeight = document.documentElement.offsetHeight;
      htmlHeight < windowHeight ? this.heightMax = true : this.heightMax = false
    }
  },
  watch: {
    // ルーティング監視
    $route: {
      async handler () {
        // マイページ表示メソッド実行
        await this.getShowMypage()
      },
      immediate: true // 起動時にも実行
    }
  }
};
</script>
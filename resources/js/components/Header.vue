<template>
<div>
  <Loading v-if="showLoadingFlg" />
  <header class="l-header">
    <RouterLink to="/">
      <div class="p-headerTitle">
        <img src="/images/logo.png" class="p-headerTitle__img">
      </div>
    </RouterLink>
    <div class="p-headerMenuTrigger js-toggle__spMenu" @click="doToggle" :class="{'is-active': toggleFlg}">
      <span class="p-headerMenuTrigger__border"></span>
      <span class="p-headerMenuTrigger__border"></span>
      <span class="p-headerMenuTrigger__border"></span>
    </div>
    <nav class="p-headerNav js-toggle__spMenuTarget" :class="{'is-active': toggleFlg}">
        <div class="p-headerNav__title">
          <span class="p-navTitle">コンビニ廃棄食品シェアリングサービス</span>
          <img src="/images/logo.png" class="p-navImg">
        </div>
        <!-- <div class="p-headerNav__searchForm">
          <form class="p-navSearchForm" method="POST">
            <input type="text" class="p-navSearchForm__input" name="keyword">
            <label for="doSearch" class="p-navSearchForm__label">
              <input type="submit" id="doSearch" class="p-navSearchButton">
              <i class="fa-solid fa-magnifying-glass"></i>
            </label>
          </form>
        </div> -->
        <a class="p-headerNav__link" @click="goPage('/product_list')">商品一覧</a>
        <a class="p-headerNav__link" @click="goPage('/buyer_register')" v-if="!isLogin">新規登録</a>
        <a class="p-headerNav__link" @click="goPage('/buyer_mypage')" v-if="isLogin && user.type === 'user'">マイページ</a>
        <a class="p-headerNav__link" @click="goPage('/seller_mypage')" v-else-if="isLogin && user.type === 'shop'">マイページ</a>
        <a class="p-headerNav__link" @click="goPage('/buyer_login')" v-if="!isLogin">ログイン</a>
        <form class="p-headerNav__link p-logout" method="post" @submit.prevent="logout" v-else>
          <input type="submit" class="p-logout__button" value="ログアウト" >
        </form>
        
    </nav>
  </header>
</div>

</template>

<script>
// storeフォルダ内のファイルで定義した「getters」を参照
import { mapState, mapGetters } from "vuex";
// ローディングコンポーネント読み込み
import Loading from './Loading.vue'


export default {
  components: {
    Loading,
  },
  data() {
    return {
      toggleFlg: false, // SPメニュー（初期は閉じている状態）
      showLoadingFlg: false // ローディング表示フラグ
    };
  },
  computed: {
    ...mapState({
      // apiステータス状況
      apiStatus: state => state.auth.apiStatus
    }),
    ...mapGetters({
      // ログインチェック
      isLogin: "auth/check",
      // ログインユーザー
      user: "auth/user",
    })
  },
  methods: {
    // ログアウト
    async logout () {
      this.toggleFlg = false
      // ローディング表示
      this.showLoadingFlg = true
      // authストアのlogoutアクションを呼び出す(売り手か買い手かユーザー種別データも渡す)
      await this.$store.dispatch('auth/logout', this.user.type)
      // apiステータスがtrueの場合(api通信成功の場合)
      if (this.apiStatus) {
        // ログインページへ移動する
        this.$router.push('/buyer_login')
      }
      // ローディング表示
      this.showLoadingFlg = false
    },
    // クリックしたページへ遷移
    goPage(path) {
      this.toggleFlg = false
      this.$router.push(path)
    },
    // spメニュー
    doToggle() {
      this.toggleFlg = !this.toggleFlg
    }
  }
};
</script>
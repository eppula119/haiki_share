<template>
<header class="l-header">
  <div class="p-headerTitle">
    <img src="/images/logo.png" class="p-headerTitle__img">
  </div>
  <div class="p-headerMenuTrigger js-toggle__spMenu">
    <span class="p-headerMenuTrigger__border"></span>
    <span class="p-headerMenuTrigger__border"></span>
    <span class="p-headerMenuTrigger__border"></span>
  </div>
  <nav class="p-headerNav js-toggle__spMenuTarget">
      <div class="p-headerNav__title">
        <span class="p-navTitle">コンビニ廃棄食品シェアリングサービス</span>
        <img src="/images/logo.png" class="p-navImg">
      </div>
      <div class="p-headerNav__searchForm">
        <form class="p-navSearchForm" method="POST">
          <input type="text" class="p-navSearchForm__input" name="keyword">
          <label for="doSearch" class="p-navSearchForm__label">
            <input type="submit" id="doSearch" class="p-navSearchButton">
            <i class="fa-solid fa-magnifying-glass"></i>
          </label>
        </form>
      </div>
      <a href="" class="p-headerNav__link">商品一覧</a>
      <RouterLink class="p-headerNav__link" to="/buyer_login" v-if="!isLogin">ログイン</RouterLink>
      <form class="p-headerNav__link p-logout" method="post" @submit.prevent="logout" v-else>
        <input type="submit" class="p-logout__button" value="ログアウト" >
      </form>
      <RouterLink class="p-headerNav__link" to="/buyer_register" v-if="!isLogin">新規登録</RouterLink>
      <a href="" class="p-headerNav__link">マイページ</a>
      
  </nav>
</header>
</template>

<script>
// storeフォルダ内のファイルで定義した「getters」を参照
import { mapState, mapGetters } from "vuex";

export default {
  data() {
    return {
    };
  },
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus
    }),
    ...mapGetters({
      // ログインチェック
      isLogin: "auth/check",
      user: "auth/user",
    })
  },
  methods: {
    async logout () {
      // authストアのlogoutアクションを呼び出す(売り手か買い手かユーザー種別データも渡す)
      await this.$store.dispatch('auth/logout', this.user.type)
      // apiステータスがtrueの場合(api通信成功の場合)
      if (this.apiStatus) {
        // ログインページへ移動する
        this.$router.push('/buyer_login')
      }
    }
  }
};
</script>
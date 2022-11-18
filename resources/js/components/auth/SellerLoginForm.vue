<template>
  <div>
    <Loading v-if="showLoadingFlg" />
    <div class="p-authContainer">
      <h1 class="p-authContainer__title">
        ログイン(売り手)
      </h1>
      <form class="p-authContainer__form" method="post" @submit.prevent="login">
        <!------------------------ メールアドレス欄 ---------------------------->
        <label class="p-authLabel">メールアドレス</label>
        <span class="p-authAttention">※必須</span>
        <!-- 入力フォーム -->
        <input class="p-authInput" type="email" v-model="loginForm.email">
        <!-- バリデーションエラーメッセージ表示箇所 -->
        <div class="p-authValidate" v-if="loginErrors">
          <ul v-if="loginErrors.email">
            <li v-for="msg in loginErrors.email" :key="msg">※{{ msg }}</li>
          </ul>
        </div>
        <!------------------------ パスワード欄 ---------------------------->
        <label class="p-authLabel">パスワード(6文字以上)</label>
        <span class="p-authAttention">※必須</span>
        <!-- 入力フォーム -->
        <input class="p-authInput" type="password" v-model="loginForm.password">
        <!-- バリデーションエラーメッセージ表示箇所 -->
        <div class="p-authValidate" v-if="loginErrors">
          <ul v-if="loginErrors.password">
            <li v-for="msg in loginErrors.password" :key="msg">※{{ msg }}</li>
          </ul>
        </div>
        <!------------------------ ログインボタン ---------------------------->
        <input type="submit" class="p-authMainButton c-button c-button--bgBlue" value="ログイン">
        <RouterLink class="p-passwordResetLink" :to="{
          name: 'passwordReminder',
          params: { type: 'shop' }
        }">パスワードを忘れた方
        </RouterLink>
        <p class="p-noAccountText">アカウントをお持ちでない方</p>
        <button
          class="p-authSubButton c-button c-button--bgWhite"
          @click="$router.push('/buyer_register')"
        >新規登録(売り手)</button>
        <div class="p-otherPage">
          <RouterLink class="p-otherPage__link" to="/buyer_login">→買い手のログインはこちら</RouterLink>
        </div>
      </form>
    </div>
  </div>
  
</template>

<script>
// storeフォルダ内のファイルで定義した「state」を参照
import { mapState } from "vuex";
// ローディングコンポーネント読み込み
import Loading from '../Loading.vue'

export default {
  components: {
    Loading,
  },
  data() {
    return {
      // ログインフォーム入力値
      loginForm: {
        email: '',
        password: '',
        type: 'shop', // Ajax通信時、typeの値によって通信時のURL変更する為
      },
      showLoadingFlg: false // ローディング表示フラグ
    };
  },
  created () {
    // 画面表示時、バリデーションメッセージを消す
    this.clearError()
  },
  computed: mapState({
    apiStatus: (state) => state.auth.apiStatus, // authストアのAPIステータスを参照
    loginErrors: (state) => state.auth.loginErrorMessages, // authストアのログインエラーメッセージを参照
  }),
  methods: {
    // ログイン実行
    async login () {
      // ローディング表示
      this.showLoadingFlg = true
      // authストアのloginアクションを呼び出す
      await this.$store.dispatch('auth/login', this.loginForm)
      // apiステータスがtrueの場合(api通信成功の場合)
      if (this.apiStatus) {
        // 商品一覧ページに移動する
        this.$router.push('/product_list')
      }
      // ローディング非表示
      this.showLoadingFlg = false
    },
    // バリデーションエラーメッセーじを消す
    clearError () {
      this.$store.commit('auth/setLoginErrorMessages', null)
    }
  }
};
</script>
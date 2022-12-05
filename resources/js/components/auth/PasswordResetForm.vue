<template>
  <div>
    <Loading v-if="showLoadingFlg" />
    <div class="p-authContainer">
      <h1 class="p-authContainer__title">
        パスワード再発行
        <span v-if="resetForm.type === 'user'">(買い手)</span>
        <span v-else-if="resetForm.type === 'shop'">(売り手)</span>
      </h1>
      <form class="p-authContainer__form" @submit.prevent="reset">
        <!------------------------ パスワード欄 ---------------------------->
        <label class="p-authLabel">パスワード(6文字以上)</label>
        <span class="p-authAttention">※必須</span>
        <!-- 入力フォーム -->
        <input class="p-authInput" type="password" v-model="resetForm.password">
        <!-- バリデーションエラーメッセージ表示箇所 -->
        <div class="p-authValidate" v-if="resetErrors">
          <ul v-if="resetErrors.password">
            <li v-for="msg in resetErrors.password" :key="msg">※{{ msg }}</li>
          </ul>
        </div>
        <!------------------------ 確認用パスワード欄 ---------------------------->
        <label class="p-authLabel">確認用パスワード</label>
        <span class="p-authAttention">※必須</span>
        <!-- 入力フォーム -->
        <input class="p-authInput" type="password" v-model="resetForm.password_confirmation">
        <!-- バリデーションエラーメッセージ表示箇所 -->
        <div class="p-authValidate" v-if="resetErrors">
          <ul v-if="resetErrors.password_confirmation">
            <li v-for="msg in resetErrors.password_confirmation" :key="msg">※{{ msg }}</li>
          </ul>
        </div>
        <!------------------------ 再設定ボタン ---------------------------->
        <input type="submit" class="p-authMainButton c-button c-button--bgBlue" value="パスワード再発行">
      </form>
    </div>
  </div>
</template>

<script>
import Cookies from "js-cookie"; //クッキーを操作するパッケージ
// storeフォルダ内のファイルで定義した「state」を参照
import { mapState } from "vuex";
// ローディングコンポーネント読み込み
import Loading from '../Loading.vue'

export default {
  components: {
    Loading,
  },
  data: function() {
    return {
      resetForm: {
        password: '', // パスワード入力値
        password_confirmation: '', // 確認用パスワード入力値
        token: '', // トークン
        type: ''
      },
      showLoadingFlg: false // ローディング表示フラグ
    };
  },
  created () {
    // 画面表示時、バリデーションメッセージを消す
    this.clearError()
    // クッキーからトークンを取得
    this.setToken()
  },
  computed: mapState({
    apiStatus: (state) => state.auth.apiStatus, // authストアのAPIステータスを参照
    resetErrors: (state) => state.auth.resetErrorMessages, // authストアのパスワードリセットエラーメッセージを参照
  }),
  methods: {
    // パスワード再設定
    async reset() {
      // ローディング表示
      this.showLoadingFlg = true
      // authストアのresetアクションを呼び出す
      await this.$store.dispatch('auth/reset', this.resetForm)
      // apiステータスがtrueの場合(api通信成功の場合)
      if (this.apiStatus) {
        // 商品一覧ページに移動する
        this.$router.push('/product_list')
      }
      // ローディング非表示
      this.showLoadingFlg = false
    },
    // クッキーからトークンを取得しデータへ渡す
    setToken () {
      // クッキーからリセットトークンを取得
      const token = Cookies.get("RESETTOKEN")
      // クッキーから売り手か買い手か種別を取得
      const type = Cookies.get("TYPE")
      // リセットトークンがない、または種別がない場合はパスワードリマインダー画面へ移動させる
      if(token == null || type == null) {
        return this.$router.push({
            name: 'passwordReminder',
            params: {
                type: type
            }
        })
      }
      
      // フォームにリセットトークンをセット
      if(token) {
        this.resetForm.token = token
        Cookies.remove("RESETTOKEN")
      }
      // フォームに買い手か売り手か種別をセット
      if(type) {
        this.resetForm.type = type
        Cookies.remove("TYPE")
      }
    },
    // バリデーションエラーメッセージを消す
    clearError () {
      this.$store.commit('auth/setResetErrorMessages', null)
    }
  }
};
</script>
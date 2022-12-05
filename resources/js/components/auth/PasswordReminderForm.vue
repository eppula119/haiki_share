<template>
  <div>
    <Loading v-if="showLoadingFlg" />
    <div class="p-authContainer">
      <h1 class="p-authContainer__title">
        パスワード再発行
        <span v-if="isType === 'user'">(買い手)</span>
        <span v-else-if="isType === 'shop'">(売り手)</span>
      </h1>
      <form class="p-authContainer__form" @submit.prevent="forgot" v-if="!successMailFlg">
        <!------------------------ メールアドレス欄 ---------------------------->
        <label class="p-authLabel">メールアドレス</label>
        <span class="p-authAttention">※必須</span>
        <!-- 入力フォーム -->
        <input class="p-authInput" type="email" v-model="forgotForm.email">
        <!-- バリデーションエラーメッセージ表示箇所 -->
        <div class="p-authValidate" v-if="forgotErrors">
          <ul v-if="forgotErrors.email">
            <li v-for="msg in forgotErrors.email" :key="msg">※{{ msg }}</li>
          </ul>
        </div>
        <!------------------------ パスワード再発行メール送信ボタン ---------------------------->
        <input type="submit" class="p-authMainButton c-button c-button--bgBlue" value="送信">
        <div class="p-otherPage">
          <!-- <a href="#" class="p-otherPage__link">→戻る</a> -->
          <a class="p-otherPage__link" @click="$router.back()">戻る</a>
        </div>
      </form>
      <!------------------------ パスワード再発行メール送信後の画面 ---------------------------->
      <p class="p-authContainer__sendmailText" v-else>
        メールを送信しました。<br/>
        お送りしたメールのパスワード再発行リンクからパスワード再設定手続きを行ってください。
      </p>
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
  props: {
    type: {
      type: [ String ]
    }
  },
  data: function() {
    return {
      forgotForm: {
        email: '', // メールアドレス入力値
        type: ''
      },
      isType: '',
      successMailFlg: false, // メール送信成功フラグ
      showLoadingFlg: false // ローディング表示フラグ
    };
  },
  created () {
    // 画面表示時、バリデーションメッセージを消す
    this.clearError()
    // ルーティング時やクッキーで受け取った種別をデータに渡す
    this.setType()
    // クッキーからフラッシュメッセージを取得
    this.setToken()
  },
  computed: mapState({
    apiStatus: (state) => state.auth.apiStatus, // authストアのAPIステータスを参照
    forgotErrors: (state) => state.auth.forgotErrorMessages, // authストアのパスワードリマインダーエラーメッセージを参照
  }),
  methods: {
    // パスワード再発行
    async forgot() {
      // ローディング表示
      this.showLoadingFlg = true
      // 売り手か買い手かどちらの種別か付与
      this.forgotForm.type = this.isType
      // authストアのforgotアクションを呼び出す
      await this.$store.dispatch('auth/forgot', this.forgotForm)
      // apiステータスがtrueの場合(api通信成功の場合)
      if (this.apiStatus) {
        this.successMailFlg = true
      }
      // ローディング非表示
      this.showLoadingFlg = false
    },
    // ルーティング時に受け取った種別をデータに渡す
    setType() {
      // ルーティング時に種別を受け取った場合
      if(this.type) {
        // 受け取った種別をデータに渡す
        this.isType = this.type
        // クッキーにも渡す(再読み込み時、データ初期化されてしまう為)
        Cookies.set('TYPE', this.type)
      // ルーティング時に種別を受け取れなかった場合(再読み込み時など)
      } else {
        // クッキーからTYPEを取得
        const type = Cookies.get('TYPE')
        this.isType = type
      }
    },
    // クッキーからトークンを取得しデータへ渡す
    setToken() {
      // クッキーからMESSAGEを取得
      const message = Cookies.get("MESSAGE");
      if (message) {
        // cookieをクリア
        Cookies.remove("MESSAGE");
        // MESSAGEストアでメッセージを表示
        this.$store.commit("message/setContent", {
          content: message,
          timeout: 3000
        })
      }
    },
    // バリデーションエラーメッセージを消す
    clearError() {
      this.$store.commit('auth/setForgotErrorMessages', null)
    }
  }
};
</script>
<template>
  <div class="p-authContainer">
    <h1 class="p-authContainer__title">
      新規登録(売り手)
    </h1>
    <form class="p-authContainer__form" method="post" @submit.prevent="register">
      <!------------------------ コンビニ名欄 ---------------------------->
      <label class="p-authLabel">コンビニ名</label>
      <span class="p-authAttention">※必須</span>
      <!-- 入力フォーム -->
      <input class="p-authInput" type="text" v-model="registerForm.name">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate" v-if="registerErrors">
        <ul v-if="registerErrors.name">
          <li v-for="msg in registerErrors.name" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ 支店名欄 ---------------------------->
      <label class="p-authLabel">支店名</label>
      <span class="p-authAttention">※必須</span>
      <!-- 入力フォーム -->
      <input class="p-authInput" type="text" v-model="registerForm.branch_name">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate" v-if="registerErrors">
        <ul v-if="registerErrors.branch_name">
          <li v-for="msg in registerErrors.branch_name" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ 都道府県欄 ---------------------------->
      <label class="p-authLabel">都道府県</label>
      <span class="p-authAttention">※必須</span>
      <!-- 入力フォーム -->
      <select class="p-authSelect" v-model="registerForm.prefecture">
        <option value="北海道">北海道</option>
        <option value="青森県">青森県</option>
        <option value="岩手県">岩手県</option>
      </select>
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate" v-if="registerErrors">
        <ul v-if="registerErrors.prefecture">
          <li v-for="msg in registerErrors.prefecture" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ 市町村名・番地欄 ---------------------------->
      <label class="p-authLabel">市町村名・番地</label>
      <span class="p-authAttention">※必須</span>
      <!-- 入力フォーム -->
      <input class="p-authInput" type="text" v-model="registerForm.city">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate" v-if="registerErrors">
        <ul v-if="registerErrors.city">
          <li v-for="msg in registerErrors.city" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ 建物名・部屋番号欄 ---------------------------->
      <label class="p-authLabel">建物名・部屋番号</label>
      <!-- 入力フォーム -->
      <input class="p-authInput" type="text" v-model="registerForm.other_address">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate" v-if="registerErrors">
        <ul v-if="registerErrors.other_address">
          <li v-for="msg in registerErrors.other_address" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ メールアドレス欄 ---------------------------->
      <label class="p-authLabel">メールアドレス</label>
      <span class="p-authAttention">※必須</span>
      <!-- 入力フォーム -->
      <input class="p-authInput" type="email" v-model="registerForm.email">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate" v-if="registerErrors">
        <ul v-if="registerErrors.email">
          <li v-for="msg in registerErrors.email" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ パスワード欄 ---------------------------->
      <label class="p-authLabel">パスワード(6文字以上)</label>
      <span class="p-authAttention">※必須</span>
      <!-- 入力フォーム -->
      <input class="p-authInput" type="password" v-model="registerForm.password">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate" v-if="registerErrors">
        <ul v-if="registerErrors.password">
          <li v-for="msg in registerErrors.password" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ 確認用パスワード欄 ---------------------------->
      <label class="p-authLabel">確認用パスワード</label>
      <span class="p-authAttention">※必須</span>
      <!-- 入力フォーム -->
      <input class="p-authInput" type="password" v-model="registerForm.password_confirmation">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-authValidate" v-if="registerErrors">
        <ul v-if="registerErrors.password_confirmation">
          <li v-for="msg in registerErrors.password_confirmation" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ 新規登録ボタン ---------------------------->
      <input type="submit" class="p-authMainButton c-button c-button--bgBlue" value="新規登録">
      <div class="p-otherPage">
        <RouterLink class="p-otherPage__link" to="/buyer_register">→買い手の新規登録はこちら</RouterLink>
      </div>
    </form>
  </div>
</template>

<script>
// ストアのステートをインポート
import { mapState } from "vuex";

export default {
  data: function() {
    return {
      // ユーザー登録フォーム入力値
      registerForm: {
        name: '',
        branch_name: '',
        prefecture: '',
        city: '',
        other_address: '',
        email: '',
        password: '',
        password_confirmation: '',
        type: 'shop', // Ajax通信時、typeの値によって通信時のURL変更する為
      }
    };
  },
  created () {
    this.clearError() // バリデーションエラーメッセージ、初期化
  },
  computed: mapState({
    apiStatus: (state) => state.auth.apiStatus, // authストアのAPIステータスを参照
    registerErrors: (state) => state.auth.registerErrorMessages, // authストアのユーザー登録エラーメッセージを参照
  }),
  methods: {
    // ユーザー登録実行
    async register () {
      // authストアのresigterアクションを呼び出す
      await this.$store.dispatch('auth/register', this.registerForm)
      // apiステータスがtrueの場合(api通信成功の場合)
      if (this.apiStatus) {
      // トップページに移動する
      this.$router.push('/')
      }
    },
    // エラーメッセージの初期化
    clearError() {
      // authストアのユーザー登録エラーメッセージステータスを初期化
      this.$store.commit('auth/setLoginErrorMessages', null)
    }
  }
};
</script>
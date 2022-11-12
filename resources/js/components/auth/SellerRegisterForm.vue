<template>
  <div>
    <Loading v-if="showLoadingFlg" />
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
          <option :value="prefecture.id" v-for="(prefecture, i) in prefectureList" :key="i">{{ prefecture.name }}</option>
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
  </div>
  
</template>

<script>
// 定義したステータスコードをインポート
import { OK } from '../../util'
// ストアのステートをインポート
import { mapState } from "vuex";
// ローディングコンポーネント読み込み
import Loading from '../Loading.vue'

export default {
  components: {
    Loading,
  },
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
      },
      prefectureList: {}, // 都道府県リスト
      showLoadingFlg: false // ローディング表示フラグ
    };
  },
  created () {
    this.clearError() // バリデーションエラーメッセージ、初期化
    // 全都道府県リスト取得
    this.getPrefectureList()
  },
  computed: mapState({
    apiStatus: (state) => state.auth.apiStatus, // authストアのAPIステータスを参照
    registerErrors: (state) => state.auth.registerErrorMessages, // authストアのユーザー登録エラーメッセージを参照
  }),
  methods: {
    // ユーザー登録実行
    async register () {
      // ローディング表示
      this.showLoadingFlg = true
      // authストアのresigterアクションを呼び出す
      await this.$store.dispatch('auth/register', this.registerForm)
      // apiステータスがtrueの場合(api通信成功の場合)
      if (this.apiStatus) {
      // トップページに移動する
      this.$router.push('/')
      }
      // ローディング非表示
      this.showLoadingFlg = false
    },
    // エラーメッセージの初期化
    clearError() {
      // authストアのユーザー登録エラーメッセージステータスを初期化
      this.$store.commit('auth/setRegisterErrorMessages', null)
    },
    // 全都道府県リスト情報取得
    async getPrefectureList() {
      // 全都道府県リスト取得API実行
      const response = await axios.get('/api/all_prefecture_list')
      // api通信失敗の場合
      if (response.status !== OK) {
        // エラーストアにステータスコードを渡す
        this.$store.commit('error/setCode', response.status)
        return false
      }
      // 取得した都道府県リストをデータへ渡す
      this.prefectureList = response.data
      
      console.log('response:', response);
    },
  }
};
</script>
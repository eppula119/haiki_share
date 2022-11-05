<template>
  <div class="p-userMainContainer">
    <h1 class="p-userMainContainer__title">
      プロフィール編集
    </h1>
    <div class="p-userMainContainer__formWrap">
      <form action="#" class="p-form" @submit.prevent="edit">
      <!------------------------ コンビニ名欄 ---------------------------->
      <label class="p-formLabel">コンビニ名</label>
      <span class="p-formAttention">※必須</span>
      <!-- 入力フォーム -->
      <input class="p-formInput" type="text" name="store-name" v-model="profileForm.name">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-formValidate" v-if="errors">
        <ul v-if="errors.name">
          <li v-for="msg in errors.name" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ 支店名欄 ---------------------------->
      <label class="p-formLabel">支店名</label>
      <span class="p-formAttention">※必須</span>
      <!-- 入力フォーム -->
      <input class="p-formInput" type="text" name="branch-name" v-model="profileForm.branch_name">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-formValidate" v-if="errors">
        <ul v-if="errors.branch_name">
          <li v-for="msg in errors.branch_name" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ 都道府県欄 ---------------------------->
      <label class="p-formLabel">都道府県</label>
      <span class="p-formAttention">※必須</span>
      <!-- 入力フォーム -->
      <select class="p-formSelect" name="prefecture" v-model="profileForm.prefecture">
        <option :selected="prefecture.id === profileForm.prefecture" :value="prefecture.id" v-for="(prefecture, i) in prefectureList" :key="i">{{ prefecture.name }}</option>
      </select>
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-formValidate" v-if="errors">
        <ul v-if="errors.prefecture">
          <li v-for="msg in errors.prefecture" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ 市町村名・番地欄 ---------------------------->
      <label class="p-formLabel">市町村名・番地</label>
      <span class="p-formAttention">※必須</span>
      <!-- 入力フォーム -->
      <input class="p-formInput" type="text" name="city" v-model="profileForm.city">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-formValidate" v-if="errors">
        <ul v-if="errors.city">
          <li v-for="msg in errors.city" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ 建物名・部屋番号欄 ---------------------------->
      <label class="p-formLabel">建物名・部屋番号</label>
      <!-- 入力フォーム -->
      <input class="p-formInput" type="text" name="other-address" v-model="profileForm.other_address">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-formValidate" v-if="errors">
        <ul v-if="errors.other_address">
          <li v-for="msg in errors.other_address" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ メールアドレス欄 ---------------------------->
      <label class="p-formLabel">メールアドレス</label>
      <span class="p-formAttention">※必須</span>
      <!-- 入力フォーム -->
      <input class="p-formInput" type="email" name="email" v-model="profileForm.email">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-formValidate" v-if="errors">
        <ul v-if="errors.email">
          <li v-for="msg in errors.email" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ 自己紹介欄 ---------------------------->
      <label class="p-formLabel">自己紹介</label>
      <span class="p-formCount">1/200</span>
      <!-- 入力フォーム -->
      <textarea class="p-formTextArea" name="introduction" v-model="profileForm.profile"></textarea>
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-formValidate" v-if="errors">
        <ul v-if="errors.profile">
          <li v-for="msg in errors.profile" :key="msg">※{{ msg }}</li>
        </ul>
      </div>
      <!------------------------ パスワード欄 ---------------------------->
      <template v-if="showPasswordFlg">
        <!------------------------ 新規パスワード欄 ---------------------------->
        <label class="p-formLabel">新規パスワード(6文字以上)</label>
        <span class="p-formAttention">※必須</span>
        <!-- 入力フォーム -->
        <input class="p-formInput" type="password" name="password-new" v-model="profileForm.password">
        <!-- バリデーションエラーメッセージ表示箇所 -->
        <div class="p-formValidate" v-if="errors">
          <ul v-if="errors.password">
            <li v-for="msg in errors.password" :key="msg">※{{ msg }}</li>
          </ul>
        </div>
        <!------------------------ 確認用パスワード欄 ---------------------------->
        <label class="p-formLabel">確認用パスワード</label>
        <span class="p-formAttention">※必須</span>
        <!-- 入力フォーム -->
        <input class="p-formInput" type="password" name="password_confirmation" v-model="profileForm.password_confirmation">
        <!-- バリデーションエラーメッセージ表示箇所 -->
        <div class="p-formValidate" v-if="errors">
          <ul v-if="errors.password_confirmation">
            <li v-for="msg in errors.password_confirmation" :key="msg">※{{ msg }}</li>
          </ul>
        </div>
      </template>
      <button class="p-passwordChangeButton c-button c-button--bgWhite"
        :class="{ 'c-button--bgGray': showPasswordFlg}"
        @click.prevent="changeShowPassword">
        {{ showPasswordFlg ? 'パスワードを変更しない' : 'パスワード変更' }}
      </button>
      <!------------------------ プロフィール更新ボタン ---------------------------->
      <input type="submit" class="p-formMainButton c-button c-button--bgBlue" value="プロフィール更新">
    </form>
    </div>
  </div>
</template>

<script>
// storeフォルダ内のファイルで定義した「getters」を参照
import { mapGetters } from "vuex";
// 定義したステータスコードをインポート
import { CREATED, OK, UNPROCESSABLE_ENTITY } from '../../util'



export default {
  created () {
    // 全都道府県リスト取得
    this.getPrefectureList()
    // 現在登録されているプロフィール情報をフォームに反映
    this.reflectData()
  },
  computed: {
    ...mapGetters({
      // 商品ストアの商品リスト情報を参照
      user: "auth/user",
    })
  },
  data() {
    return {
      // プロフィールフォーム入力値
      profileForm: {
        id: '', // ユーザーID
        name: '', // コンビニ名
        branch_name: '', // 支店名
        prefecture: '', // 都道府県
        city: '', // 市区町村・番地
        other_address: '', // 住所
        email: '', // メールアドレス
        password: '', // パスワード
        password_confirmation: '', // 確認用パスワード
        profile: '' // 自己紹介
      },
      errors: {}, // バリデーションエラーメッセージ
      showPasswordFlg: false, // パスワード変更フォーム表示フラグ
      prefectureList: {} // 全都道府県リスト
    };
  },
  methods: {
    // 編集実行
    async edit() {
      // バリデーションメッセージ初期化
      this.errors = {}
      console.log('売り手ユーザープロフィール編集API実行！');
      let profileData = this.profileForm
      if(!this.showPasswordFlg) {
        delete profileData.password
        delete profileData.password_confirmation
      }
      // 売り手ユーザープロフィール編集API実行！
      const response = await axios.put('/api/shop/profile', profileData)
      console.log('response:', response);
      // ステータスコードが422(バリデーションエラー)の場合
      if (response.status === UNPROCESSABLE_ENTITY) {
        console.log('errors:', this.errors);
        // バリデーションメッセージオブジェクトをデータに渡す
        this.errors = response.data.errors
        return false
      }

      console.log('売り手ユーザープロフィール編集実行完了')
      console.log('response:', response);

      // api通信失敗の場合
      if (response.status !== OK) {
        console.log('API通信レスポンスOK!じゃない');
        // errorストアのsetCodeアクションを呼び出す
        this.$store.commit('error/setCode', response.status)
        return false
      }
      // 変更後ログインユーザー情報をstoreへ渡す
      await this.$store.dispatch('auth/setUser', response.data.user)
      // フラッシュメッセージを表示させる
      this.showMessage(response.data.message)
    },
    // パスワード変更フォーム表示・非表示
    changeShowPassword() {
      this.showPasswordFlg = !this.showPasswordFlg
      // 非表示の場合
      if(!this.showPasswordFlg) {
        // パスワード入力値を削除
        this.profileForm.password = ''
        this.profileForm.password_confirmation = ''
      }
    },
    // 現在登録されているプロフィール情報をフォームに反映
    reflectData() {
      this.profileForm = {
        id: this.user.id,
        name: this.user.name,
        branch_name: this.user.branch_name,
        prefecture: this.user.prefecture.id,
        city: this.user.city,
        other_address: this.user.other_address,
        email: this.user.email,
        profile: this.user.profile
      }
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
    // フラッシュメッセージを表示
    showMessage(message) {
      // MESSAGEストアに購入キャンセル成功メッセージを渡す
      this.$store.commit("message/setContent", {
        content: message,
        timeout: 5000
      })
    }
  }
};
</script>
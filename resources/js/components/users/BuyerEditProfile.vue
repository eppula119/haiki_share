<template>
  <div class="p-userMainContainer">
    <h1 class="p-userMainContainer__title">
      プロフィール編集
    </h1>
    <div class="p-userMainContainer__formWrap">
      <form class="p-form" @submit.prevent="edit">
      <!------------------------ ユーザー名欄 ---------------------------->
      <label class="p-formLabel">ユーザー名</label>
      <span class="p-formAttention">※必須</span>
      <!-- 入力フォーム -->
      <input class="p-formInput" type="text" name="name" v-model="profileForm.name">
      <!-- バリデーションエラーメッセージ表示箇所 -->
      <div class="p-formValidate" v-if="errors">
        <ul v-if="errors.name">
          <li v-for="msg in errors.name" :key="msg">※{{ msg }}</li>
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
        name: '', // ユーザー名
        email: '', // メールアドレス
        password: '', // パスワード
        password_confirmation: '', // 確認用パスワード
      },
      errors: {}, // バリデーションエラーメッセージ
      showPasswordFlg: false, // パスワード変更フォーム表示フラグ
    };
  },
  methods: {
    // 編集実行
    async edit() {
      // バリデーションメッセージ初期化
      this.errors = {}
      console.log('買い手ユーザープロフィール編集API実行！');
      let profileData = this.profileForm
      // パスワード変更しない場合
      if(!this.showPasswordFlg) {
        // パスワード、確認用パスワードプロパティ削除
        delete profileData.password
        delete profileData.password_confirmation
      }
      // 買い手ユーザープロフィール編集API実行！
      const response = await axios.put('/api/profile', profileData)
      console.log('response:', response);
      // ステータスコードが422(バリデーションエラー)の場合
      if (response.status === UNPROCESSABLE_ENTITY) {
        console.log('errors:', this.errors);
        // バリデーションメッセージオブジェクトをデータに渡す
        this.errors = response.data.errors
        return false
      }

      console.log('買い手ユーザープロフィール編集実行完了')
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
      
      // リロード
      console.log('リロード');
      // this.$router.go(0)
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
        email: this.user.email
      }
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
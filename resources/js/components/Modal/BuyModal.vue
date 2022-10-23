<template>
  <div>
    <!------------------------ 商品購入モーダル欄 ---------------------------->
    <div class="c-contentImgBlock">
      <img :src="productData.images.image_1" class="c-contentImgBlock__img">
      <span class="c-contentImgBlock__title">{{ productData.name }}</span>
    </div>
    <dl class="c-contentInfoRow">
      <dt class="c-contentInfoRow__title">価格</dt>
      <dd class="c-contentInfoRow__detail">{{ productData.price }}円(税込)</dd>
    </dl>
    <dl class="c-contentInfoRow">
      <dt class="c-contentInfoRow__title">賞味期限</dt>
      <dd class="c-contentInfoRow__detail">{{ productData.best_day + ' ' + productData.best_time }}まで</dd>
    </dl>
    <dl class="c-contentInfoRow">
      <dt class="c-contentInfoRow__title">コンビニ名</dt>
      <dd class="c-contentInfoRow__detail">{{ productData.shop.name }} | {{ productData.shop.branch_name }}</dd>
    </dl>
    <dl class="c-contentInfoRow">
      <dt class="c-contentInfoRow__title">住所</dt>
      <dd class="c-contentInfoRow__detail">
        {{ productData.shop.prefecture.name + productData.shop.city + productData.shop.other_address }}
      </dd>
    </dl>
    <p class="c-contentNote">
      ※上記の商品で間違いないか確認した上で「購入する」ボタンを押してください。
      ボタン押下後、決済が完了し登録メールアドレスへ購入確認メールが送信されます。
    </p>
  </div>
</template>

<script>
// 定義したステータスコードをインポート
import { OK } from '../../util'
// storeフォルダ内のファイルで定義した「getters」を参照
import { mapGetters } from "vuex";

export default {
  props: {
    productData: { // 商品データ
      default: "",
      required: true,
    },
  },
  computed: {
    ...mapGetters({
      // ログインユーザー(買い手)
      user: "auth/user",
    })
  },
  data() {
    return {
    };
  },
  methods: {
    // 商品購入実行
    async buyProduct() {
      console.log('購入実行！');
      // 未ログインの場合
      if(!this.user) {
        // 買い手ユーザーログイン画面へ遷移
        return this.$router.push({ name: 'buyerLogin'})
      }
      // 買い手ユーザーの場合
      if(this.user.type === 'user') {
        // 商品購入API実行
        const response = await axios.post(`/api/buy/${this.productData.id}`,
          {
            userId: this.user.id,
            userName: this.user.name
          })
        console.log('response:', response);
      
        // api通信失敗の場合
        if (response.status !== OK) {
          // errorストアのsetCodeアクションを呼び出す
          this.$store.commit('error/setCode', response.status)
          return false
        }
        // MESSAGEストアに購入成功メッセージを渡す
        this.$store.commit("message/setContent", {
          content: response.data,
          timeout: 5000
        })
        // リロード
        this.$router.go(0);
      // 買い手ユーザー以外の場合
      } else {
        console.log('買い手ユーザーじゃない');
        // 商品購入は行わない
        return false
      }
      
      
    }
  }
};
</script>
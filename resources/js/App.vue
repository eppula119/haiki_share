<template>
  <div>
    <Header />
    <Message />
    <RouterView />
  </div>
</template>

<script>
// ヘッダーコンポーネント読み込み
import Header from "./components/Header.vue";
// フラッシュメッセージコンポーネント読み込み
import Message from './components/Message.vue'
// 500エラーのステータスコード読み込み
import { INTERNAL_SERVER_ERROR } from './util'

export default {
  components: {
    Header,
    Message
  },
  computed: {
    errorCode() {
      return this.$store.state.error.code // エラーstateを参照
    }
  },
  methods: {},
  watch: {
    errorCode: {
      // errorストアのエラーコードを監視
      handler (val) { // オプション(immediate: true)を付与してるため記述
        if (val === INTERNAL_SERVER_ERROR) {
          // 500エラーの場合は、エラーページ画面へ遷移
          this.$router.push('/500')
        }
      },
      immediate: true
    },
    // routeインスタンス(画面遷移)を監視
    $route () {
      // errorストアのステータスコードを「null」に更新
      this.$store.commit('error/setCode', null)
    }
  }
};
</script>
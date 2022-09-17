import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポート
import BuyerRegister from './components/auth/BuyerRegisterForm.vue' // 新規登録画面(買い手)
import BuyerLogin from './components/auth/BuyerLoginForm.vue' // 新規登録画面(買い手)

// VueRouterプラグインを使用し、<RouterView />コンポーネントを使用可能にする
Vue.use(VueRouter)

// パスに対して表示させるコンポーネントの設定
const routes = [
  {
    path: '/buyer_register',
    component: BuyerRegister
  },
  {
    path: '/buyer_login',
    component: BuyerLogin
  }
]

// VueRouterインスタンス作成
const router = new VueRouter({
   mode: 'history',
  routes
})

// app.jsでインポートするため、VueRouterインスタンスをエクスポート
export default router
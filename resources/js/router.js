import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポート
import Top from './components/Top.vue' //TOP画面
/*
|--------------------------------------------------------------------------
| 認証系
|--------------------------------------------------------------------------
*/
import BuyerRegister from './components/auth/BuyerRegisterForm.vue' // 新規登録画面(買い手)
import BuyerLogin from './components/auth/BuyerLoginForm.vue' // 新規登録画面(買い手)
import SellerRegister from './components/auth/SellerRegisterForm.vue' // 新規登録画面(売り手)
import SellerLogin from './components/auth/SellerLoginForm.vue' // 新規登録画面(売り手)
import PasswordReminder from './components/auth/PasswordReminderForm.vue' // パスワードリマインダー画面
import PasswordReset from './components/auth/PasswordResetForm.vue' // パスワードリセット画面
/*
|--------------------------------------------------------------------------
| ユーザー系
|--------------------------------------------------------------------------
*/
import BuyerEditProfile from './components/users/BuyerEditProfile.vue' // プロフィール編集画面(買い手)
import SellerEditProfile from './components/users/SellerEditProfile.vue' // プロフィール編集画面(売り手)
import BuyerMypage from './components/users/BuyerMypage.vue' // マイページ画面(買い手)
import SellerMypage from './components/users/SellerMypage.vue' // マイページ画面(売り手)
/*
|--------------------------------------------------------------------------
| 商品系
|--------------------------------------------------------------------------
*/
import ProductDetail from './components/products/ProductDetail.vue' // 商品詳細画面
import ProductList from './components/products/ProductList.vue' // 商品一覧画面
import SellProduct from './components/products/SellProduct.vue' // 商品出品画面
import EditProduct from './components/products/EditProduct.vue' // 商品編集画面


// VueRouterプラグインを使用し、<RouterView />コンポーネントを使用可能にする
Vue.use(VueRouter)

// パスに対して表示させるコンポーネントの設定
const routes = [
  // トップ画面
  {
    path: '/',
    component: Top
  },
  /*
  |--------------------------------------------------------------------------
  | 認証系
  |--------------------------------------------------------------------------
  */
  // ユーザー登録画面(買い手)
  {
    path: '/buyer_register',
    component: BuyerRegister
  },
  // ログイン画面(買い手)
  {
    path: '/buyer_login',
    component: BuyerLogin
  },
  // ユーザー登録画面(売り手)
  {
    path: '/seller_register',
    component: SellerRegister
  },
  // ログイン画面(売り手)
  {
    path: '/seller_login',
    component: SellerLogin
  },
  // パスワードリマインダー画面
  {
    path: '/password_reminder',
    component: PasswordReminder
  },
  // パスワードリセット画面
  {
    path: '/password_reset',
    component: PasswordReset
  },
  /*
  |--------------------------------------------------------------------------
  | ユーザー系
  |--------------------------------------------------------------------------
  */
  // プロフィール編集画面(買い手)
  {
    path: '/buyer_edit_profile',
    component: BuyerEditProfile
  },
  // プロフィール編集画面(売り手)
  {
    path: '/seller_edit_profile',
    component: SellerEditProfile
  },
  // マイページ画面(買い手)
  {
    path: '/buyer_mypage',
    component: BuyerMypage
  },
  // マイページ画面(売り手)
  {
    path: '/seller_mypage',
    component: SellerMypage
  },
  /*
  |--------------------------------------------------------------------------
  | 商品系
  |--------------------------------------------------------------------------
  */
  // 商品詳細画面
  {
    path: '/product_detail',
    component: ProductDetail
  },
  // 商品一覧画面
  {
    path: '/product_list',
    component: ProductList
  },
  // 商品出品画面
  {
    path: '/sell_product',
    component: SellProduct
  },
  // 商品編集画面
  {
    path: '/edit_product',
    component: EditProduct
  },
]

// VueRouterインスタンス作成
const router = new VueRouter({
  mode: 'history', // URLの末尾にハッシュ(#)を表示させない設定
  routes
})

// app.jsでインポートするため、VueRouterインスタンスをエクスポート
export default router
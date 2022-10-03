// 定義したステータスコードをインポート
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  productList: [], // 商品リスト
}

const getters = {
  productList: state => state.productList ? state.productList : null, // 現在の商品リスト情報
}

const mutations = {
  // stateの商品リストを更新
  setProductList(state, productList) {
    state.productList = productList;
  },
}

const actions = {
  // stateのproductList情報を更新
  async setProductList(context, productList) {
    context.commit('setProductList', productList)
  },
  // ログアウト
  // async logout(context, data) {
  //   context.commit('setApiStatus', null)
  //   let url = ''
  //   // 売り手、買い手のログアウトそれぞれでAjax通信のURL変更
  //   switch (data) {
  //     case 'user':
  //       // 買い手ユーザー
  //       url = '/api/logout'
  //       break;
  //     case 'shop':
  //       // 売り手ユーザー
  //       url = '/api/shop/logout'
  //       break;
  //     default:
  //   }
  //   // ログアウトAPI実行
  //   const response = await axios.post(url)
  //   // ステータスコードが200(api通信成功)の場合
  //   if (response.status === OK) {
  //     // ユーザー情報をnullに更新し、stateのapiステータスをtrueに更新
  //     context.commit('setApiStatus', true)
  //     context.commit('setUser', null)
  //     return false
  //   }
  //   // api通信成功しなかった場合、apiステータスをfalseに更新
  //   context.commit('setApiStatus', false)
  //   // エラーストアのステータスコードを更新
  //   context.commit('error/setCode', response.status, { root: true })
  // },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
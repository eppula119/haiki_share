
const state = {
  productList: [], // 商品リスト
  params: {} // 絞り込みパラメーター
}

const getters = {
  productList: state => state.productList ? state.productList : null, // 現在の商品リスト情報
  params: state => state.params ? state.params : null, // 現在の絞り込みパラメーター
}

const mutations = {
  // stateの商品リストを更新
  setProductList(state, productList) {
    state.productList = productList;
  },
  // stateの商品リストに新しく取得した商品を追加
  updateProduct(state, product) {
    console.log('product:', product);
    // 商品リストから新しく取得した商品データの存在確認
    const existProductFlg = state.productList.find(el => el.id === product.id)
    // 存在する場合
    if (existProductFlg) {
      // 商品リスト配列の何番目に新しく取得した商品データが存在するかキー番号を保持
      const index = state.productList.findIndex(el => el.id === product.id)
      // 商品リストを更新
      state.productList[index] = product
    } else {
      // 存在しない場合は、商品リストに追加
      state.productList.push(product)
    }
  },
  // stateの絞り込みパラメーターを更新
  updateParams(state, params) {
    state.params = params;
  },
}

const actions = {
  // stateのproductList情報を更新
  async setProductList(context, productList) {
    context.commit('setProductList', productList)
  },
  // stateのproductListに新しく取得した商品データを追加
  async updateProduct(context, product) {
    context.commit('updateProduct', product)
  },
  // stateのparams情報を更新
  async updateParams(context, params) {
    context.commit('updateParams', params)
  },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
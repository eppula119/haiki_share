const state = {
  code: null // ステータスコード
}

const mutations = {
  // ステータスコード更新
  setCode(state, code) {
    state.code = code
  }
}

export default {
  namespaced: true,
  state,
  mutations
}
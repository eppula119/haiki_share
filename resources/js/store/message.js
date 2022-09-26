const state = {
  content: '' // メッセージ内容
}

const mutations = {
  setContent(state, { content, timeout }) {
    state.content = content

    setTimeout(() => { state.content = '' }, timeout)

  }
}

export default {
  namespaced: true,
  state,
  mutations
}
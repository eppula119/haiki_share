const state = {
  content: '' // メッセージ内容
}

const mutations = {
  // stateのメッセージ内容更新
  setContent(state, { content, timeout }) {
    state.content = content
    // 指定時間経過後、メッセージを初期化
    setTimeout(() => { state.content = '' }, timeout)

  }
}

export default {
  namespaced: true,
  state,
  mutations
}
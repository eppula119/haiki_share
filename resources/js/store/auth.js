// 定義したステータスコードをインポート
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  user: null, // ログイン済みユーザー
  apiStatus: null, // API呼び出しが成功か失敗したか
  loginErrorMessages: null, // ログインエラーメッセージ
  registerErrorMessages: null, // ユーザー登録エラーメッセージ
  forgotErrorMessages: null, // パスワードリマインダーエラーメッセージ
  resetErrorMessages: null, // パスワードリセットエラーメッセージ
}

const getters = {
  user: state => state.user ? state.user : null, // 現在のユーザー情報
  check: state => !!state.user, // ログインチェック
}

const mutations = {
  // stateのユーザー情報を更新
  setUser(state, user) {
    state.user = user
  },
  // stateのapiStatusを更新
  setApiStatus(state, status) {
    state.apiStatus = status
  },
  // stateのloginErrorMessagesを更新
  setLoginErrorMessages(state, messages) {
    state.loginErrorMessages = messages
  },
  // stateのregisterErrorMessagesを更新
  setRegisterErrorMessages(state, messages) {
    state.registerErrorMessages = messages
  },
  // stateのforgotErrorMessagesを更新
  setForgotErrorMessages(state, messages) {
    state.forgotErrorMessages = messages
  },
  // stateのresetErrorMessagesを更新
  setResetErrorMessages(state, messages) {
    state.resetErrorMessages = messages
  },
}

const actions = {
  // ユーザー登録
  async register(context, data) {
    context.commit('setApiStatus', null)
    let url = ''
    // 売り手、買い手のユーザー登録それぞれでAjax通信のURL変更
    switch (data.type) {
      case 'user':
        // 買い手ユーザー
        url = '/api/register'
        break;
      case 'shop':
        // 売り手ユーザー
        url = '/api/shop/register'
        break;
      default:
    }
    // ユーザー登録API実行
    const response = await axios.post(url, data)
    
    // ステータスコードが201(api通信成功)の場合
    if (response.status === CREATED) {
      // ユーザー情報を更新し、stateのapiステータスをtrueに更新
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }
    context.commit('setApiStatus', false)
    // ステータスコードが422(バリデーションエラー)の場合
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setRegisterErrorMessages', response.data.errors)
    } else {
      // エラーストアのステータスコードを更新
      // { root: true }は、他モジュールのメソッドを使用するために必要な設定)
      context.commit('error/setCode', response.status, { root: true })
    }
  },
  // ログイン
  async login(context, data) {
    context.commit('setApiStatus', null)
    let url = ''
    // 売り手、買い手のログインそれぞれでAjax通信のURL変更
    switch (data.type) {
      case 'user':
        // 買い手ユーザー
        url = '/api/login'
        break;
      case 'shop':
        // 売り手ユーザー
        url = '/api/shop/login'
        break;
      default:
    }
    // ログインAPI実行
    const response = await axios.post(url, data)
    // ステータスコードが200(api通信成功)の場合
    if (response.status === OK) {
      // ユーザー情報を更新し、stateのapiステータスをtrueに更新
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }
    // apiステータスをfalseに更新
    context.commit('setApiStatus', false)
    // ステータスコードが422(バリデーションエラー)の場合
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setLoginErrorMessages', response.data.errors)
    } else {
      // エラーストアのステータスコードを更新
      context.commit('error/setCode', response.status, { root: true })
    }

  },
  // ログアウト
  async logout(context, data) {
    context.commit('setApiStatus', null)
    let url = ''
    // 売り手、買い手のログアウトそれぞれでAjax通信のURL変更
    switch (data) {
      case 'user':
        // 買い手ユーザー
        url = '/api/logout'
        break;
      case 'shop':
        // 売り手ユーザー
        url = '/api/shop/logout'
        break;
      default:
    }
    // ログアウトAPI実行
    const response = await axios.post(url)
    // ステータスコードが200(api通信成功)の場合
    if (response.status === OK) {
      // ユーザー情報をnullに更新し、stateのapiステータスをtrueに更新
      context.commit('setApiStatus', true)
      context.commit('setUser', null)
      return false
    }
    // api通信成功しなかった場合、apiステータスをfalseに更新
    context.commit('setApiStatus', false)
    // エラーストアのステータスコードを更新
    context.commit('error/setCode', response.status, { root: true })
  },
  // ログインユーザー
  async currentUser(context) {
    context.commit('setApiStatus', null)
    // ログインユーザー情報取得API実行
    const response = await axios.get('/api/user')
    const user = response.data || null
    context.commit('setUser', user)
    // ステータスコードが200(api通信成功)の場合
    if (response.status === OK) {
      // ユーザー情報をnullに更新し、stateのapiステータスをtrueに更新
      context.commit('setApiStatus', true)
      context.commit('setUser', user)
      return false
    }
    // api通信成功しなかった場合、apiステータスをfalseに更新
    context.commit('setApiStatus', false)
    // エラーストアのステータスコードを更新
    context.commit('error/setCode', response.status, { root: true })
  },
  // パスワードリマインダー
  async forgot(context, data) {
    context.commit('setApiStatus', null)
    let url = ''
    // 売り手、買い手のパスワードリマインダーそれぞれでAjax通信のURL変更
    switch (data.type) {
      case 'user':
        // 買い手ユーザー
        url = '/api/forgot'
        break;
      case 'shop':
        // 売り手ユーザー
        url = '/api/shop/forgot'
        break;
      default:
    }
    
    // パスワードリマインダーAPI実行
    const response = await axios.post(url, { email: data.email })
    // ステータスコードが201(api通信成功)の場合
    if (response.status === CREATED) {
      // ユーザー情報を更新し、stateのapiステータスをtrueに更新
      context.commit('setApiStatus', true)
      return false
    }
    // apiステータスをfalseに更新
    context.commit('setApiStatus', false)
    // ステータスコードが422(バリデーションエラー)の場合
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setForgotErrorMessages', response.data.errors)
    } else {
      // エラーストアのステータスコードを更新
      context.commit('error/setCode', response.status, { root: true })
    }

  },
  // パスワードリセット
  async reset(context, data) {
    context.commit('setApiStatus', null)
    let url = ''
    // 売り手、買い手のパスワードリセットそれぞれでAjax通信のURL変更
    switch (data.type) {
      case 'user':
        // 買い手ユーザー
        url = '/api/reset'
        break;
      case 'shop':
        // 売り手ユーザー
        url = '/api/shop/reset'
        break;
      default:
    }

    // パスワードリセットAPI実行
    const response = await axios.post(url, {
      password: data.password,
      password_confirmation: data.password_confirmation,
      token: data.token
    })
    // ステータスコードが200(api通信成功)の場合
    if (response.status === OK) {
      // ユーザー情報を更新し、stateのapiステータスをtrueに更新
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }
    // apiステータスをfalseに更新
    context.commit('setApiStatus', false)
    // ステータスコードが422(バリデーションエラー)の場合
    if (response.status === UNPROCESSABLE_ENTITY) {
      console.log('response:', response);
      // errorsオブジェクトに「'token'」のキーが存在する場合
      if (response.data.errors['token'] == undefined ? false : true) {
        // フラッシュメッセージでエラー文言を表示
        context.commit('message/setContent',
          {
            content: response.data.errors.token[0],
            timeout: 3000
          },
          {
            root: true
          })
        console.log('response.data.errors.token[0]:', response.data.errors.token[0]);
      } else {
        // パスワードリセットエラーメッセージステータスを更新
        context.commit('setResetErrorMessages', response.data.errors)
      }
    } else {
      // エラーストアのステータスコードを更新
      context.commit('error/setCode', response.status, { root: true })
    }

  },
  // ユーザー情報を更新
  setUser(context, data) {
    context.commit('setUser', data)
  },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}

/**
 * クッキーの値を取得する
 * @param {String} searchKey 検索するキー
 * @returns {String} キーに対応する値
 */
export function getCookieValue(searchKey) {
  if (typeof searchKey === 'undefined') {
    return ''
  }

  let val = ''


  // 「document.cookie」は、「name=12345; token = 67890; key = abcde」のような形式でクッキーを参照
  document.cookie.split(';').forEach(cookie => {
    const [key, value] = cookie.split('=')
    if (key === searchKey) {
      return val = value
    }
  })

  return val
}

// 最上部へ戻る
export function returnTop() {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  })
}

/*
|--------------------------------------------------------------------------
| レスポンスコード
|--------------------------------------------------------------------------
*/
export const OK = 200
export const CREATED = 201
export const BAD_REQUEST = 400 // クライアントエラー、リクエストの処理ができない
export const UNPROCESSABLE_ENTITY = 422 // Laravelのバリデーションエラーは422を返す為
export const HTTP_FORBIDDEN = 403 // アクセス権なし
export const INTERNAL_SERVER_ERROR = 500
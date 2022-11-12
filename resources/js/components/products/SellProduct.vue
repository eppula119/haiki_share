<template>
  <div>
    <Loading v-if="showLoadingFlg" />
    <div class="p-userMainContainer">
      <h1 class="p-userMainContainer__title">
        商品出品
      </h1>
      <div class="p-userMainContainer__formWrap">
        <form class="p-form" @submit.prevent="submit">
          <!------------------------ 出品画像欄 ---------------------------->
          <p class="p-sellDesc">出品画像(最大5枚)</p>
          <div class="p-uploadedImgContainer">
              <div class="p-uploadedImgContainer__block" v-for="(img, index) in sellForm.images" :key="index">
                <button class="p-imgDeleteButton c-button c-button--bgGray" @click.prevent="deleteImg(index)">
                  <i class="fa-solid fa-xmark"></i>
                </button>
                <img :src="sellForm.images[index].url" class="p-uploadedImg">
              </div>
          </div>
          <div class="p-uploadPointBlock"
            :class="{'is-active': dragFlg}"
            @dragenter="dragImg"
            @dragleave="dragLeaveImg"
            @dragover.prevent
            @drop.prevent="dropImg">
            <label for="filename" class="p-uploadPointBlock__label">
              <input type="file" id="filename" class="p-uploadImgButton c-button c-button--bgBlue" value="画像を選択" @change="onImgChange">
              <p class="p-uploadImgText">ファイルをドラッグ＆ドロップ</p>
              <p class="p-uploadImgText p-responsiveText">ファイルを選択</p>
            </label>
            
            <!-- バリデーションエラーメッセージ表示箇所 -->
            <div class="p-formValidate" v-if="errors">
              <ul v-if="errors.image">
                <li v-for="msg in errors.image" :key="msg">※{{ msg }}</li>
              </ul>
            </div>
          </div>
          <h2 class="p-formDesc">商品詳細</h2>
          <!------------------------ 商品名欄 ---------------------------->
          <label class="p-formLabel">商品名</label>
          <span class="p-formAttention">※必須</span>
          <!-- 入力フォーム -->
          <input class="p-formInput" type="text" v-model="sellForm.name">
          <!-- バリデーションエラーメッセージ表示箇所 -->
          <div class="p-formValidate" v-if="errors">
            <ul v-if="errors.product_name">
              <li v-for="msg in errors.product_name" :key="msg">※{{ msg }}</li>
            </ul>
          </div>
          <!------------------------ 価格欄 ---------------------------->
          <label class="p-formLabel">価格</label>
          <span class="p-formAttention">※必須</span>
          <!-- 入力フォーム -->
          <input type="number" class="p-formInput" placeholder="¥" v-model="sellForm.price">
          <!-- バリデーションエラーメッセージ表示箇所 -->
          <div class="p-formValidate" v-if="errors">
            <ul v-if="errors.price">
              <li v-for="msg in errors.price" :key="msg">※{{ msg }}</li>
            </ul>
          </div>
          <!------------------------ 賞味期限(日付)欄 ---------------------------->
          <label class="p-formLabel">賞味期限(日付)</label>
          <span class="p-formAttention">※必須</span>
          <!-- カレンダー入力フォームコンポーネント -->
          <vue-date-picker
            format="yyyy-MM-dd"
            :language="ja"
            calendar-class="p-calendar"
            wrapper-class="p-formCalendar"
            input-class="p-formCalendar__input"
            v-model="sellForm.best_day"
          ></vue-date-picker>
          <!-- バリデーションエラーメッセージ表示箇所 -->
          <div class="p-formValidate" v-if="errors">
            <ul v-if="errors.best_day">
              <li v-for="msg in errors.best_day" :key="msg">※{{ msg }}</li>
            </ul>
          </div>
          <!------------------------ 賞味期限(時間)欄 ---------------------------->
          <label class="p-formLabel">賞味期限(時間)</label>
          <span class="p-formAttention">※必須</span>
          <!-- 時間入力フォームコンポーネント -->
          <vue-time-picker
            class="p-formInput"
            v-model="sellForm.best_time"
            hour-label="時"
            minute-label="分"
            placeholder="時間を入力"
            minute-interval="15"
            input-class="p-formInput__time"
            hide-clear-button
          ></vue-time-picker>
          <!-- バリデーションエラーメッセージ表示箇所 -->
          <div class="p-formValidate" v-if="errors">
            <ul v-if="errors.best_time">
              <li v-for="msg in errors.best_time" :key="msg">※{{ msg }}</li>
            </ul>
          </div>
          <!------------------------ 出品ボタン ---------------------------->
          <input type="submit" class="p-formMainButton c-button c-button--bgBlue" value="出品する">
        </form>
      </div>
    </div>
  </div>
</template>

<script>
// 定義したステータスコードをインポート
import { CREATED, UNPROCESSABLE_ENTITY } from '../../util'
// カレンダー入力プラグインをインポート
import VueDatePicker from 'vuejs-datepicker';
// カレンダープラグラインの言語用のプラグインをインポート
import {ja} from 'vuejs-datepicker/dist/locale'
// 時刻入力プラグインをインポート
import VueTimePicker from 'vue2-timepicker'
import 'vue2-timepicker/dist/VueTimepicker.css'
// ローディングコンポーネント読み込み
import Loading from '../Loading.vue'


export default {
  components: {
    VueDatePicker,
    VueTimePicker,
    Loading
  },
  created () {
    // 今日の日付を任意の表記にフォーマット化
    const initBestDat = this.dayToFormat(new Date(), 'YYYY-MM-DD')
    // フォーマット化した今日の日付をデータへ渡す
    this.sellForm.best_day = initBestDat
  },
  data: function() {
    return {
      // 出品フォーム入力値
      sellForm: {
        images: [], // 商品画像
        name: '', // 商品名
        price: '', // 価格
        best_day: '', // 賞味期限(日付)
        best_time: '00:00', // 賞味期限(時間)
      },
      errors: {}, // バリデーションエラーメッセージ
      dragFlg: false, // 画像ドラッグエリア内へドラッグ中か判別
      ja:ja, // カレンダーの言語用のプラグインを日本語化
      showLoadingFlg: false // ローディング表示フラグ
    };
  },
  methods: {
    // 画像をアップロードエリアにドラッグ
    dragImg() {
        console.log('Enter Drop Area');
        this.dragFlg = true;
    },
    // ドラッグ&ドロップエリアから外れる
    dragLeaveImg() {
      this.dragFlg = false;
    },
    // 画像をドロップ
    dropImg(event) {
      console.log(event.dataTransfer.files)
      console.log(event.dataTransfer.files[0])
      // ファイルが画像ではなかったら処理中断
      if (!event.dataTransfer.files[0].type.match('image.*')) {
        console.log("画像ファイルではありません");
        this.dragFlg = false
        return false
      }
      // 5枚アップロード済みの場合は処理中断
      if (this.sellForm.images.length >= 5) {
        console.log("既に5枚の画像をアップロード済みです");
        this.dragFlg = false
        return false
      }
      
      let imgObj = { url: '', uploadFile: '' };
      imgObj.url = URL.createObjectURL(event.dataTransfer.files[0])
      imgObj.uploadFile = event.dataTransfer.files[0]
      this.sellForm.images.push(imgObj)

      this.dragFlg = false
    },
    // 画像を削除
    deleteImg(index) {
      this.sellForm.images.splice(index, 1)
    },
    // 商品画像の選択時に実行される
    onImgChange (event) {
      console.log('event.target.files:', event.target.files);
      console.log('event.target.files[0]:', event.target.files[0]);
      // ファイルが画像ではなかったら処理中断
      if (! event.target.files[0].type.match('image.*')) {
        console.log("画像ファイルではありません");
      }
      // 5枚アップロード済みの場合は処理中断
      if (this.sellForm.images.length >= 5) {
        console.log("既に5枚の画像をアップロード済みです");
        return false
      }

      // FileReaderクラスのインスタンスを取得
      const reader = new FileReader()
      let self = this;
      let imgObj = {url: '', uploadFile: ''};

      // ファイルを読み込み終わったタイミングで実行する処理
      reader.onload = e => {
        console.log('e.target.result:', e.target.result);
        imgObj.url = e.target.result;
        imgObj.uploadFile = event.target.files[0];
        self.sellForm.images.push(imgObj);
        console.log('imgObj:', imgObj);
        console.log('self.sellForm:', self.sellForm);
      }

      // ファイル読み込み。読み込まれたファイルはデータURL形式で受け取れる
      reader.readAsDataURL(event.target.files[0])
      console.log('onImgChange最後の行')
    },
    // 出品実行
    async submit() {
      // ローディング表示
      this.showLoadingFlg = true
      let formData = new FormData()
      // 日付を任意の表記にフォーマット化
      const bestDay = this.dayToFormat(new Date(this.sellForm.best_day), 'YYYY-MM-DD')
  
      formData.append('product_name', this.sellForm.name) // 商品名を追加
      formData.append('price', this.sellForm.price) // 値段を追加
      formData.append('best_day', bestDay) // 賞味期限(日付)を追加
      formData.append('best_time', this.sellForm.best_time) // 賞味期限(時間)を追加
      // アップロードした商品画像の枚数分ループ
      for (let i = 0; i < this.sellForm.images.length; i++) {
        const imgNumber = i + 1;
        formData.append('image_' + imgNumber, this.sellForm.images[i].uploadFile);
      }
      console.log(...formData.entries());

      let config = {
        headers: {
          'content-type': 'multipart/form-data',
        },
      };

      // 出品API実行
      const response = await axios.post('/api/product', formData, config)
      // ステータスコードが422(バリデーションエラー)の場合
      if (response.status === UNPROCESSABLE_ENTITY) {
        const errors = response.data.errors
        // 自作エラーオブジェクト定義
        let customErros = {}
        // 画像バリデーションエラーフラグ
        let imageErrorFlg = false
        /* バリデーションエラーオブジェクトの数だけループ
          複数の画像でバリデーションエラー時、最初のエラーのみ表示させる処理 */
        for (let key of Object.keys(errors)) {
          // キー名に「image_」が含まれ、最初の画像バリデーションエラーオブジェクトの場合
          if(!key.indexOf('image_') && !imageErrorFlg){
            // 自作エラーオブジェクトにそのままエラーオブジェクトを渡す
            customErros['image'] = errors[key]
            // 画像バリデーションエラーをtrue
            imageErrorFlg = true
          // キー名に「image_」が含まれてない場合
          } else if(key.indexOf('image_')) {
            // 自作エラーオブジェクトにそのままエラーオブジェクトを渡す
            customErros[key] = errors[key]
          }
        }
        // 自作バリデーションメッセージオブジェクトをデータに渡す
        this.errors = customErros
        // ローディング非表示
        this.showLoadingFlg = false
        return false
      }

      console.log('出品実行完了')
      console.log('response:', response);
      // 入力値の初期化
      this.reset()
      // api通信失敗の場合
      if (response.status !== CREATED) {
        // errorストアのsetCodeアクションを呼び出す
        this.$store.commit('error/setCode', response.status)
        // ローディング非表示
        this.showLoadingFlg = false
        return false
      }
      // 出品実行後、レスポンスメッセージ表示
      this.showMessage(response.data.message)
      // 出品した商品の詳細画面へ遷移
      this.$router.push(`/product_list/${response.data.product.id}`)
    },
    // 入力値初期化
    reset() {
      console.log("リセット実行");
      this.sellForm = {
        images: [],
        name: '',
        price: '',
        best_day: '',
        best_time: '00:00',
      }
      this.sellForm.images
    },
    // 今日の日付を任意の表記にフォーマット化
    dayToFormat(date, format) {
      // フォーマット文字列内のキーワードを日付に置換する
      format = format.replace(/YYYY/g, date.getFullYear());
      format = format.replace(/MM/g, ('0' + (date.getMonth() + 1)).slice(-2));
      format = format.replace(/DD/g, ('0' + date.getDate()).slice(-2));
      return format
    },
    // フラッシュメッセージを表示
    showMessage(message) {
      console.log('message:', message);
      // MESSAGEストアに購入キャンセル成功メッセージを渡す
      this.$store.commit("message/setContent", {
        content: message,
        timeout: 5000
      })
    }
  }
};
</script>
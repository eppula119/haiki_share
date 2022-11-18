<template>
  <div>
    <!------------------------ 都道府県の絞り込みモーダル欄 ---------------------------->
    <template v-if="filterType === 'prefecture'">
      <span class="c-contentHead">都道府県</span>
      <div class="c-pullDownCheckWrap" v-if="updateDataFlg">
        <div
          class="c-pullDownCheckWrap__block"
          v-for="(prefectureListByRegion, region) in prefectureList"
          :key="region">
          <div class="c-regionBlock">
            <label class="c-pullDownCheckItem">
              <input
                type="checkbox"
                class="c-contentCheckbox"
                @click="selectRegion(region, prefecture.region[region].isRegionSelected)"
                v-model="prefecture.region[region].isRegionSelected">{{ region }}
            </label>
            <span class="c-pullDownIcon" @click="changePrefectureFlg(region)">↓</span>
          </div>
          <div class="c-prefectureBlock" :class="{ 'is-active': prefecture.region[region].showPrefectureFlg }" >
            <label
              class="c-pullDownCheckWrap__childItem"
              v-for="(selectItem, index) of prefectureListByRegion" :key="index">
              <input
                type="checkbox"
                class="c-contentCheckbox"
                @change="selectPrefecture(region, prefecture.region[region].isRegionSelected, prefecture.region[region].isPrefectureSelected.length)"
                v-model="prefecture.region[region].isPrefectureSelected"
                :value="selectItem.name">{{ selectItem.name }}
            </label>
          </div>
        </div>
      </div>
    </template>
    <!------------------------ 価格の絞り込みモーダル欄 ---------------------------->
    <template v-if="filterType === 'price'">
      <span class="c-contentHead">価格</span>
      <div class="c-contentWrap">
        <input type="number" class="c-contentWrap__input" v-model="price.min">
        <span class="c-contentWrap__text">-</span>
        <input type="number" class="c-contentWrap__input" v-model="price.max">
      </div>
    </template>
    <!------------------------ 賞味期限の絞り込みモーダル欄 ---------------------------->
    <template v-if="filterType === 'bestBefore'">
      <span class="c-contentHead">賞味期限</span>
      <div class="c-checkboxWrap">
        <label for="expired" class="c-checkboxWrap__label">
          <input type="checkbox" id="expired" class="c-checkboxInput" v-model="bestBefore" :value="false">賞味期限切れ
        </label>
        <label for="bestBefore" class="c-checkboxWrap__label">
          <input type="checkbox" id="bestBefore" class="c-checkboxInput" v-model="bestBefore" :value="true">賞味期限内
        </label>
      </div>
    </template>
  </div>
</template>

<script>

export default {
  props: {
    filterType: { // 絞り込みモーダル種別
      default: "",
      required: true,
    },
    prefectureList: { // 都道府県リスト
      type: Object,
      default: {},
      required: false,
    }
  },
  created () {
    this.createPrefectureData() // 都道府県選択値の受け取りデータ形式作成
  },
  data() {
    return {
      price: { // 価格入力値
        min: '',
        max: '',
      },
      bestBefore: [], // 賞味期限入力値
      prefecture: { // 都道府県入力値
        region: {
          '都道府県名': {
            isPrefectureSelected: [],
            isRegionSelected: false,
            showPrefectureFlg: false
          }
        }
      },
      updateDataFlg : true, // 更新データを再描画するための判定フラグ
    };
  },
  methods: {
    // 絞り込み実施
    async doFilter() {
      // 都道府県入力値を整形
      const prefecture = this.prefectureValue()
      
      const params = {
        min: this.price.min,
        max: this.price.max,
        bestBefore: this.bestBefore[0],
        prefecture
      }
      // 商品ストアにパラメーターを渡す
      this.$store.dispatch("product/updateParams", params);
      // モーダルを閉じる
      this.$emit("close-modal")
      // 絞り込みした商品リストを取得
      this.$emit("get-filter-products")
    },
    // 条件クリア
    doClear() {
      switch (this.filterType) {
        case 'price': // 価格絞り込みの場合
          this.price = { min: '', max: '' }
          break;
        case 'bestBefore': // 賞味期限絞り込みの場合
          this.bestBefore = []
        case 'prefecture': // 都道県絞り込みの場合
          this.createPrefectureData()　
          break;
        default:
      }
    },
    // 地方毎の都道府県全選択、または全選択解除
    selectRegion(region, isRegionSelected) {
      // 地方欄が選択されている場合
      if(isRegionSelected) {
        // 再描画フラグをfalse
        this.updateDataFlg = false
        // 選択した地方欄の都道府県を全て選択解除にする
        this.prefecture.region[region].isPrefectureSelected = []
        // 地方欄も選択解除
        this.prefecture.region[region].isRegionSelected = false
        // 再描画して更新データを反映させる
        this.updateDataFlg = true
      // 地方欄が選択されていない場合
      } else {
        this.updateDataFlg = false
        // 一度、選択した地方欄の都道府県を全て選択解除
        this.prefecture.region[region].isPrefectureSelected = []
        // 地方欄を選択状態にする
        this.prefecture.region[region].isRegionSelected = true
        // 選択した地方かつ、選択可能な都道府県の数だけループ
        this.prefectureList[region].forEach(prefecture => {
          // 都道府県全て選択状態にする
          this.prefecture.region[region].isPrefectureSelected.push(prefecture.name)
        })
        // 都道府県リストを表示する
        this.prefecture.region[region].showPrefectureFlg = true
        // 再描画して更新データを反映
        this.updateDataFlg = true
      }
        
    },
    // 都道府県選択または選択解除
    selectPrefecture(region, isRegionSelected, selectedLength) {
      // 再描画フラグをfalse
      this.updateDataFlg = false
      // 地方欄が選択されていない場合
      if(!isRegionSelected) {
        // 地方欄を選択状態にする
        this.prefecture.region[region].isRegionSelected = true
      // 地方欄が選択されており、選択した地方の都道府県が1つも選択されてない場合
      } else if(isRegionSelected && selectedLength === 0) {
        // 地方欄の選択解除
        this.prefecture.region[region].isRegionSelected = false
      }
      // 再描画して更新データを反映
      this.updateDataFlg = true
    },
    // 都道府県リストの表示・非表示実行
    changePrefectureFlg(region) {
      // 再描画フラグをfalse
      this.updateDataFlg = false
      // 地方欄に紐づいた都道府県リストを表示中なら非表示、非表示中なら表示する
      this.prefecture.region[region].showPrefectureFlg = !this.prefecture.region[region].showPrefectureFlg
      // 再描画して更新データを反映
      this.updateDataFlg = true
    },
    // 都道府県入力値を整形
    prefectureValue() {
      let prefectureValue = []
      Object.keys(this.prefecture.region).forEach( (key) => {
        prefectureValue = prefectureValue.concat(this.prefecture.region[key].isPrefectureSelected)
      });
      return prefectureValue
    },
    // 都道府県選択値の受け取りデータ形式作成
    createPrefectureData() {
      this.prefecture.region = {}
      // 地方の数だけループ
      Object.keys(this.prefectureList).forEach( (key, i) => {
        // 地方毎の都道府県入力値と選択した地方の都道府県全選択のデータを定義
        this.prefecture.region[key] = {
          isPrefectureSelected: [],
          isRegionSelected: false,
          showPrefectureFlg: false
        }
      })
    },
  },
  watch: {
    bestBefore: { // 賞味期限プロパティ監視
      handler(newVal, oldVal) {
        // 賞味期限絞り込みのチェックが1つ以上の場合
        if (this.bestBefore.length > 1) {
            // 最初にチェックした値を削除(1つしか選択できないようにする処理)
            this.bestBefore.shift()
        }
      },
      deep: true
    }
  }
};
</script>
<template>
  <div>
    <div class="c-modalOverlay" :class="{ 'is-active': modalFlg }" @click="closeModal()"></div>
    <div class="c-modal" :class="{ 'is-active': modalFlg, 'c-modalPrefecture': childType === 'prefecture' }">
      <div class="c-modal__content">
        <!------------------------ モーダルの子コンポーネント ---------------------------->
        <BuyModal
          ref="buyModal"
          :productData="data"
          :type="type"
          v-if="type === 'buy' || type === 'cancel'"
          @update-page="updatePage"></BuyModal>
        <FilterModal
          ref="filterModal"
          :filterType="childType"
          :prefecture-list="prefectureList"
          @get-filter-products="getFilterProducts"
          @close-modal="closeModal()"
          v-if="type === 'filter'"></FilterModal>
      </div>
      <div class="c-modal__buttonWrap">
        <button class="c-modalDoButton c-button c-button--bgGray" @click="closeModal">キャンセル</button>
        <button class="c-modalDoButton c-button c-button--bgBlue" @click="buyProduct" v-if="type === 'buy'">購入する</button>
        <button class="c-modalDoButton c-button c-button--bgBlue" @click="cancelProduct" v-else-if="type === 'cancel'">購入キャンセルする</button>
        <button class="c-modalDoButton c-button c-button--bgBlue" @click="doFilter" v-else-if="type === 'filter'">検索</button>
      </div>
    </div>
  </div>
</template>

<script>
// 購入確認モーダル読み込み
import BuyModal from './BuyModal'
// 絞り込みモーダル読み込み
import FilterModal from './FilterModal'


export default {
  components: {
    BuyModal,
    FilterModal
  },
  props: {
    prefectureList: { // 都道府県リスト
      type: Object,
      default: {},
      required: false,
    }
  },
  data() {
    return {
      modalFlg: false, // モーダルの状態(開いている or 閉じている)
      type: '', // 主モーダル種別
      childType: '', // 子モーダル種別
      data: '' // 受け取ってきたデータ
    };
  },
  methods: {
    // モーダルを開く
    openModal(type) {
      // 開くモーダル種別をデータに渡す
      this.type = type.type
      this.childType = type.childType
      // 受け取ってきたデータを渡す
      this.data = type.data
      // モーダルを開いた状態にする
      this.modalFlg = true
    },
    // モーダルを閉じる
    closeModal() {
      this.modalFlg = false
    },
    // 購入関連モーダルコンポーネントの商品購入メソッド実行
    buyProduct() {
      this.$refs.buyModal.buyProduct()
    },
    // 購入関連モーダルコンポーネントの購入キャンセルメソッド実行
    cancelProduct() {
      this.$refs.buyModal.cancelProduct()
    },
    // 絞り込み関連モーダルコンポーネントの絞り込みメソッド実行
    doFilter() {
      this.$refs.filterModal.doFilter()
    },
    // 絞り込み商品リスト取得メソッド実行
    getFilterProducts() {
      this.$emit("get-filter-products")
    },
    // ページ情報を更新
    updatePage(message) {
      this.$emit("update-page", message)
    },
  }
};
</script>
<template>
  <div>
    <div class="c-modalOverlay" :class="{ 'is-active': modalFlg }" @click="closeModal()"></div>
    <div class="c-modal" :class="{ 'is-active': modalFlg }">
      <div class="c-modal__content">
        <!------------------------ モーダルの子コンポーネント ---------------------------->
        <BuyModal ref="buyModal" :productData="data" v-if="type === 'buy'"></BuyModal>
        <FilterModal :filterType="childType" v-if="type === 'filter'"></FilterModal>
      </div>
      <div class="c-modal__buttonWrap">
        <button class="c-modalDoButton c-button c-button--bgGray" @click="closeModal">キャンセル</button>
        <button class="c-modalDoButton c-button c-button--bgBlue" @click="buyProduct" v-if="type === 'buy'">購入する</button>
        <button class="c-modalDoButton c-button c-button--bgBlue" v-else-if="type === 'filter'">保存</button>
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
    // 購入確認モーダルコンポーネントの商品購入メソッド実行
    buyProduct() {
      this.$refs.buyModal.buyProduct()
    }
  }
};
</script>
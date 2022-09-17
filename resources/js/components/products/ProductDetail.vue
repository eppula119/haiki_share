<template>
  <div>
    <!------------------------ モーダル欄 ---------------------------->
  <div class="c-modalOverlay"></div>
  <div class="c-modal c-modalBuy">
    <div class="c-modal__content">
      <!------------------------ 商品購入モーダル欄 ---------------------------->
        <div class="c-contentImgBlock">
          <img src="/images/item.png" class="c-contentImgBlock__img">
          <span class="c-contentImgBlock__title">ガリガリ君リッチ！</span>
        </div>
        <dl class="c-contentInfoRow">
          <dt class="c-contentInfoRow__title">価格</dt>
          <dd class="c-contentInfoRow__detail">¥1200円(税込)</dd>
        </dl>
        <dl class="c-contentInfoRow">
          <dt class="c-contentInfoRow__title">賞味期限</dt>
          <dd class="c-contentInfoRow__detail">2023年10月31日まで</dd>
        </dl>
        <dl class="c-contentInfoRow">
          <dt class="c-contentInfoRow__title">コンビニ名</dt>
          <dd class="c-contentInfoRow__detail">ローソン | 行田店</dd>
        </dl>
        <dl class="c-contentInfoRow">
          <dt class="c-contentInfoRow__title">住所</dt>
          <dd class="c-contentInfoRow__detail">東京都新宿区新宿x丁目x番地 hogehogeビル3F</dd>
        </dl>
        <p class="c-contentNote">
          ※上記の商品で間違いないか確認した上で「購入する」ボタンを押してください。
          ボタン押下後、決済が完了し登録メールアドレスへ購入確認メールが送信されます。
        </p>
    </div>
    <div class="c-modal__buttonWrap">
      <button class="c-modalDoButton c-button c-button--bgGray">キャンセル</button>
      <button class="c-modalDoButton c-button c-button--bgBlue">保存</button>
    </div>
  </div>
  <!------------------------ 商品説明・商品画像欄 ---------------------------->
  <div class="p-productMainContainer">
    <!------------------------ 商品詳細説明欄 ---------------------------->
    <section class="p-productDetailContainer">
      <p class="p-productDetailContainer__title">ガリガリ君リッチ！</p>
      <p class="p-productDetailContainer__bestBeforeText">賞味期限<span class="p-bestBeforeDate">2023年10月まで</span></p>
      <p class="p-productDetailContainer__price">
        価格
        <span class="p-priceBigText">¥1,200</span>
        税込
      </p>
      <button class="p-productDetailContainer__button c-button c-button--bgBlue">購入する</button>
      <button class="p-productDetailContainer__button c-button c-button--bgGray">購入済み</button>
      <button class="p-productDetailContainer__button c-button c-button--bgBlue">購入をキャンセル</button>
      <div class="p-productDetailContainer__info">
        <dl class="p-infoRow">
          <dt class="p-infoRow__title">コンビニ名</dt>
          <dd class="p-infoRow__detail">ローソン | 行田店</dd>
        </dl>
        <dl class="p-infoRow">
          <dt class="p-infoRow__title">住所</dt>
          <dd class="p-infoRow__detail">東京都新宿区新宿x丁目x番地 hogehogeビル3</dd>
        </dl>
        <dl class="p-infoRow">
          <dt class="p-infoRow__title">詳細</dt>
          <dd class="p-infoRow__detail">
            賞味期限がギリギリのため、ほぼ半額の値段で販売させていただきます。関東地方以外への発送は2日以上かかる場合があります。
          </dd>
        </dl>
      </div>
    </section>
    <!------------------------ 商品画像欄 ---------------------------->
    <section class="p-productImgContainer">
      <img src="images/item.png" class="p-productImgContainer__mainImg">
      <div class="p-productImgContainer__subBlock">
          <img src="images/item.png" class="p-productSubImg" v-for="n of 5" :key="n">
      </div>
    </section>
  </div>
  </div>
</template>

<script>


export default {
  data: function() {
    return {
    };
  },
  methods: {
  }
};
</script>
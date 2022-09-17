<template>
  <div class="p-userMainContainer">
    <h1 class="p-userMainContainer__title">
      商品出品
    </h1>
    <div class="p-userMainContainer__formWrap">
      <form action="#" class="p-form" method="post">
        <!------------------------ 出品画像欄 ---------------------------->
        <p class="p-sellDesc">出品画像(最大5枚)</p>
        <div class="p-uploadedImgContainer">
            <div class="p-uploadedImgContainer__block" v-for="n of 5" :key="n">
              <button class="p-imgDeleteButton c-button c-button--bgGray">+</button>
              <img src="/images/item.png" class="p-uploadedImg">
            </div>
        </div>
        <div class="p-uploadPointBlock">
          <label for="filename" class="p-uploadPointBlock__label">
            <button class="p-uploadImgButton c-button c-button--bgBlue">画像を選択</button>
            <input type="file" id="filename" class="p-uploadImgInput" name="product-img">
          </label>
          <p class="p-uploadPointBlock__text">ファイルをドラッグ＆ドロップ</p>
        </div>
        <h2 class="p-formDesc">商品詳細</h2>
        <!------------------------ 商品名欄 ---------------------------->
        <label class="p-formLabel">商品名</label>
        <span class="p-formAttention">※必須</span>
        <!-- 入力フォーム -->
        <input class="p-formInput" type="text" name="product-name">
        <!-- バリデーションエラーメッセージ表示箇所 -->
        <div class="p-formValidate">
          <ul>
            <li>※入力必須</li>
          </ul>
        </div>
        <!------------------------ 価格欄 ---------------------------->
        <label class="p-formLabel">価格(¥300 ~ 9,999,999)</label>
        <span class="p-formAttention">※必須</span>
        <!-- 入力フォーム -->
        <input type="number" class="p-formInput" name="price" placeholder="¥">
        <!-- バリデーションエラーメッセージ表示箇所 -->
        <div class="p-formValidate">
          <ul>
            <li>※入力必須</li>
          </ul>
        </div>
        <!------------------------ 賞味期限欄 ---------------------------->
        <label class="p-formLabel">賞味期限</label>
        <span class="p-formAttention">※必須</span>
        <!-- 入力フォーム -->
        <input type="text" id="datepicker"  class="p-formInput" name="best-before">
        <!-- バリデーションエラーメッセージ表示箇所 -->
        <div class="p-formValidate">
          <ul>
            <li>※入力必須</li>
          </ul>
        </div>
        <!------------------------ 出品ボタン ---------------------------->
        <input type="submit" class="p-formMainButton c-button c-button--bgBlue" value="出品する">
      </form>
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
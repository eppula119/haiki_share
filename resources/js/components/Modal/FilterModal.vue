<template>
  <div>
    <!------------------------ 都道府県の絞り込みモーダル欄 ---------------------------->
    <template v-if="filterType === 'prefecture'">
      <span class="c-contentHead">都道府県</span>
      <div class="c-pullDownCheckWrap">
        <div class="c-pullDownCheckWrap__block">
          <label for="c-pullDownCheckItem1" class="c-pullDownCheckItem">
            <input type="checkbox" id="c-pullDownCheckItem1" class="c-contentCheckbox">北海道
          </label>
          <span class="c-pullDownIcon">↓</span>
        </div>
        <label for="c-pullDownCheckItem2" class="c-pullDownCheckWrap__childItem">
          <input type="checkbox" id="c-pullDownCheckItem2" class="c-contentCheckbox">北海道
        </label>
        <div class="c-pullDownCheckWrap__block">
          <label for="c-pullDownCheckItem3" class="c-pullDownCheckItem">
            <input type="checkbox" id="c-pullDownCheckItem3" class="c-contentCheckbox">東北
          </label>
          <span class="c-pullDownIcon">↓</span>
        </div>
        <label for="c-pullDownCheckItem4" class="c-pullDownCheckWrap__childItem">
          <input type="checkbox" id="c-pullDownCheckItem4" class="c-contentCheckbox">青森県
        </label>
        <label for="c-pullDownCheckItem5" class="c-pullDownCheckWrap__childItem">
          <input type="checkbox" id="c-pullDownCheckItem5" class="c-contentCheckbox">岩手県
        </label>
      </div>
    </template>
    <!------------------------ 価格の絞り込みモーダル欄 ---------------------------->
    <template v-if="filterType === 'price'">
      <span class="c-contentHead">価格</span>
      <div class="c-contentWrap">
        <input type="text" class="c-contentWrap__input" value="">
        <span class="c-contentWrap__text">-</span>
        <input type="text" class="c-contentWrap__input" value="">
      </div>
    </template>
    <!------------------------ 賞味期限の絞り込みモーダル欄 ---------------------------->
    <template v-if="filterType === 'bestBefore'">
      <span class="c-contentHead">賞味期限</span>
      <div class="c-checkboxWrap">
        <label for="expired" class="c-checkboxWrap__label">
          <input type="checkbox" id="expired" class="c-checkboxInput">賞味期限切れ
        </label>
        <label for="bestBefore" class="c-checkboxWrap__label">
          <input type="checkbox" id="bestBefore" class="c-checkboxInput">賞味期限内
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
  },
  data: function() {
    return {
    };
  },
  methods: {
  }
};
</script>
@extends('app')

@section('title', '商品詳細')

@section('content')
  <!------------------------ モーダル欄 ---------------------------->
  <div class="c-modalOverlay"></div>
  <div class="c-modal">
    <div class="c-modal__content">
      <!------------------------ 購入確認画面モーダル欄 ---------------------------->
      <img src="#" class="c-contentImg">
      <span class="c-contentTitle">ガリガリ君リッチ！</span>
      <dt class="c-contentInfoTitle">賞味期限</dt>
      <dd class="c-contentInfoDetail">2023年10月31日まで</dd>
      <dt class="c-contentInfoTitle">価格</dt>
      <dd class="c-contentInfoDetail">¥1200円(税込)</dd>
      <dt class="c-contentInfoTitle">コンビニ名</dt>
      <dd class="c-contentInfoDetail">ローソン | 行田店</dd>
      <dt class="c-contentInfoTitle">住所</dt>
      <dd class="c-contentInfoDetail">東京都新宿区新宿x丁目x番地 hogehogeビル3F</dd>
      <p class="c-contentNote">
        上記の商品で間違いないか確認した上で「購入する」ボタンを押してください。
        ボタン押下後、決済が完了し登録メールアドレスへ購入確認メールが送信されます。
      </p>
    </div>
    <div class="c-modal__buttonWrap">
      <button class="c-modalDoButton c-button">キャンセル</button>
      <button class="c-modalDoButton c-button">購入する</button>
    </div>
  </div>
  <!------------------------ 商品詳細説明欄 ---------------------------->
  <section class="p-productDetailContainer">
    <p class="p-productDetailContainer__title">ガリガリ君リッチ！</p>
    <p class="p-productDetailContainer__bestBeforeText">賞味期限<span class="p-bestBeforeDate">2023年10月まで</span></p>
    <p class="p-productDetailContainer__price">
      価格
      <span class="p-priceBigText">¥1,200</span>
      税込
    </p>
    <button class="p-productDetailContainer__Button c-button">購入する</button>
    <button class="p-productDetailContainer__Button c-button">購入済み</button>
    <button class="p-productDetailContainer__Button c-button">購入をキャンセル</button>

    <dl class="p-productDetailContainer__info">
      <dt class="p-infoTitle">コンビニ名</dt>
      <dd class="p-infoDetail">ローソン | 行田店</dd>
      <dt class="p-infoTitle">住所</dt>
      <dd class="p-infoDetail">東京都新宿区新宿x丁目x番地 hogehogeビル3F</dd>
      <dt class="p-infoTitle">詳細</dt>
      <dd class="p-infoDetail">賞味期限がギリギリのため、ほぼ半額の値段で販売させていただきます。</br>
        関東地方以外への発送は2日以上かかる場合があります。
      </dd>
    </dl>
  </section>
  <!------------------------ 商品画像欄 ---------------------------->
  <section class="p-productImgContainer">
    <img src="#" class="p-productImgContainer__mainImg">
    @for ($i = 0; $i < 5; $i++)
      <img src="#"class="p-productImgContainer__subImg">
    @endfor
  </section>
@endsection
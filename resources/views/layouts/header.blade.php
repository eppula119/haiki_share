<header class="l-header">
  <div class="p-headerTitle">
    <img src="#" class="p-headerTitle__img">
  </div>
  <div class="p-headerMenuTrigger js-toggle__spMenu">
    <span class="p-headerMenuTrigger__border"></span>
    <span class="p-headerMenuTrigger__border"></span>
    <span class="p-headerMenuTrigger__border"></span>
  </div>
  <nav class="p-headerNav js-toggle__spMenuTarget">
      <a href="" class="p-headerNav__link">商品一覧</a>
      <a href="" class="p-headerNav__link">ログイン</a>
      <a href="" class="p-headerNav__link">新規登録</a>
      <a href="" class="p-headerNav__link">マイページ</a>
      <form action="#" class="p-headerNav__link p-logout" method="post">
        @csrf
        <input type="submit" value="ログアウト">
      </form>
  </nav>
</header>
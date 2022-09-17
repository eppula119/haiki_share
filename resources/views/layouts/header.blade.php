<header class="l-header">
  <div class="p-headerTitle">
    <img src="{{ asset('images/logo.png') }}" class="p-headerTitle__img">
  </div>
  <div class="p-headerMenuTrigger js-toggle__spMenu">
    <span class="p-headerMenuTrigger__border"></span>
    <span class="p-headerMenuTrigger__border"></span>
    <span class="p-headerMenuTrigger__border"></span>
  </div>
  <nav class="p-headerNav js-toggle__spMenuTarget">
      <div class="p-headerNav__title">
        <span class="p-navTitle">コンビニ廃棄食品シェアリングサービス</span>
        <img src="{{ asset('images/logo.png') }}" class="p-navImg">
      </div>
      <div class="p-headerNav__searchForm">
        <form class="p-navSearchForm" method="POST">
          <input type="text" class="p-navSearchForm__input" name="keyword">
          <label for="doSearch" class="p-navSearchForm__label">
            <input type="submit" id="doSearch" class="p-navSearchButton">
            <i class="fa-solid fa-magnifying-glass"></i>
          </label>
        </form>
      </div>
      <a href="" class="p-headerNav__link">商品一覧</a>
      <a href="" class="p-headerNav__link">ログイン</a>
      <a href="" class="p-headerNav__link">新規登録</a>
      <a href="" class="p-headerNav__link">マイページ</a>
      <form action="#" class="p-headerNav__link p-logout" method="post">
        @csrf
        <input type="submit" class="p-logout__button" value="ログアウト">
      </form>
  </nav>
</header>
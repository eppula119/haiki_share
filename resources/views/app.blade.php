<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>haiki share | @yield('title', 'Home')</title>
</head>
<body>
  <!----- ヘッダー ----->
  @include('layouts.header')
  <!----- メインコンテンツ ----->
  <main class="l-main">
    @yield('content')
  </main>
  <!----- フッター ----->
  @include('layouts.footer')
</body>
</html>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>haiki share | @yield('title', 'Home')</title>
  <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
  <!-- Styles -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <!----- ヘッダー ----->
  <!-- @include('layouts.header') -->
  <!----- メインコンテンツ ----->
  <main id="app" class="l-main">
    <!-- @yield('content') -->
  </main>
  <!----- フッター ----->
  @include('layouts.footer')
</body>
</html>
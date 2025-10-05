<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/santize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>
<body>
    <header class="header">

        <div class="header-nav">
            <a href="{{ route('items.index') }}">
                <div class="header-nav__log"><img src="{{ asset('images/logo.svg') }}" alt="ロゴ" class="logo">
                </div>
            </a>
          

        </div>

    </header>


    <main>
        @yield('content')
    </main>    
</body>
</html>
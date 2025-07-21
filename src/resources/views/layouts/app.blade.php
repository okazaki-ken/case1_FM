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

        <ul class="header-nav">
            <h2 class="header-nav__log">COACHTECH</h2>
            
            @if (Auth::check())
            <!--findメソッドのタブ付けを後で入力する-->

            <li class="header-nav__text">
                <form action="/logout" method="post">
                    @csrf
                    <button type="submit" class="header-nav__button">ログアウト</button>
                </form>
            </li>

            <li class="header-nav__text">
                <a href="/mypage" class="header-nav__link">マイページ</a>
            </li>
            @endif

        </ul>

    </header>


    <main>
        @yield('content')
    </main>    
</body>
</html>
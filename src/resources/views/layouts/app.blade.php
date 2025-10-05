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
            
            
            <div class="header-find">
                <form action="{{ route('items.index')}}" method="get">
                     <input type="text" name="q" value="{{ request('q') }}" placeholder="何をお探しですか？" class="find_bar">
                    <input type="submit" value="検索" class="find_button">
                </form>
            </div>
            
            @if (Auth::check())
            <div class="header-nav__button">
                <div class="header-nav__text">
                    <form action="/logout" method="post">
                        @csrf
                        <button type="submit">ログアウト</button>
                    </form>
                </div>

                <div class="header-nav__text">
                    <a href="/mypage" >マイページ</a>
                </div>
            
                <div class="header-nav__sell">
                    <a href="/sell">出品</a>
                </div>
            </div>

            @else
            <div class="header-nav__button">
                <div class="header-nav__text">
                    <form action="/login" method="post">
                        @csrf
                        <a href="/login" >ログイン</a>
                    </form>
                </div>

                <div class="header-nav__text">
                    <a href="/login" >マイページ</a>
                </div>
            
                <div class="header-nav__sell">
                    <a href="/login">出品</a>
                </div>
            </div>
   
            @endif

        </div>

    </header>


    <main>
        @yield('content')
    </main>    
</body>
</html>
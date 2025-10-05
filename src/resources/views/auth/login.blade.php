@extends('layouts.app2')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login">
    <h2 class="login-heading">ログイン</h2>

    <form method="POST" action="/login">
        @csrf

        <div class="form__group">
            <div class="form__group-title">メールアドレス</div>
            <div class="form__input--text">
                <input type="email" name="email" value="{{ old('email') }}">
            </div>
        </div>
        <div class="form__error">
          @error('email')
          {{ $message }}
          @enderror
        </div>

        <div class="form__group">
            <div class="form__group-title">パスワード</div>
            <div class="form__input--text">
                <input type="password" name="password">
            </div>
        </div>
        <div class="form__error">
          @error('password')
          {{ $message }}
          @enderror
        </div>

        <div class="form__button">
            <button type="submit" class="form__button-submit">ログインする</button>
        </div>

        <div class="login__link">
            <a href="/register">会員登録はこちら</a>
        </div>
    </form>
</div>
@endsection

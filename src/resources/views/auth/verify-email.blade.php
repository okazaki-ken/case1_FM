@extends('layouts.app2')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.blade.css') }}">
@endsection

@section('content')
<div class="verify-email">
    <div class="text">
    <p>登録していただいたメールアドレスに認証メールを送付しました。<br>
    メール内のリンクをクリックして認証を完了してください。</p>
    </div>

    {{-- MailHog を開くボタン --}}
    <p>
        <a href="http://localhost:8025/" target="_blank">
            <button type="button" class="button">認証はこちら</button>
        </a>
    </p>

    {{-- 認証メール再送フォーム --}}
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="retry">認証メールを再送する</button>
    </form>
</div>
@endsection

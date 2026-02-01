@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage">
    
    <header class="mypage-header">
        <div class="mypage-user">
            <div class="mypage-user__avatar">
                @if(optional($user->address)->profile_image)
                    <img
                        src="{{ asset('storage/' . $user->address->profile_image) }}"
                        alt="プロフィール画像"
                        class="mypage-user__avatar-img"
                    >
                @else
                    <div class="mypage-user__avatar-img mypage-user__avatar-img--empty"></div>
                @endif
            </div>

            <div class="mypage-user__meta">
                <h2 class="mypage-user__name">{{ $user->name }}</h2>

                @if($avgRating ?? false)
                    <div class="mypage-rating" aria-label="評価">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= $avgRating)
                                <span class="mypage-rating__star">
                                    <img src="{{ asset('images/icon/Star_filled.png') }}" alt="">
                                </span>
                            @else
                                <span class="mypage-rating__star">
                                    <img src="{{ asset('images/icon/Star_empty.png') }}" alt="">
                                </span>
                            @endif
                        @endfor
                    </div>
                @endif
            </div>
        </div>

        <div class="mypage-actions">
            <a href="/profile?redirect=/mypage" class="btn btn--outline-danger">プロフィールを編集</a>
        </div>
    </header>

    {{-- ===== Tabs ===== --}}
    <nav class="mypage-tabs" aria-label="マイページタブ">
        <a
            href="{{ route('mypage', ['type' => 'listed']) }}"
            class="mypage-tabs__link {{ $type === 'listed' ? 'is-active' : '' }}"
        >
            出品した商品
        </a>

        <a
            href="{{ route('mypage', ['type' => 'purchased']) }}"
            class="mypage-tabs__link {{ $type === 'purchased' ? 'is-active' : '' }}"
        >
            購入した商品
        </a>

        <a
            href="{{ route('mypage', ['type' => 'trading']) }}"
            class="mypage-tabs__link {{ $type === 'trading' ? 'is-active' : '' }}"
        >
            取引中の商品
            @if(($unreadCount ?? 0) > 0)
                <span class="badge mypage-tabs__badge" aria-label="未読 {{ $unreadCount }} 件">{{ $unreadCount }}</span>
            @endif
        </a>
    </nav>

    <hr class="mypage-divider">

    <section class="mypage-grid">
        @if($type === 'trading')
            @if($orders->isEmpty())
                <p class="mypage-empty">取引中の商品はありません</p>
            @else
                @foreach($orders as $order)
                    <a href="{{ route('orders.show', $order->id) }}" class="card">
                        <div class="card__thumb">
                            <img
                                src="{{ asset('storage/' . $order->item->item_image) }}"
                                alt="{{ $order->item->name }}"
                                class="card__img"
                            >

                            @if(($order->unread_count ?? 0) > 0)
                                <span class="badge badge--on-image" aria-label="未読 {{ $order->unread_count }} 件">
                                    {{ $order->unread_count }}
                                </span>
                            @endif
                        </div>

                        <div class="card__title">{{ $order->item->name }}</div>
                    </a>
                @endforeach
            @endif
        @else
            @if($items->isEmpty())
                <p class="mypage-empty">
                    {{ $type === 'purchased' ? '購入した商品はありません' : '出品中の商品はありません' }}
                </p>
            @else
                @foreach($items as $item)
                    <a href="{{ route('item.show', $item->id) }}" class="card">
                        <div class="card__thumb">
                            <img
                                src="{{ asset('storage/' . $item->item_image) }}"
                                alt="{{ $item->name }}"
                                class="card__img"
                            >
                        </div>

                        <div class="card__title">{{ $item->name }}</div>
                    </a>
                @endforeach
            @endif
        @endif
    </section>

</div>
@endsection

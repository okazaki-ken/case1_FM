@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')


<div class="profile">
    <div class="profile__left">
        <div class="profile__image">
            @if(optional($user->address)->profile_image)
                <img src="{{ asset('storage/' . $user->address->profile_image) }}" alt="プロフィール画像" class="profile-image">
            @else
                <div class="profile-image"></div>
            @endif
        </div>
        <h2 class="profile__name">{{ $user->name }}</h2>
    </div>

    <div class="profile__right">
        <a href="/profile?redirect=/mypage" class="profile__edit">プロフィールを編集</a>
    </div>
</div>

<div class="top">
    <div class="top__recommend">
        <a href="{{ route('mypage',['type'=>'selling']) }}" class="{{ $type === 'selling' ? 'active' : '' }}">
            出品した商品
        </a>
    </div>
    <div class="top__mylist">
        <a href=" {{ route('mypage',['type'=>'purchased']) }}" class="{{ $type === 'purchased' ? 'active' : '' }}">
            購入した商品
        </a>
    </div>
</div>

<hr class="divider">

<div class="item">
    @if($items->isEmpty())
        <div class="text">出品中の商品はありません</div>
    
    @else
        @foreach($items as $item)
        <a href="{{ route('item.show',$item->id) }}">    
            <div class="item__image">
                <img src="{{ 'storage/'.$item->item_image }}" alt="{{ $item->name }}">
                <div class="item__name">{{ $item->name }}</div>
            </div>
        </a>
        @endforeach

    @endif

</div>
@endsection
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')

<div class="item">
    <div class="item__image">
        <img src="{{ asset('storage/'.$item->item_image) }}" alt="{{ $item->name }}">
    </div>

    <div class="item__main">
        <h2>{{ $item->name }}</h2>
        <div class="item__main-category">{{ $item->category}}</div>
        <div class="item__main-pirce">￥{{number_format($item->price)}}(税込)</div>
        
         <div class="item__icon">
        <!--いいね機能-->
            @if($user)
                
                @if($user->goods->contains($item->id))
                    <form action="{{ route('goods.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="favorite-button favorited">★</button>
                    </form>
                
                @else
                    <form action="{{ route('goods.store', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="favorite-button">☆</button>
                    </form>
                @endif

                {{ $item->favorited_users_count }}

            @else
                <!-- 未ログインはログイン画面に遷移-->
                <a href="{{ route('login') }}" class="favorite-button">☆</a>
                {{ $item->favorited_users_count }}
            @endif

            <!-- コメントの数 -->
            <span class="comment-count">💬 {{ $item->comments_count }}</span>
         </div>

        <div class="item__main-buy">
            @if($item->order)
             <a href="javascript:void(0)" class="buy-button disabled">購入済み</a>
            @else
            <a href="/purchase/{{ $item->id }}" class="buy-button">購入手続きへ</a>
            @endif
        </div>

        <h2>商品説明</h2>
        <div class="item__main-explanation">
            {{ $item -> explanation}}
        </div>

        <h2>商品情報</h2>
        <div class="item__main-info">
            <div class="info-text">カテゴリー  
                @foreach (explode(',', $item->type) as $type)
                <span class="info-text__chack">{{ $type }}</span>
                @endforeach
            </div>
            <div class="info-text">商品の状態 <span class="info-text__condition">{{ $item->condition }}</span></div>
        </div>

        <!-- コメント -->
         <h2>コメント（ {{ $item->comments_count }}）</h2>
         <div class="comments">
            @forelse($item->comments as $comment)
                <div class="comment">
                    <div class="comment__user">
                        @if($comment->user->address->profile_image)
                            <img src="{{ asset('storage/' . $comment->user->address->profile_image) }}" alt="" class="comment__avatar">
                        @else
                            <div class="comment__avatar"></div>
                        @endif
                        <span class="comment__name">{{ $comment->user->name }}</span>
                    </div>
                    <div class="comment__body">
                        {{ $comment->body}}
                    </div>
                </div>
            @empty
                <p>まだコメントはありません</p>
            @endforelse
         </div>

        @if($user)
            <h2>商品へのコメント</h2>
            <form action="{{ route('item.comment',$item->id) }}" method="POST">
                @csrf
                <textarea name="body" rows="3" class="coment-textarea" >{{ old('body')}}</textarea>

                <br>
                <div class="error">
                    @error('body')
                    {{ $message }}
                    @enderror
                </div>
                <button type="submit" class="comment-button">コメントを送信する</button>
            </form>
        @endif
        
    </div>
</div>
@endsection
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="top">
    <div class="top__recommend">
        <a href="{{ route('items.index',['q'=>request('q')]) }}" class="{{ request('favorite') ? '' : 'active' }}">おすすめ</a>
    </div>
    <div class="top__mylist">
        <a href="{{ route('items.index',['favorite'=>1,'q'=>request('q')]) }}" class="{{ request('favorite') ? 'active' : '' }}">マイリスト</a>
    </div>
</div>

<hr class="divider">

<div class="item">
    
    @forelse($items as $item)
    <a href="{{ route('item.show',$item->id) }}">    
        <div class="item__image">
            <img src="{{ asset('storage/'.$item->item_image) }}" alt="{{ $item->name }}">
            <div class="item__name">{{ $item->name }}</div>
        

            <!--Sold Outの表示-->
            @if($item->order)
                <div class="item__soldout">SOLD OUT</div>
            @endif
        </div>
    </a>
        
    @empty
    <div class="item_nothing">一致する商品はありません</div>   

    @endforelse

</div>
@endsection
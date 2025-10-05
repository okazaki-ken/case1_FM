@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="top">
    <div class="top__recommend">おすすめ</div>
    <div class="top__mylist">マイリスト</div>
</div>

<hr class="divider">

<div class="item">
    @foreach($items as $item)    
    <div class="item__image">
        <img src="{{ $item->item_image }}" alt="{{ $item->name }}">
        <div class="item__name">{{ $item->name }}</div>
    </div>
    
    @endforeach

</div>

@endsection
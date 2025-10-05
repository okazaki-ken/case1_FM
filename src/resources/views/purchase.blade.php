@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="buy">
    <form action="/purchase/{{$item->id}}" method="post">
        @csrf
        <div class="input">

            <div class="item">

                <div class="item__img">                    
                    <img src="{{ asset('storage/'.$item->item_image) }}" alt="{{ $item->name }}">


                </div>
                <div class="item__info">
                    <div class="item__info-name">
                        {{$item-> name}}
                    </div>
                    <div class="item__info-price">
                        ￥{{number_format($item->price)}}
                    </div>
                </div>
            </div>

            <hr class="divider">

            <div class="pay">
                <div class="text">支払い方法</div>
                <div class="pay__payment">
                    <select name="payment" id="pay">
                        <option value="" disabled selected hidden>選択してください</option>
                        <option value="stor">コンビニ払い</option>
                        <option value="card">カード払い</option>
                    </select>                
                </div>
                <div class="error">
                    @error('payment')
                    {{ $message }}
                    @enderror
                </div>

                <hr class="divider">
            </div>

            <div class="address">
                <div class="address__text">
                    <div class="text">配送先</div>
                    <a href="{{ route('purchase.address.edit',[
                        'item_id' => $item->id ,
                        'redirect'=> '/purchase/'.$item->id
                        ]) }}" class="address__change">
                        変更する</a>
                </div>
                <div class="address__info">
                    @if(session()->has('temp_address'))
                        〒 {{ $temp_address['post'] ?? '' }}<br>
                        {{ $temp_address['address'] ?? '' }}{{ $temp_address['building'] ?? '' }}
                    @else
                       〒 {{ $user->address->post }}<br>
                        {{ $user->address->address }} {{ $user->address->building }}
                    @endif
                </div>  
                
                <hr class="divider">
            </div>
        </div>

        <div class="result">
            <div class="box">
                <div class="box__price">
                    <div class="box-text">商品代金</div>
                    <div class="box-price">￥{{number_format($item->price)}}</div>
                </div>
                <div class="box__pay">
                    <div class="box-text">支払方法</div>
                    <!-- JavaScriptを用いたプレビュー反映のコード -->
                    <div class="box-pay" id="pay-display"></div>
                </div>
            </div>

            <button type="submit"> 購入する</button>
        </div>

    </form>    
</div>

 <script>
    //支払方法のselectを即時反映させるJavaScript
    const paymentSelect = document.getElementById('pay');
    const paymentDisplay = document.getElementById('pay-display'); 

    paymentSelect.addEventListener('change', () => {  
    const selectedOptionText = paymentSelect.options[paymentSelect.selectedIndex].textContent;
    
    paymentDisplay.textContent = selectedOptionText;
    });
  </script>

@endsection
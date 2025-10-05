@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="profile">

    <div class="profile-heading">
        <h2>住所の変更</h2>
    </div>

    <form method="POST" action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}">
    @csrf
    @method('PATCH')
    <input type="hidden" name="redirect" value="{{ $redirect }}">

    <div class="form__group">
      <div class="form__group-title">郵便番号</div>
      <div class="form__input--text">
        <input type="text" name="post" value="{{ old('post', $temp_address->post ?? $user->address->post) }}">
      </div>

      <div class="form__error">
      @error('post')
          {{ $message }}
      @enderror
      </div>


    </div>

    <div class="form__group">
      <div class="form__group-title">住所</div>
      <div class="form__input--text">
        <input type="text" name="address" value="{{ old('address', $temp_address->address ?? $user->address->address) }}" >
      </div>

      <div class="form__error">
      @error('address')
          {{ $message }}
      @enderror
      </div>

    </div>

    <div class="form__group">
      <div class="form__group-title">建物名</div>
      <div class="form__input--text">
        <input type="text" name="building" value="{{ old('building', $temp_address->building ?? $user->address->building) }}">
      </div>
    </div>

    <div class="form__group">
      <button type="submit" class="form__button">更新する</button>
    </div>
  </form>

</div>
@endsection
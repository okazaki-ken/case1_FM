@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile">

    <div class="profile-heading">
        <h2>プロフィール設定</h2>
    </div>

    <form method="POST" action="/profile" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="redirect" value="{{ $redirect}}">

    <div class="form__group">

      <div class="form__image-area">
        @if(optional($user->address)->profile_image)
          <img src="{{ asset('storage/' . $user->address->profile_image) }}" 
          alt="プロフィール画像" 
          class="profile-image" 
          id="preview">
        @else
          <div class="profile-image" id="preview"></div>
        @endif

        <input type="file" name="profile_image" id="profile_image" class="form__input--file" hidden>
        <label for="profile_image" class="form__input--file-label">画像を選択する</label>
      </div>
    </div>

    <div class="form__group">
      <div class="form__group-title">ユーザー名</div>
      <div class="form__input--text">
        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" >
      </div>

      <div class="form__error">
      @error('name')
          {{ $message }}
      @enderror
      </div>

    </div>

    <div class="form__group">
      <div class="form__group-title">郵便番号</div>
      <div class="form__input--text">
        <input type="text" name="post" value="{{ old('post', Auth::user()->address?->post) }}">
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
        <input type="text" name="address" value="{{ old('address', Auth::user()->address?->address) }}" >
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
        <input type="text" name="building" value="{{ old('building', Auth::user()->address?->building) }}">
      </div>
    </div>

    <div class="form__group">
      <button type="submit" class="form__button">更新する</button>
    </div>
  </form>

</div>

<script>
(() => {
  const input = document.getElementById('profile_image');
  const preview = document.getElementById('preview');

  input.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file || !file.type.startsWith('image/')) return;

    const reader = new FileReader();
    reader.onload = (ev) => {
      if (preview.tagName === 'IMG') {
        preview.src = ev.target.result;
      } else {
        preview.innerHTML = '';
        const img = new Image();
        img.src = ev.target.result;
        img.className = 'profile-image';
        img.id = 'preview'; 
        preview.replaceWith(img);
      }
    };
    reader.readAsDataURL(file);
  });
})();
</script>

@endsection
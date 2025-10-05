@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell">

    <h2 class="top">商品の出品</h2>

    <form action="/sell" method="post" enctype="multipart/form-data">
        @csrf

        <div class="item">

            <div class="image">
                <div class="text">商品画像</div>
                <div class="image__photo">
                    <label for="image" class="image__photo-up">画像を選択する</label>
                        <img id="preview" class="preview-image" alt="プレビュー">
                        <input type="file" id="image" class="image" name="item_image" accept="image/*">
                                    
                </div>
            </div>

            <div class="detail">
                <h3>商品の詳細</h3>

                <hr class="divider">

                <div class="text">カテゴリー</div>
                <div class="detail__type-check">
                    <input type="checkbox" id="fashion" name="type[]" value="ファッション" hidden>
                    <label for="fashion" class="category-label">ファッション</label>

                    <input type="checkbox" id="electronics" name="type[]" value="家電" hidden>
                    <label for="electronics" class="category-label">家電</label>

                    <input type="checkbox" id="interior" name="type[]" value="インテリア" hidden>
                    <label for="interior" class="category-label">インテリア</label>

                    <input type="checkbox" id="ladies" name="type[]" value="レディース" hidden>
                    <label for="ladies" class="category-label">レディース</label>

                    <input type="checkbox" id="mens" name="type[]" value="メンズ" hidden>
                    <label for="mens" class="category-label">メンズ</label>

                    <input type="checkbox" id="cosmetics" name="type[]" value="コスメ" hidden>
                    <label for="cosmetics" class="category-label">コスメ</label>

                    <input type="checkbox" id="books" name="type[]" value="本" hidden>
                    <label for="books" class="category-label">本</label>

                    <input type="checkbox" id="games" name="type[]" value="ゲーム" hidden>
                    <label for="games" class="category-label">ゲーム</label>

                    <input type="checkbox" id="sports" name="type[]" value="スポーツ" hidden>
                    <label for="sports" class="category-label">スポーツ</label>

                    <input type="checkbox" id="kitchen" name="type[]" value="キッチン" hidden>
                    <label for="kitchen" class="category-label">キッチン</label>

                    <input type="checkbox" id="handmade" name="type[]" value="ハンドメイド" hidden>
                    <label for="handmade" class="category-label">ハンドメイド</label>

                    <input type="checkbox" id="accessory" name="type[]" value="アクセサリー" hidden>
                    <label for="accessory" class="category-label">アクセサリー</label>

                    <input type="checkbox" id="toys" name="type[]" value="おもちゃ" hidden>
                    <label for="toys" class="category-label">おもちゃ</label>

                    <input type="checkbox" id="baby" name="type[]" value="ベビー・キッズ" hidden>
                    <label for="baby" class="category-label">ベビー・キッズ</label>
                </div>
            </div>

            <div class="detail__condition">
                <div class="text">商品の状態</div>
                <div class="detail__condtion-select">
                    <select name="condition">
                        <option value="" disabled selected>選択してください</option>
                        <option value="良好">良好</option>
                        <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                        <option value="状態が悪い">状態が悪い</option>
                    </select>
                </div>
            </div>

            <div class="explanation">
                <h3>商品名と説明</h3>

                <hr class="divider">

                <div class="text">商品名</div>
                <div class="explanation__input">
                    <input type="text" name="name" value="{{ old('name')}}">
                </div>

                <div class="text">ブランド名</div>
                <div class="explanation__input">
                    <input type="text" name="category" value="{{ old('category')}}">
                </div>

                <div class="text">商品の説明</div>
                <div class="explanation__textarea">
                    <textarea name="explanation" cols="30" rows="10"></textarea>
                </div>

                <div class="text">販売価格</div>
                <div class="expanation__price">
                    <input type="text" name="price">
                </div>

                <div class="item__submit">
                    <button type="submit" class="item__submit-button">出品する</button>
                </div>


        </div>
    </form>
</div>

<script>
(() => {
  const input = document.getElementById('image');
  const preview = document.getElementById('preview'); 
  const label = document.querySelector('.image__photo label');

  input.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file || !file.type.startsWith('image/')) return;

    const reader = new FileReader();
    reader.onload = (ev) => {
      preview.src = ev.target.result; 
      label.style.display = 'none';
    };
    reader.readAsDataURL(file);
  });
})();
</script>

@endsection
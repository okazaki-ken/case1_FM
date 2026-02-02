@extends('layouts.app2')

@section('css')
<link rel="stylesheet" href="{{ asset('css/trading.css') }}">
@endsection

@section('content')
<div class="trade-layout">
    <aside class="trade-side">
        <div class="trade-side__title">その他の取引</div>

        <ul class="trade-side__list">
            @foreach($orders as $o)
                <li class="trade-side__item">
                    <a class="trade-side__link" href="{{ route('orders.show', $o->id) }}">
                        <span class="trade-side__name">{{ $o->item->name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </aside>

    <main class="trade-main">
        <header class="trade-header">
            <div class="trade-header__avatar">
                @if(optional($partner->address)->profile_image)
                    <img src="{{ asset('storage/'.$partner->address->profile_image) }}" alt="{{ $partner->name }}">
                @else
                    <div class="trade-header__avatar--empty"></div>
                @endif
            </div>

            <h1 class="trade-header__title">「 {{ $partner->name }}」さんとの取引画面</h1>

            @if(!$isSeller && !$hasRated)
                <button type="button" class="trade-header__complete" id="openRating">取引を完了する</button>
            @endif
        </header>

        <hr class="trade-divider">

        <section class="trade-item">
            <div class="trade-item__image">
                <img src="{{ asset('storage/'.$order->item->item_image) }}" alt="商品画像">
            </div>
            <div class="trade-item__info">
                <div class="trade-item__name">{{ $order->item->name }}</div>
                <div class="trade-item__price">{{ number_format($order->item->price) }}円</div>
            </div>
        </section>

        <hr class="trade-divider">

        <section class="chat">
            @foreach($messages as $message)
                @php $isMine = (int)$message->user_id === (int)auth()->id(); @endphp

                <div class="chat-row {{ $isMine ? 'chat-row--mine' : 'chat-row--other' }}" data-message-id="{{ $message->id }}">
                    <div class="chat-meta">
                        <div class="chat-meta__avatar">
                            @if(optional($message->user->address)->profile_image)
                                <img src="{{ asset('storage/'.$message->user->address->profile_image) }}" alt="">
                            @else
                                <div class="chat-meta__avatar--empty"></div>
                            @endif
                        </div>
                        <div class="chat-meta__name">{{ $message->user->name }}</div>
                    </div>

                    <div class="chat-bubble">
                        @if($message->image_path)
                            <img class="chat-bubble__image" src="{{ asset('storage/'.$message->image_path) }}" alt="message image">
                        @else
                            <div class="chat-bubble__text">{{ $message->body }}</div>
                        @endif
                    </div>

                    @if($isMine)
                        <div class="chat-actions">
                            @if(!$message->image_path)
                                <button
                                    type="button"
                                    class="chat-actions__btn chat-actions__btn--edit js-edit-btn"
                                    data-update-url="{{ route('orders.messages.update', [$order->id, $message->id]) }}"
                                    data-body="{{ e($message->body) }}"
                                >編集</button>
                            @endif

                            <form action="{{ route('orders.messages.destroy', [$order->id, $message->id]) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="chat-actions__btn chat-actions__btn--delete"
                                        onclick="return confirm('このメッセージを削除しますか？')">削除</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </section>

        <form id="chatForm"
              action="{{ route('orders.messages.store', $order->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="chat-form">
            @csrf
            <input type="hidden" name="_method" id="chatFormMethod" value="PUT" disabled>

            @if ($errors->any())
                <ul class="chat-form__errors" role="alert" aria-label="エラー">
                    @foreach ($errors->all() as $error)
                        <li class="chat-form__error">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="chat-form__row">
                <input
                    type="text"
                    name="body"
                    placeholder="取引メッセージを記入してください"
                    class="chat-form__input"
                    id="chatInput"
                    value="{{ old('body') }}"
                >

                <button type="button" class="chat-form__image-btn" id="imagePickBtn">画像を追加</button>

                <input type="file" name="image" id="imageInput" accept="image/*" class="chat-form__file">

                <button type="submit" class="chat-form__send" aria-label="送信">
                    <img src="{{ asset('images/icon/send.png') }}" alt="送信">
                </button>

                <button type="button" class="chat-form__cancel" id="editCancelBtn" style="display:none;">
                    キャンセル
                </button>
            </div>
        </form>
    </main>
</div>

<div class="modal" id="ratingModal" aria-hidden="true">
    <div class="modal__backdrop" id="closeRating"></div>

    <div class="modal__panel" role="dialog" aria-modal="true">
        <h3 class="modal__title">取引が完了しました。</h3>

        <hr class="modal__divider">

        <div class="modal__question">今回の取引相手はどうでしたか？</div>

        <form action="{{ route('order.rate', $order->id) }}" method="POST">
            @csrf
            <input type="hidden" name="score" id="ratingScore" value="0">

            <div class="modal__stars" id="stars">
                @for($i=1; $i<=5; $i++)
                    <button type="button" class="modal__star-btn" data-value="{{ $i }}">
                        <img src="{{ asset('images/icon/Star_empty.png') }}" alt="star">
                    </button>
                @endfor
            </div>

            <hr class="modal__divider">

            <div class="modal__actions">
                <button type="button" class="modal__cancel" id="cancelRating">キャンセル</button>
                <button type="submit" class="modal__submit" id="submitRating" disabled>送信する</button>
            </div>
        </form>
    </div>
</div>

<script>
(() => {
  const ratingModal = document.getElementById('ratingModal');
  const openRatingBtn = document.getElementById('openRating');
  const closeRatingBg = document.getElementById('closeRating');
  const cancelRatingBtn = document.getElementById('cancelRating');

  const starsWrap = document.getElementById('stars');
  const scoreInput = document.getElementById('ratingScore');
  const submitRatingBtn = document.getElementById('submitRating');

  const filledSrc = "{{ asset('images/icon/Star_filled.png') }}";
  const emptySrc  = "{{ asset('images/icon/Star_empty.png') }}";

  const openModal = () => {
    if (!ratingModal) return;
    ratingModal.setAttribute('aria-hidden', 'false');
    ratingModal.classList.add('is-open');
  };

  const closeModal = () => {
    if (!ratingModal) return;
    ratingModal.setAttribute('aria-hidden', 'true');
    ratingModal.classList.remove('is-open');
  };

  if (openRatingBtn) openRatingBtn.addEventListener('click', openModal);
  if (closeRatingBg) closeRatingBg.addEventListener('click', closeModal);
  if (cancelRatingBtn) cancelRatingBtn.addEventListener('click', closeModal);

  const paintStars = (score) => {
    if (!starsWrap) return;
    starsWrap.querySelectorAll('.modal__star-btn').forEach((btn) => {
      const v = Number(btn.dataset.value);
      const img = btn.querySelector('img');
      if (img) img.src = (v <= score) ? filledSrc : emptySrc;
    });
  };

  if (starsWrap) {
    starsWrap.addEventListener('click', (e) => {
      const btn = e.target.closest('.modal__star-btn');
      if (!btn) return;

      const score = Number(btn.dataset.value);
      if (scoreInput) scoreInput.value = String(score);
      paintStars(score);

      if (submitRatingBtn) submitRatingBtn.disabled = score < 1;
    });
  }

  const autoOpen = @json($autoOpenRating ?? false);
  if (autoOpen) openModal();

  const chatForm = document.getElementById('chatForm');
  const chatInput = document.getElementById('chatInput');
  const imagePickBtn = document.getElementById('imagePickBtn');
  const imageInput = document.getElementById('imageInput');
  const methodInput = document.getElementById('chatFormMethod');
  const cancelEditBtn = document.getElementById('editCancelBtn');

  if (!chatForm) return;

  const storeUrl = chatForm.getAttribute('action') || '';

  const enterEditMode = (updateUrl, body) => {
    if (!chatInput || !methodInput || !cancelEditBtn) return;

    chatForm.action = updateUrl;
    methodInput.disabled = false;
    chatInput.value = body || '';
    chatInput.focus();

    cancelEditBtn.style.display = '';
  };

  const exitEditMode = () => {
    if (!chatInput || !methodInput || !cancelEditBtn) return;

    chatForm.action = storeUrl;
    methodInput.disabled = true;
    chatInput.value = '';
    chatInput.focus();

    cancelEditBtn.style.display = 'none';
  };

  document.querySelectorAll('.js-edit-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      enterEditMode(btn.dataset.updateUrl, btn.dataset.body);
    });
  });

  if (cancelEditBtn) cancelEditBtn.addEventListener('click', exitEditMode);

  if (imagePickBtn && imageInput) {
    imagePickBtn.addEventListener('click', () => imageInput.click());

    imageInput.addEventListener('change', () => {
      if (!imageInput.files || imageInput.files.length === 0) return;

      const hasText = (chatInput?.value || '').trim().length > 0;
      if (hasText) {
        alert('テキストと画像は同時に送信できません。テキストを空にしてから画像を選択してください。');
        imageInput.value = '';
        return;
      }

      chatForm.submit();
    });
  }

  const draftKey = "order_message_draft_{{ $order->id }}";

  if (chatInput) {
    const oldVal = chatInput.value || '';
    if (!oldVal) {
      const saved = sessionStorage.getItem(draftKey);
      if (saved) chatInput.value = saved;
    }

    chatInput.addEventListener('input', () => {
      sessionStorage.setItem(draftKey, chatInput.value);
    });
  }

  chatForm.addEventListener('submit', () => {
    sessionStorage.removeItem(draftKey);
  });
})();
</script>
@endsection

<p>{{ $order->user_id === $rater->id ? '出品者' : '購入者' }}の{{ $rater->name }}さんが取引の評価を完了しました。</p>

<p>取引を完了するため、あなたも評価をお願いします。</p>

<p>
    取引画面：
    <a href="{{ route('orders.show', $order->id) }}">
        {{ route('orders.show', $order->id) }}
    </a>
</p>

<p>※このメールはテスト環境（MailHog）送信です。</p>

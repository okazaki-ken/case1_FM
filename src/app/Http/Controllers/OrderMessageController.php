<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderMessageRequest;
use App\Models\Order;
use App\Models\OrderMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderMessageController extends Controller
{
    public function store(OrderMessageRequest $request, Order $order)
    {
        $userId = (int) $request->user()->id;

        abort_unless($this->isParticipant($order, $userId), 403);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('order_messages', 'public');
        }

        OrderMessage::create([
            'order_id' => $order->id,
            'user_id' => $userId,
            'body' => $this->normalizeBody($request->input('body')),
            'image_path' => $imagePath,
        ]);

        return redirect()->route('orders.show', $order->id);
    }

    public function update(Request $request, Order $order, OrderMessage $message)
    {
        $userId = (int) $request->user()->id;

        abort_unless($this->isParticipant($order, $userId), 403);
        abort_unless((int) $message->order_id === (int) $order->id, 404);
        abort_unless((int) $message->user_id === $userId, 403);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $message->update([
            'body' => $this->normalizeBody($validated['body']),
        ]);

        return redirect()->route('orders.show', $order->id);
    }

    public function destroy(Request $request, Order $order, OrderMessage $message)
    {
        $userId = (int) $request->user()->id;

        abort_unless((int) $message->order_id === (int) $order->id, 404);
        abort_unless($this->isParticipant($order, $userId), 403);
        abort_unless((int) $message->user_id === $userId, 403);

        if (!empty($message->image_path)) {
            Storage::disk('public')->delete($message->image_path);
        }

        $message->delete();

        return redirect()->route('orders.show', $order->id);
    }

    private function isParticipant(Order $order, int $userId): bool
    {
        return (int) $order->user_id === (int) $userId
            || (int) $order->buyer_id === (int) $userId;
    }

    private function normalizeBody(?string $body): ?string
    {
        $trimmed = trim((string) $body);
        return $trimmed !== '' ? $trimmed : null;
    }
}

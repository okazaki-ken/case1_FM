<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TradeCompletePromptMail;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        $user = auth()->user();

        abort_unless($this->isParticipant($order, $user->id), 403);

        $order->load([
            'item',
            'user.address',
            'buyer.address',
            'messages.user.address',
        ]);

        $isSeller = (int) $order->user_id === (int) $user->id;
        $partner = $isSeller ? $order->buyer : $order->user;

        $otherOrders = Order::with('item')
            ->where('status', 'purchased')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('buyer_id', $user->id);
            })
            ->whereKeyNot($order->id)
            ->get();

        $hasRated = Rating::where('order_id', $order->id)
            ->where('rater_id', $user->id)
            ->exists();

        $buyerHasRated = Rating::where('order_id', $order->id)
            ->where('rater_id', $order->buyer_id)
            ->exists();

        $autoOpenRating = $isSeller && $buyerHasRated && !$hasRated;

        // 既読更新（相手が送った未読のみ）
        $order->messages()
            ->whereNull('read_at')
            ->where('user_id', '!=', $user->id)
            ->update(['read_at' => now()]);

        $messages = $order->messages()
            ->with('user.address')
            ->orderBy('created_at')
            ->get();

        return view('trading', [
            'orders' => $otherOrders,
            'order' => $order,
            'messages' => $messages,
            'user' => $user,
            'partner' => $partner,
            'isSeller' => $isSeller,
            'hasRated' => $hasRated,
            'autoOpenRating' => $autoOpenRating,
        ]);
    }

    public function rate(Request $request, Order $order)
    {
        $user = $request->user();

        abort_unless($this->isParticipant($order, $user->id), 403);

        $validated = $request->validate([
            'score' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $isSeller = (int) $order->user_id === (int) $user->id;
        $rateeId  = $isSeller ? $order->buyer_id : $order->user_id;

        $alreadyRated = Rating::where('order_id', $order->id)
            ->where('rater_id', $user->id)
            ->exists();

        // すでに評価済みなら何もしない（メールも送らない）
        if ($alreadyRated) {
            return redirect()->route('items.index');
        }

        Rating::create([
            'order_id' => $order->id,
            'rater_id' => $user->id,
            'ratee_id' => $rateeId,
            'score'    => (int) $validated['score'],
        ]);

        if ((int) $user->id === (int) $order->buyer_id) {
            $sellerHasRated = Rating::where('order_id', $order->id)
                ->where('rater_id', $order->user_id)
                ->exists();

            if (!$sellerHasRated) {
                $order->loadMissing('user'); // seller
                Mail::to($order->user->email)->send(new TradeCompletePromptMail($order, $user));
            }
        }
        
        $ratingCount = Rating::where('order_id', $order->id)->count();
        if ($ratingCount >= 2) {
            $order->update(['status' => 'completed']);
        }

        return redirect()->route('items.index');
    }


    private function isParticipant(Order $order, int $userId): bool
    {
        return (int) $order->user_id === (int) $userId
            || (int) $order->buyer_id === (int) $userId;
    }
}

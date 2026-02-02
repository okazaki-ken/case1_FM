<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Address;
use App\Models\Item;
use App\Models\Rating;
use App\Models\Order;
use App\Models\OrderMessage;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();
        $address = Address::firstOrNew(['user_id' => $user->id]);

        $avgScore = Rating::where('ratee_id', $user->id)->avg('score');
        $avgRating = $avgScore !== null ? (int) round($avgScore) : null;

        return view('profile', [
            'user'      => $user,
            'redirect'  => $request->query('redirect', '/'),
            'avgRating' => $avgRating,
        ]);
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $user->update([
            'name' => $request->name,
        ]);

        $address = Address::firstOrNew(['user_id' => $user->id]);

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = $user->id . '.' . $file->extension();

            $file->storeAs('profiles', $filename, 'public');
            $address->profile_image = 'profiles/' . $filename;
        }

        $address->fill([
            'post'     => $request->post,
            'address'  => $request->address,
            'building' => $request->building,
            'user_id'  => $user->id,
        ])->save();

        return redirect($request->input('redirect', '/'));
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $type = $request->query('type', 'listed');

        $avgScore = Rating::where('ratee_id', $user->id)->avg('score');
        $avgRating = $avgScore !== null ? (int) round($avgScore) : null;

        $unreadCount = OrderMessage::whereNull('read_at')
            ->where('user_id', '!=', $user->id)
            ->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('buyer_id', $user->id);
            })
            ->count();

        if ($type === 'listed') {
            $items = Item::where('user_id', $user->id)
                ->doesntHave('order')
                ->get();

            return view('mypage', compact(
                'items',
                'user',
                'type',
                'avgRating',
                'unreadCount'
            ));
        }

        if ($type === 'purchased') {
            $items = Item::whereIn('id', function ($q) use ($user) {
                $q->select('item_id')
                  ->from('orders')
                  ->where('buyer_id', $user->id)
                  ->where('status', 'completed');
            })->get();

            return view('mypage', compact(
                'items',
                'user',
                'type',
                'avgRating',
                'unreadCount'
            ));
        }

        if ($type === 'trading') {
            $orders = Order::query()
                ->with('item')
                ->where('status', 'purchased')
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('buyer_id', $user->id);
                })
                ->withCount([
                    'messages as unread_count' => function ($q) use ($user) {
                        $q->whereNull('read_at')
                          ->where('user_id', '!=', $user->id);
                    },
                ])
                ->withMax([
                    'messages as partner_last_message_at' => function ($q) use ($user) {
                        $q->where('user_id', '!=', $user->id);
                    },
                ], 'created_at')
                ->orderByRaw('partner_last_message_at IS NULL ASC')
                ->orderByDesc('partner_last_message_at')
                ->orderByDesc('updated_at')
                ->get();

            return view('mypage', compact(
                'orders',
                'user',
                'type',
                'avgRating',
                'unreadCount'
            ));
        }

        abort(404);
    }
}

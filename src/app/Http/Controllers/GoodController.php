<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;

class GoodController extends Controller
{
    // お気に入り登録
    public function store($item_id){
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        if (!$user->goods()->where('item_id', $item->id)->exists()) {
            $user->goods()->attach($item->id);
        }

        return redirect()->route('item.show', $item->id);
    }

    // お気に入り解除
     public function destroy($item_id){
        $user = Auth::user();
            if ($user->goods()->where('item_id', $item_id)->exists()) {
                $user->goods()->detach($item_id);
            }
        return redirect()->route('item.show', $item_id);
}
}

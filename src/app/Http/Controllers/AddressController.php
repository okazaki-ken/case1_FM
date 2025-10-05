<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Models\Item;

class AddressController extends Controller
{
    //購入時の住所変更ページを表示
    public function edit(Request $request,$item_id = null){
        
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $temp_address=session('temp_address');

        return view('address',compact('item','user','temp_address'),[
            'redirect' => $request->query('redirect', '/') 
        ]);        
     }

     // 購入時の住所を一時保存(sesseion)して、戻る
    public function update(AddressRequest $request,$item_id)
    {
        $date = $request->only(['post','address','building']);

        session(['temp_address' => $date]); 

        return redirect()->route('purchase.show',['item_id'=>$item_id])->with('success', '配送先を変更しました');
    }
}

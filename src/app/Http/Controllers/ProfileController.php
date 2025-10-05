<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Address;
use App\Models\Item;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AuthorRequest;

class ProfileController extends Controller
{
    //プロフィール設定画面の表示
     public function edit(Request $request){
        $user = Auth::user(); 
        $address = Address::firstOrNew(['user_id' => $user->id]);
        return view('profile', [
            'user'=>$user,
            'redirect'=>$request->query('redirect','/')
        ]);
    }
    
    //プロフィール  の更新
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        // usersテーブルの名前更新
        $user->name = $request->name;
        $user->save();

        $address = Address::firstOrNew(['user_id' => $user->id]);

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $extension = $file->extension();
            $filename = $user->id . '.' . $extension;

            // storage/app/public/profiles に保存
            $file->storeAs('profiles', $filename, 'public');
            $address->profile_image = 'profiles/' . $filename;
        }

        // Address テーブル更新または作成
        $address->post = $request->post;
        $address->address = $request->address;
        $address->building = $request->building;
        $address->user_id = $user->id;
        $address->save();

        $redirectTo = $request->input('redirect', '/');
        return redirect($redirectTo);
    }

    //プロフィール画面の表示
    public function show(Request $request){

        $user=Auth::user();

        $type = $request->query('type','selling');
        
        if ($type === 'purchased'){
            $items =Item::whereIn('id',function($query) use($user){
                $query->select('item_id')
                    ->from('orders')
                    ->where('user_id',$user->id);
            })->get();
        }else{
            $items =Item::Where('user_id',$user->id)->get();
        }

                return view('mypage',compact('items','user','type'));
    }
}

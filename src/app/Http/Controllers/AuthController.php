<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AuthorRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister(){
        return view('auth.register');
    }

    public function register(AuthorRequest $request){
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        Auth::login($user);

         $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice');
    }

    //メール認証
    protected function registered(Request $request, $user)
    {
        return redirect()->route('verification.notice');
    }

}

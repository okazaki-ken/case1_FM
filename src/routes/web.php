<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\GoodController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/register',[AuthController::class,'showRegister']);
Route::post('/register',[AuthController::class,'register']);

//商品一覧
Route::get('/', [ItemController::class, 'index'])->name('items.index');

//商品詳細
Route::get('/item/{item_id}',[ItemController::class,'show'])->name('item.show');
//お気に入り追加
Route::post('/goods/{item_id}', [GoodController::class, 'store'])->name('goods.store');
//お気に入り削除
Route::delete('/goods/{item_id}', [GoodController::class, 'destroy'])->name('goods.destroy');
//コメント
Route::post('/items/{item}/comment',[ItemController::class,'comment'])->name('item.comment');

Route::get('/purchase/success', [ItemController::class, 'success'])->name('purchase.success');

Route::middleware('auth')->group(function () {

    // メール認証通知画面
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // メール認証リンククリック
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/profile'); // 認証完了後 /profile に遷移
    })->middleware(['auth', 'signed'])->name('verification.verify');

    // メール再送
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証メールを再送しました');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    //プロフィール編集画面
    Route::get('/profile',[ProfileController::class,'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    //出品画面
    Route::get('/sell',[ItemController::class,'create']);
    Route::post('/sell',[ItemController::class,'store']);

    //購入画面
    Route::get('/purchase/{item_id}',[ItemController::class,'showPurchase'])->name('purchase.show');
    Route::post('/purchase/{item_id}', [ItemController::class, 'purchase'])->name('purchase');    
    Route::get('/thank', function(){ return view('thank'); })->name('thank');

    //購入画面からの住所修正
    Route::get('/purchase/address/{item_id}',[AddressController::class,'edit'])->name('purchase.address.edit');
    Route::patch('/purchase/address/{item_id}',[AddressController::class,'update'])->name('purchase.address.update');

    //マイページ
    Route::get('/mypage',[ProfileController::class,'show'])->name('mypage');


 });


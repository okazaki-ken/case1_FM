<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderMessageController;

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

// 商品一覧
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// 商品詳細
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

// お気に入り
Route::post('/goods/{item_id}', [GoodController::class, 'store'])->name('goods.store');
Route::delete('/goods/{item_id}', [GoodController::class, 'destroy'])->name('goods.destroy');

Route::post('/items/{item}/comment', [ItemController::class, 'comment'])->name('item.comment');

Route::get('/purchase/success', [ItemController::class, 'success'])->name('purchase.success');

Route::middleware('auth')->group(function () {

    Route::get('/email/verify', fn () => view('auth.verify-email'))
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/profile');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証メールを再送しました');
    })->middleware('throttle:6,1')->name('verification.send');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    Route::get('/purchase/{item_id}', [ItemController::class, 'showPurchase'])->name('purchase.show');
    Route::post('/purchase/{item_id}', [ItemController::class, 'purchase'])->name('purchase');
    Route::get('/thank', fn () => view('thank'))->name('thank');

    // 購入画面からの住所修正
    Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])->name('purchase.address.edit');
    Route::patch('/purchase/address/{item_id}', [AddressController::class, 'update'])->name('purchase.address.update');

    Route::get('/mypage', [ProfileController::class, 'show'])->name('mypage');

    Route::get('/order/{order}', [OrderController::class, 'show'])->name('orders.show');

    // 取引メッセージ（作成/更新/削除）
    Route::post('/order/{order}/messages', [OrderMessageController::class, 'store'])->name('orders.messages.store');
    Route::put('/order/{order}/messages/{message}', [OrderMessageController::class, 'update'])->name('orders.messages.update');
    Route::delete('/order/{order}/messages/{message}', [OrderMessageController::class, 'destroy'])->name('orders.messages.destroy');

    // 評価
    Route::post('/order/{order}/rate', [OrderController::class, 'rate'])->name('order.rate');
});

<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Order;
use App\Models\Comment;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PurchaseRequest;


class ItemController extends Controller
{
    //商品一覧画面
    public function index(Request $request){

        $user = auth()->user();
        $query = Item::query()->with('order');

        if($user){
            $query->where('user_id','!=',$user->id);
        }
        
        if($request->filled('q')){
            $keyword = $request->input('q');
            $query->where('name','like',"%{$keyword}%");
        }

        if($request->has('favorite')){
            if($user){
                $query->whereIn('id',$user->goods()->pluck('items.id'));
            } else {
                $items=collect();
                return view('index',compact('items'));
            }
            
        }

        $items = $query->get();

        return view('index',compact('items'));
    }

    //出品画面の表示
    public function create(){
        return view('sell');
    }

    //出品実施画面
    public function store(ExhibitionRequest $request){
        $formData = $request->except('_token','item_image');

        if (isset($request->type) && is_array($request->type)) {
            $formData['type'] = implode(',', $request->type);
        }else{
            $formData['type']=null;
        }

        $formData['item_image']=null;
        $formData['user_id'] = auth()->id();

        $item = Item::create($formData);
        $itemId=$item->id;

        $extension =$request->file('item_image')->extension();
        $filename =$itemId.'.'.$extension;
        $path = $request->file('item_image')->storeAs(
            'items',$filename,'public'
        );

        $item->item_image = $path;
        $item->save();


        return redirect('/');
    }



    //商品詳細画面の呼び出し
    public function show($item_id){

        $item = Item::with(['order','comments.user'])
                    ->withCount(['favoritedUsers','comments'])
                    ->findOrFail($item_id);
        $user = auth()->user();

        return view('item',compact('item','user'));
    }

    //商品詳細画面でのコメント
    public function comment(CommentRequest $request,Item $item){

        $item->comments()->create([
            'user_id'=>auth()->id(),
            'body'=>$request->body,
        ]);

        return redirect()->route('item.show',$item->id);
    }

    //商品購入画面（呼び出しと購入実施）
    public function showPurchase($item_id){

        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $temp_address=session('temp_address');

        return view('purchase',compact('item','user','temp_address'));
    }

    //購入時の処理
    public function purchase(PurchaseRequest $request, $item_id){

        $item = Item::findOrFail($item_id);

        // 住所取得（セッション優先、なければ会員情報）
        $address = session('temp_address') ?? [
            'post'     => Auth::user()->address->post,
            'address'  => Auth::user()->address->address,
            'building' => Auth::user()->address->building,
        ];

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentType = $request->payment;

        if (!in_array($paymentType, ['card','stor'])) {
            return back()->withErrors(['payment' => '支払い方法を選択してください']);
        }

        $paymentMethods = $paymentType === 'card' ? ['card'] : ['konbini'];

        // Stripe Checkout Session 作成
        $checkoutSession = StripeSession::create([
            'payment_method_types' => $paymentMethods,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'unit_amount' => $item->price,
                    'product_data' => [
                        'name' => $item->name,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'metadata' => [
                'item_id' => $item->id,
                'user_id' => Auth::id(),
                'shipping_post' => $address['post'],
                'shipping_address' => $address['address'],
                'shipping_building' => $address['building'],
                'payment_method' => $paymentType,
            ],
            'success_url' => route('purchase.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url('/purchase/'.$item->id),
        ]);

        // 一時住所をクリア
        session()->forget('temp_address');

        return redirect($checkoutSession->url);
}


    // サンキューページ表示
    public function success(Request $request){

    $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect('/')->withErrors(['error' => 'セッション情報がありません']);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::retrieve($sessionId);

        // metadata から必要情報を取得
        $metadata = $session->metadata;

        // すでに Order が作られていれば二重作成を防止
        $existingOrder = Order::where('stripe_id', $session->payment_intent)->first();
        if (!$existingOrder) {
            Order::create([
                'item_id' => $metadata->item_id,
                'user_id' => $metadata->user_id,
                'shipping_post' => $metadata->shipping_post,
                'shipping_address' => $metadata->shipping_address,
                'shipping_building' => $metadata->shipping_building,
                'payment_method' => $metadata->payment_method,
                'stripe_id' => $session->payment_intent,
            ]);
        }

    return view('thank');
    }



}

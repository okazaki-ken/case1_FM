<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\Rating;
use App\Models\User;
use App\Models\OrderMessage;

class Order extends Model
{
    use HasFactory;

    protected $guarded = array('id');  
    protected $fillable =[
        'user_id',
        'item_id',
        'shipping_post',
        'shipping_address',
        'shipping_building',
        'payment_method',
        'buyer_id',
        'status',
    ];

    /** 対象商品 */
    public function item(){
        return $this->belongsTo(Item::class);
    }

    /** 出品者 */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    /** 購入者 */
    public function buyer(){
        return $this->belongsTo(User::class, 'buyer_id');
    }    

    /** 取引中メッセージ */
    public function messages(){
        return $this->hasMany(OrderMessage::class);
    }

    /** 評価 */
    public function ratings(){
        return $this->hasMany(Rating::class);
    }


}

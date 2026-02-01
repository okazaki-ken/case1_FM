<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Address;
use App\Models\Order;
use App\Models\Rating;
use App\Models\OrderMessage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function address(){
        return $this -> hasOne(Address::class);
    }

    public function goods(){
        return $this->belongsToMany(Item::class, 'goods', 'user_id', 'item_id')->withTimestamps();
    }

    public function commetns(){
        return $this->hasMany(Comment::class);
    }

    /** 出品した商品 */
    public function items(){
        return $this->hasMany(Item::class);
    }

    /** 購入した取引 */
    public function ordersAsBuyer(){
        return $this->hasMany(Order::class, 'buyer_id');
    }

    /** 出品した取引 */
    public function ordersAsSeller(){
        return $this->hasMany(Order::class, 'seller_id');
    }

    /** 取引中メッセージ */
    public function orderMessages(){
        return $this->hasMany(OrderMessage::class);
    }

    /** 自分が「評価した」 */
    public function ratingsGiven(){
        return $this->hasMany(Rating::class, 'rater_id');
    }

    /** 自分が「評価された」 */
    public function ratingsReceived(){
        return $this->hasMany(Rating::class, 'ratee_id');
    }

  

}

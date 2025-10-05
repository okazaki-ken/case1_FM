<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    public function address(){
        return $this -> hasOne(Item::class);
    }
}

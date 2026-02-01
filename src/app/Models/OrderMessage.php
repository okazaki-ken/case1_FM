<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMessage extends Model
{
    use HasFactory;

    protected $guarded = array('id');  
     protected $fillable =[
        'order_id',
        'user_id',
        'body',
        'image_path',
    ];

    /** 対象の取引 */
    public function order(){
        return $this->belongsTo(Order::class);
    }

    /** 発言者 */
    public function user(){
        return $this->belongsTo(User::class);
    }
}

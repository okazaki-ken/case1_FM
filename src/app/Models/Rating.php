<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $guarded = array('id');  
    protected $fillable =[
        'order_id',
        'rater_id',
        'ratee_id',
        'score',
    ];

    /** 対象取引 */
    public function order(){
        return $this->belongsTo(Order::class);
    }

    /** 評価した人 */
    public function rater(){
        return $this->belongsTo(User::class, 'rater_id');
    }

    /** 評価された人 */
    public function ratee(){
        return $this->belongsTo(User::class, 'ratee_id');
    }
}

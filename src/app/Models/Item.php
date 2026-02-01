<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = array('id');  
    protected $fillable =[
        'user_id',
        'item_image',
        'name',
        'price',
        'category',
        'condition',
        'type',
        'explanation'
    ];

    public function favoritedUsers(){
        return $this->belongsToMany(User::class, 'goods', 'item_id', 'user_id')->withTimestamps();
    }

    public function order(){
    
        return $this->hasOne(Order::class); 
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
   
}

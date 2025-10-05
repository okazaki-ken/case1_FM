<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Address extends Model
{
    use HasFactory;

    protected $guarded = array('id');  
    protected $fillable =[
        'user_id',
        'post',
        'address',
        'building',
        'profile_image',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

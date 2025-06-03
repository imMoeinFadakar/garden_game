<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartUser extends Model
{
    protected $fillable = 
    [
        "cart_number" ,
            "user_id",
            'expire_date',
            'cvv' 
    ];

    protected $hidden = [
        'user_id'
    ];


}

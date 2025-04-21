<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class temporaryReward extends Model
{
    protected $fillable = [
        "user_id",
        "product_id",
        "amount",
        "ex_time"
    ];

    public static function addNewTemporaryReward($request)
    {
       return  self::query()->create($request);
    }



}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketHistory extends Model
{
    
    protected $fillable=
    [
        "user_id",
        "product_amount",
        "token_amount"
    ];

    public function addNewMarketHistory( $request){
    return $this->create( $request);
    }

}

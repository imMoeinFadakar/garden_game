<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketHistory extends Model
{
    
    protected $fillable=
    [
        "user_id",
        "product_amount",
        "token_amount",
        "farm_id"
    ];

    /**
     * Get the farm that owns the MarketHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function farm()
    {
        return $this->belongsTo(Farms::class, 'farm_id', 'id');
    }
    /**
     * Get the user that owns the MarketHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }



    public function addNewMarketHistory( $request){
    return $this->create( $request);
    }

}

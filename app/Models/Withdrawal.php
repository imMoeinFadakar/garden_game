<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        "amount",
        "user_id",
        "wallet_id"
    ];


    protected $hidden = ['user_id'];

    public function addNewWithdrawal( $request){
    return $this->create( $request);
    }




    /**
     * Get the wallet that owns the Withdrawal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }



}

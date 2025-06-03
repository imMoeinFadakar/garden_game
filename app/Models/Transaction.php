<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        "user_id",
        "status",
        "type",
        "amount",
        'transaction_number'
    ];

    /**
     * Get the user that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function updateTransaction($request){
    $this->update($request->validated());
    return $this;
    }

    public function addNewTransaction( $request){
    return $this->create( $request);
    }

}

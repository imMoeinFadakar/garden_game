<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    //
    protected $fillable = [
        "gem_amount",
        "from_wallet",
        "to_wallet"
    ];
    /**
     * Get the user that owns the Transfer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function from_user()
    {
        return $this->belongsTo(User::class, 'from_user','id');
    }


    /**
     * Get the to_user that owns the Transfer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function to_user()
    {
        return $this->belongsTo(User::class, 'to_user', 'id');
    }

    public function addNewTransfer($request)
    {
        return $this->query()->create($request);
    }

}

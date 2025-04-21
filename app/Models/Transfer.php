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
    public function from_wallet()
    {
        return $this->belongsTo(User::class, 'from_wallet','id');
    }


    /**
     * Get the to_user that owns the Transfer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function to_wallet()
    {
        return $this->belongsTo( User::class, 'to_wallet', 'id');
    }

    public function addNewTransfer($request)
    {
        return $this->query()->create($request);
    }

}

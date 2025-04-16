<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cryptocurrency extends Model
{
    protected $table = "cryptocurrencies";

    /**
     * Get the user that owns the cryptocurrency
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }



}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReffralManagment extends Model
{
    //


    /**
     * Get the user that owns the UserReffralManagment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

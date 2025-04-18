<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReferral extends Model
{

    protected  $fillable = [
        "invading_user",
        "invented_user"
    ];
    /**
     * Get the reffred that owns the UserReffral
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reffred()
    {
        return $this->belongsTo(User::class, 'invented_user', 'id');
    }

    /**
     * Get the reffring that owns the UserReffral
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reffring()
    {
        return $this->belongsTo(User::class, 'invating_user', 'id');
    }

    public function addNewUserReferral($request)
    {
        return $this->query()->create($request);
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFarms extends Model
{
    //
    protected $fillable =[
        "user_id",
        "farm_id",
        "farm_power"
    ];

    /**
     * Get the user that owns the UserFarms
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the farm that owns the UserFarms
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function farm()
    {
        return $this->belongsTo(Farms::class, 'farm_id', 'id');
    }

    public function addNewUserFarms($request): UserFarms
    {
        return $this->query()->create($request);
    }

    



    public function updateUserFarms($request)
    {
        $this->update($request->validated());
        return $this;
    }

    public function deleteUserFarms()
    {
        return $this->delete();
    }


}

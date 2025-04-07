<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReferralReward extends Model
{
    //
    protected $fillable = [
        "reward_for_generation_one",
        "reward_for_generation_two",
        "reward_for_generation_three",
        "reward_for_generation_four",
        "farm_id"
    ];


    /**
     * Get the farm that owns the UserReffralReward
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function farm()
    {
        return $this->belongsTo(Farms::class, 'farm_id');
    }


    public function addNewUserReffralReward( $request){
    return $this->create( $request->validated());
    }


    public function updateUserReffralReward($request){
    $this->update($request->validated());
    return $this;
    }

    public function deleteUserReffralReward(){
    return $this->delete();
    }

}

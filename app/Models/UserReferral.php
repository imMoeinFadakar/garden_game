<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserReferral extends Model
{

    protected  $fillable = [
        "invading_user",
        "invented_user",
        'gender'
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

    // user referral 

    public static function userReferralNum()
    {
        return self::query()->where("invading_user",auth()->id())->count();
    }
    public static function findUserReferral($userId)
    {   
        if($userId){
            return self::query()->where("invented_user",$userId) // add auth
            ->first()->invading_user;
        }
       
        return null;


    }

}

<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\UserStatusResource;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class UserStatusController extends BaseUserController
{   
    /**
     * activate user warehouse
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function activeWarehouse(Request $request,User $user)
    {   
        $user = auth()->user();
        if($user->warehouse_status === 'active')
            return $this->api(null,__METHOD__,'Your warehouse is already active');


        $userAmount = $this->hasUserEnoughGem(5); // ensure user have enough gem
        if(! $userAmount)
            return $this->api(null,__METHOD__,'dont have enough gem');

        $minusUserGem = $this->minusUserGem(5); // minus user gem
        if($minusUserGem){

            $user =  $this->activeUserOptions('warehouse_status'); // active user warehouse
            $user->id = null;
            return $this->api(new UserStatusResource($user->toArray()),__METHOD__);
        }

       return $this->errorResponse("operation failed"); 
    }



    /**
     * active selected option for user (warehouse , market)
     * @param mixed $option
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function activeUserOptions($option)
    {
        $user = auth()->user();
        if($user->$option === "inactive")
            $user->$option = "active";

        $user->save();
        return $user;
    }



    /**
     * active market
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function activeMarket()
    {   
        $user = auth()->user();
        if($user->market_status === 'active')
            return $this->api(null,__METHOD__,'Your market is already active');


        $userAmount = $this->hasUserEnoughGem(20); // ensure user have enough gem
        if(! $userAmount)
            return $this->api(null,__METHOD__,'dont have enough gem');


        $minusUserGem = $this->minusUserGem(20); // minus user gem
        if($minusUserGem){

            $user =  $this->activeUserOptions('market_status'); // active user market
            $user->id = null;
            return $this->api(new UserStatusResource($user->toArray()),__METHOD__);
        }

        return $this->errorResponse("operation failed"); // error

    }
  
    /**
     * @param int $gemPrice
     * @return bool
     */
    public function minusUserGem(int $gemPrice): bool
    {
        $user = auth()->user();
        $user->gem_amount -= $gemPrice;
       return  $user->save();

    }

    /**
     * Summary of hasUserEnoughGem
     * @param mixed $gemPrice
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return bool
     */
    public function hasUserEnoughGem(int $gemPrice)
    {
        $user = auth()->user();
        if($user->gem_amount < $gemPrice)
           return false;

    return true;
    }


}

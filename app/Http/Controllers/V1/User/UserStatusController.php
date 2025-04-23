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
        $this->hasUserEnoughGem(5); // ensure that user have enough gem
        $minusUserGem = $this->minusUserGem(5); // minus user gem
        if($minusUserGem){

            $user =  $this->activeUserOptions('warehouse_status'); // active user warehouse
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
        $this->hasUserEnoughGem(20); // ensure user have enough gem
        $minusUserGem = $this->minusUserGem(20); // minus user gem
        if($minusUserGem){

            $user =  $this->activeUserOptions('market_status'); // active user market
            return $this->api(new UserStatusResource($user->toArray()),__METHOD__);
        }

        throw new HttpResponseException(response()->json([
            "success" => false,
            "code" => 422,
            "message" => "operation failed",
        ]));
    }



    ///////////
  

    public function minusUserGem($gemPrice)
    {
        $user = auth()->user();
        $user->gem_amount -= $gemPrice;
       return  $user->save();

    }


    public function hasUserEnoughGem($gemPrice)
    {
        $user = auth()->user();
        if($user->gem_amount < $gemPrice)
            throw new HttpResponseException(response()->json([
                "success" => false,
                "code" => 422,
                "message" => "dont have enough gem",
            ]));

    return true;
    }


}

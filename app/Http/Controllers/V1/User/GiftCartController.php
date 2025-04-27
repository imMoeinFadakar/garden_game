<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\Giftcart\UseGiftCartRequest;
use App\Http\Resources\V1\User\GiftcartResource;
use App\Models\Giftcart;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;

class GiftCartController extends BaseUserController
{   

    /**
     *  use gitftcart/post
     * @param \App\Http\Requests\V1\User\Giftcart\UseGiftCartRequest $request
     * @param \App\Models\Giftcart $giftcart
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function useGiftCart(UseGiftCartRequest $request,Giftcart $giftcart)
    {
        $giftcart = $this->findGiftcart($request->code); // find gift cart

        $user = auth()->user(); // auth user
        if($user){

           
            $deletedGiftcart = $this->deleteGiftcart($request->code); // Delete giftcart

            if($deletedGiftcart){

               
                $this->addNewAmount($user,$giftcart->value); // add new amount to user wallet
                return $this->api(new GiftcartResource($user),__METHOD__);

            }

            return $this->errorResponse("gift cart operation failed",400);


        }else{

            return $this->errorResponse("login first",400);


        }



    }

    /**
     * Delete used giftcart
     * @param mixed $code
     * @return bool|null
     */
    public function deleteGiftcart($code): ?bool
    {
        return Giftcart::where("code",$code)->delete();
    }

    /**
     * add new amount to user wallet
     * @param  $user
     * @param int $amount
     * @return bool
     */
    public function addNewAmount( $user,int $amount): bool
    {
        $user->token_amount += $amount;
      return   $user->save();
    }



    /**
     * find giftcart
     * @param mixed $code
     * @return Giftcart|null
     */
    public function findGiftcart($code)
    {
        return Giftcart::query()
        ->where("code",$code)
        ->first()?:null;
    }

}

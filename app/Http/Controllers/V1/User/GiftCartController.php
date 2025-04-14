<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\Giftcart\UseGiftCartRequest;
use App\Http\Resources\V1\User\GiftcartResource;
use App\Models\Giftcart;
use App\Models\Wallet;
use Illuminate\Http\Request;

class GiftCartController extends BaseUserController
{
    public function useGiftCart(UseGiftCartRequest $request,Giftcart $giftcart)
    {
        $giftcart = $this->findGiftcart($request->code);
        if(! $giftcart)
            return $this->errorResponse(400,"gift cart not find");

        $userWallet = $this->findUserWallet();

        $deletedGiftcart = $this->deleteGiftcart($request->code);
        if($deletedGiftcart){

            $userWallet->gem_amount += $giftcart->value;
            $userWallet->save();

            return $this->api(new GiftcartResource($userWallet->toArray()),__METHOD__);

        }

         return $this->errorResponse(400,"gift cart operation failed");

    }


    public function deleteGiftcart($code)
    {
        return Giftcart::where("code",$code)->delete();
    }

    public function findUserWallet()
    {
        return Wallet::query()
        ->where("user_id",1)
        ->first();
    }

    public function findGiftcart($code)
    {
        return Giftcart::query()
        ->where("code",$code)
        ->first()?:null;
    }

}

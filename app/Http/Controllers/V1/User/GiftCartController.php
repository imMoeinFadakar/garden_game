<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\Giftcart\UseGiftCartRequest;
use App\Http\Resources\V1\User\GiftcartResource;
use App\Models\Giftcart;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class GiftCartController extends BaseUserController
{
    public function useGiftCart(UseGiftCartRequest $request,Giftcart $giftcart)
    {
        $giftcart = $this->findGiftcart($request->code);

        if(! $giftcart)
            return $this->errorResponse("gift cart not find",401);

        $user = $this->findUser();
        if($user){

           
            $deletedGiftcart = $this->deleteGiftcart($request->code);
            if($deletedGiftcart){

                $user->token_amount += $giftcart->value;
                $user->save();
                

                return $this->api(new GiftcartResource($user->toArray()),__METHOD__);

            }

            return $this->errorResponse("gift cart operation failed",400);


        }else{

            return $this->errorResponse("wallet is not found!",400);


        }



    }


    public function deleteGiftcart($code)
    {
        return Giftcart::where("code",$code)->delete();
    }

    public function findUser()
    {
        return User::find(1);
    }

    public function findGiftcart($code)
    {
        return Giftcart::query()
        ->where("code",$code)
        ->first()?:null;
    }

}

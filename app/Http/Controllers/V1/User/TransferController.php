<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\Giftcart\TransferRequest;
use App\Http\Resources\V1\User\TransferResource;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class TransferController extends BaseUserController
{   
    /**
     * transfer from user to another user
     * @param \App\Http\Requests\V1\User\Transfer\TransferRequest $request
     * @param \App\Models\Transfer $transfer
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(\App\Http\Requests\V1\User\Transfer\TransferRequest $request,Transfer $transfer)
    {
        $validatedRequest = $request->validated();
        $user = auth()->user();
     

        // has user enough token
        $hasUsereniughToken = $this->hasUserEnoughToken($user->token_amount,$validatedRequest["token_amount"]);
        if(! $hasUsereniughToken)
            return $this->api(null,__METHOD__,'you dont have enough token');
        
        $reciverUser = $this->reciverWallet($validatedRequest['user_address']);
        
        $minusToken = $this->minusUserGem($validatedRequest["token_amount"],$user);

    
        if(! $minusToken)
            return $this->errorResponse("Receiver user not found!");

        $addGemToWallet = $this->plusUserReceiverGem($reciverUser,$validatedRequest["token_amount"]);

        if(! $addGemToWallet){
            $this->plusUserReceiverGem($user,$validatedRequest["token_amount"]);
            return $this->errorResponse("operation failed! gem has been returned to your wallet");
        }

        $validatedRequest["from_user"] = auth()->id(); //auth::id()
        $validatedRequest["to_user"] = $reciverUser->id;
        $transfer = $transfer->addNewTransfer($validatedRequest);

        return $this->api(new TransferResource($transfer->toArray()),__METHOD__);

    }

    public function reciverWallet($referalCode)
    {
        return User::query()
        ->where('referral_code',$referalCode)
        ->first();
    }




    public function plusUserReceiverGem($receiverWallet,int $amount)
    {
        $receiverWallet->token_amount += $amount;
        return $receiverWallet->save();
    }
    public function minusUserGem($amount,$user)
    {   
       
        $user->token_amount -= $amount;
        return $user->save();
    }

    public function findUserWallet()
    {
        return auth()->user();
    }

    public function hasUserEnoughToken(int $userGem,int $amount)
    {
        if($userGem < $amount)
            return false;

        return true;
    }

}

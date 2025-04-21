<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\Giftcart\TransferRequest;
use App\Http\Resources\V1\User\TransferResource;
use App\Models\Transfer;
use App\Models\Wallet;
use Illuminate\Http\Request;

class TransferController extends BaseUserController
{
    public function store(\App\Http\Requests\V1\User\Transfer\TransferRequest $request,Transfer $transfer)
    {
        $validatedRequest = $request->validated();
        $userwallet = $this->findUserWallet();

        // has user enough token
        $request = $this->hasUserEnoughToken($userwallet->gem_amount,$validatedRequest["token_amount"]);
        $reciverWallet = $this->findUserWallet();
        $minusToken = $this->minusUserGem($validatedRequest["token_amount"],$userwallet);

        if(! $minusToken)
            return $this->errorResponse(400,"Receiver user not found!");

        $addGemToWallet = $this->plusUserReceiverGem($reciverWallet,$validatedRequest["token_amount"]);

        if(! $addGemToWallet){
            $this->plusUserReceiverGem($userwallet,$validatedRequest["token_amount"]);
            return $this->errorResponse(400,"operation failed! gem has been returned to your wallet");
        }

        $validatedRequest["from_wallet"] = 1; //auth::id()
        $validatedRequest["to_wallet"] = $reciverWallet->id;
        $transfer = $transfer->addNewTransfer($validatedRequest);
        return $this->api(new TransferResource($transfer->toArray()),__METHOD__);

    }

    public function plusUserReceiverGem($receiverWallet,int $amount)
    {
        $receiverWallet->gem_amount += $amount;
        return $receiverWallet->save();
    }
    public function minusUserGem($amount,$wallet)
    {
        $wallet->gem_amount -= $amount;
        return $wallet->save();
    }

    public function findUserWallet()
    {
        return auth()->user();
    }

    public function hasUserEnoughToken(int $userGem,int $amount)
    {
        if($userGem < $amount)
            return $this->errorResponse(422,"dont have enough gem");

        return true;
    }

}

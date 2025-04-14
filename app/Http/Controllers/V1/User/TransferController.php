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
        $userwallet = $this->findUserWallet("user_id",1);

        // has user enough token
        $request = $this->hasUserEnoughToken($userwallet->gem_amount,$validatedRequest["gem_amount"]);
        $reciverWallet = $this->findUserWallet("referral_code",$validatedRequest["user_address"]);
        $minusGem = $this->minusUserGem($validatedRequest["gem_amount"],$userwallet);

        if(! $minusGem)
            return $this->errorResponse(400,"Receiver user not found!");

        $addGemToWallet = $this->plusUserReceiverGem($reciverWallet,$validatedRequest["gem_amount"]);

        if(! $addGemToWallet){
            $this->plusUserReceiverGem($userwallet,$validatedRequest["gem_amount"]);
            return $this->errorResponse(400,"operation failed! gem has been returned to your wallet");
        }

        $validatedRequest["from_wallet"] = 1;
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

    public function findUserWallet($param, $value)
    {
        return Wallet::query()->where("$param",$value)->first();
    }

    public function hasUserEnoughToken(int $userGem,int $amount)
    {
        if($userGem < $amount)
            return $this->errorResponse(422,"dont have enough gem");

        return true;
    }

}

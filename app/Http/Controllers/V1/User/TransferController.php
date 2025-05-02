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


    public function receiveTransfer(Request $request)
    {
        $transfer = Transfer::query()
        ->where('to_user',auth()->id())
        ->when(isset($request->id),fn($query)=>$query->where('id',$request->id))
        ->when(isset($request->token_amount),fn($query)=>$query->where('token_amount',$request->token_amount))
        ->get(['id','token_amount','created_at'])
        ->each(function($transfer){

            $transfer->setAttribute('type','receive');

        });

        return $this->api(TransferResource::collection($transfer->toArray()),__METHOD__);
        
    }



    /**
     * get 
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function sendTransfer(Request $request)
    {
        $transfer = Transfer::query()
        ->where('from_user',auth()->id())
        ->when(isset($request->id),fn($query)=>$query->where('id',$request->id))
        ->when(isset($request->token_amount),fn($query)=>$query->where('token_amount',$request->token_amount))
        ->get(['id','token_amount','created_at'])
        ->each(function($transfer){

            $transfer->setAttribute('type','send');

        });
         
        return $this->api(TransferResource::collection($transfer->toArray()),__METHOD__);
    }

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
        
        $reciverUser = $this->reciverWallet($validatedRequest['user_address']); // find reciver User
        
        if($reciverUser->referral_code === $user->referral_code)
            return $this->api(null,__METHOD__,'you cant transfer to your wallet');



        $minusToken = $this->minusUserToken($validatedRequest["token_amount"],$user); // minuse user token

    
        if(! $minusToken)
            return $this->errorResponse("Receiver user not found!");

        // add new amount to user reciver
        $addTokenToWallet = $this->AddUserReceiverToken($reciverUser,$validatedRequest["token_amount"]);

        if(! $addTokenToWallet){ // if add wasn`t successful :
            // return tokens to user wallet
            $this->AddUserReceiverToken($user,$validatedRequest["token_amount"]);
            return $this->errorResponse("operation failed! token has been returned to your wallet"); // error response
        }

        $validatedRequest["from_user"] = auth()->id(); // Sender user = auth->user
        $validatedRequest["to_user"] = $reciverUser->id; // Reciver user
        $transfer = $transfer->addNewTransfer($validatedRequest); 

        return $this->api(["tranfer_amount" => $transfer->token_amount],__METHOD__);

    }

    /**
     * find reciver user
     * @param mixed $referalCode
     * @return User|null
     */
    public function reciverWallet($referalCode)
    {
        return User::query()
        ->where('referral_code',$referalCode)
        ->first();
    }



    /**
     * add token to reciver wallet
     * @param mixed $receiverWallet
     * @param int $amount
     */
    public function AddUserReceiverToken($receiver,int $amount)
    {
        $receiver->token_amount += $amount;
        return $receiver->save();
    }
    /**
     * minus user token
     * @param mixed $amount
     * @param mixed $user
     */
    public function minusUserToken($amount,$user)
    {   
       
        $user->token_amount -= $amount;
        return $user->save();
    }

    /**
     * Summary of findUserWallet
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function findUserWallet()
    {
        return auth()->user();
    }

    /**
     * check user is enough token
     * @param int $userGem
     * @param int $amount
     * @return bool
     */
    public function hasUserEnoughToken(int $userGem,int $amount)
    {
        if($userGem < $amount)
            return false;

        return true;
    }

}

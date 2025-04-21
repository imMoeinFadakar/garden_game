<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\User\WithdrawalResource;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\Withdrawal\WithdrawalRequest;

class WithdrawalControler extends BaseUserController
{   

    public function index()
    {
        $transaction = Transaction::query()
        ->where("user_id",auth()->id())
        ->get();

        return $this->api(WithdrawalResource::collection($transaction),__METHOD__);
    }


    public function withdrawal(WithdrawalRequest $request,Transaction $transaction)
    {
        // has user enough token
        $userToken = $this->hasUserToken($request->amount);
        if(! $userToken)
            return $this->errorResponse('dont have enough token');


        $minusToken = $this->minusUserToken($request->amount);
        if($minusToken){

            $newWithdrawalRequest = 
            [
                "user_id" => auth()->id(),
                "status" => "pending",
                "type" => "withdraw",
                "amount" => $request->amount
            ];
    
    
            $transaction = $transaction->addNewTransaction($newWithdrawalRequest);   
            return $this->api(new WithdrawalResource($transaction->toArray()));
    
        }

        return $this->errorResponse("operation failed"); 
    }
 

    public function minusUserToken(int $amount): bool
    {
        $user = auth()->user();
        $user->token_amount -= $amount;
        return $user->save();
    }

    public function hasUserToken(int $amount): bool
    {
        $user = auth()->user();
        if($user->token_amount < $amount)
            return false;

         return true;   
    }


}

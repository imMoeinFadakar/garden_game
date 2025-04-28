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
    /**
     * get all users transaction
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $transaction = Transaction::query()
        ->where("user_id",auth()->id())
        ->get(['id','status','type','amount']);

        return $this->api(WithdrawalResource::collection($transaction),__METHOD__);
    }

    /**
     * new withdraw request
     * @param \App\Http\Requests\V1\User\Withdrawal\WithdrawalRequest $request
     * @param \App\Models\Transaction $transaction
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function withdrawal(WithdrawalRequest $request,Transaction $transaction)
    {
        // has user enough token
        $userToken = $this->hasUserToken($request->amount); 
        if(! $userToken)
            return $this->errorResponse('dont have enough token');


        $minusToken = $this->minusUserToken($request->amount); 
        if($minusToken){

            // withdraw request
            $newWithdrawalRequest = 
            [
                "user_id" => auth()->id(),
                "status" => "pending",
                "type" => "withdraw",
                "amount" => $request->amount
            ];
    
            $transaction = $transaction->addNewTransaction($newWithdrawalRequest); // new tarnaction 
            $transaction->user_id = null;
            return $this->api(new WithdrawalResource($transaction->toArray()));
    
        }

        return $this->errorResponse("operation failed"); 
    }
 
    /**
     * minus user gem
     * @param int $amount
     * @return bool
     */
    public function minusUserToken(int $amount): bool
    {
        $user = auth()->user();
        $user->token_amount -= $amount;
        return $user->save();
    }

    /**
     * has user enough token
     * @param int $amount
     * @return bool
     */
    public function hasUserToken(int $amount): bool
    {
        $user = auth()->user();
        if($user->token_amount < $amount)
            return false;

         return true;   
    }


}

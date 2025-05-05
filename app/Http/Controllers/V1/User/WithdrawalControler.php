<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Resources\V1\User\WithdrawalResource;
use App\Models\AdderssUser;
use App\Models\GamePrice;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\Withdrawal\WithdrawalRequest;

class WithdrawalControler extends BaseUserController
{   

    public function userWithdraw()
    {
        $userWihdraw = Withdrawal::query()
        ->where("user_id",auth()->id())
        ->with(['wallet:id,address'])
        ->get(['id','amount','wallet_id','created_at']);

        return $this->api(WithdrawalResource::collection($userWihdraw),__METHOD__);

    }
  



    /**
     * get all users transaction
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $transaction = Transaction::query()
        ->where("user_id",auth()->id())
        ->with([])
        ->get(['id','status','type','amount','created_at']);

        return $this->api(WithdrawalResource::collection($transaction),__METHOD__);
    }

    /**
     * new withdraw request
     * @param \App\Http\Requests\V1\User\Withdrawal\WithdrawalRequest $request
     * @param \App\Models\Transaction $transaction
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function withdrawal(WithdrawalRequest $request,Withdrawal $withdrawal)
    {
        // has user enough token
        $userToken = $this->hasUserToken($request->amount); 
        if(! $userToken)
            return $this->errorResponse('dont have enough token');

        $minusToken = $this->minusUserToken($request->amount); 
        if($minusToken){


            $validated = $request->validated();

            // withdraw request
            $newWithdrawalRequest = 
            [
                "user_id" => auth()->id(),
                "wallet_id" => $validated["wallet_id"],
                "amount" => $validated['amount']
            ];
    

         
            $transaction = $withdrawal->addNewWithdrawal($newWithdrawalRequest); // new tarnaction 
            $transaction->user_id = null;
            return $this->api(new WithdrawalResource($transaction->toArray()));
    
        }

        return $this->errorResponse("operation failed"); 
    }
 
    public function findTokenPrice()
    {
        return GamePrice::query()
        ->where("unite",'like','%cent%')
        ->first();
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

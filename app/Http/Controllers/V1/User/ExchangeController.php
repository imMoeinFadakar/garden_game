<?php

namespace App\Http\Controllers\V1\User;

use App\Models\GamePrice;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\ExchangeRequest;
use App\Http\Resources\V1\Admin\TransactionResource;

class ExchangeController extends BaseUserController
{
    /**
     * Display a listing of the resource.
     */
    public function UserExchange(Request $request)
    {
        $allExchanges = Transaction::query()
        ->where('user_id',auth()->id())
        ->where("type",'exchange')
        ->get(['status','type','amount','created_at']);

        return $this->api(TransactionResource::collection($allExchanges),__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function newExchange(ExchangeRequest $request,Transaction $transaction)
    {
        
        $user = auth()->user(); // Find user

        $hasUserToken = $this->hasUserEnoughToken($user,$request); // Check user have enough token

        if(! $hasUserToken) // If user dosn`t have token
            return $this->api(null,__METHOD__,'You dont have enough token');


        $minusUserToken =  $this->minusUserToken($user,$request); // Minuse user token 
        if($minusUserToken){

            $gemValue  =$this->findGemPrice(); // Find gem price bas by token

            $newGemAmount  = $this->findGemAmount($request,$gemValue); // Calcluate gems
    
            $this->addNewGemAmout($user,$newGemAmount); // Update user gem amount
            
             $transaction->addNewTransaction([
                "user_id" => auth()->id(),
                "status" => "done",
                "type" => "exchange",
                "amount" =>  $request->token_amount
            ]);




            return  $this->api(["user_gem" => $user->gem_amount],__METHOD__,"Tokens successfully converted to gem");
            // success response
        }


        return $this->api(null,__METHOD__,'operation failed');
            // failure response
    
    }

    /**
     * Token Amount devided By gem value
     * @param mixed $request
     * @param mixed $gemValue
     * @return int
     */
    public function findGemAmount($request,$gemValue): int
    {
        return $request->token_amount / intval($gemValue->unite_price);
    }

    /**
     * update user gem amount
     * @param mixed $user
     * @param mixed $gem
     */
    public function addNewGemAmout($user,$gem)
    {
        $user->gem_amount += floor($gem);
        return $user->save();
    }

    /**
     *  find Total  gem value per token
     * @return GamePrice|null
     */
    public function findGemPrice()
    {
        return GamePrice::query()
        ->where("unite",'like','%token%')
        ->first();
    }



    /**
     * update user token amount 
     * @param mixed $user
     * @param mixed $request
     * @return bool
     */
    public function minusUserToken($user,$request): bool
    {
        $user->token_amount -= $request->token_amount;
      return $user->save();
       
    }


    /**
     * check has user enough token
     * @param mixed $user
     * @param mixed $request
     * @return bool
     */
    public function hasUserEnoughToken($user,$request): bool
    {

       if($user->token_amount < $request->token_amount)
            return false;

        return true;

    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

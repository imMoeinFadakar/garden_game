<?php

namespace App\Http\Controllers\V1\User;

use App\Models\GamePrice;
use App\Models\Transaction;
use App\Services\ExchangeService;
use Exception;
use App\Http\Requests\V1\User\ExchangeRequest;
use App\Http\Resources\V1\Admin\TransactionResource;

class ExchangeController extends BaseUserController
{

    protected ExchangeService $exchangeService;

    public function __construct(ExchangeService $exchangeService)
    {
        $this->exchangeService = $exchangeService;
    }



    /**
     * Display a listing of the resource.
     */
    public function UserExchangeHistory()
    {
        $user = auth()->user();
        $cacheKey = "user_exchange_history" . $user->id;


        $allExchanges = cache()->remember($cacheKey, 300, function () {
        return Transaction::query()
            ->where('user_id', auth()->id())
            ->where("type", 'exchange')
            ->get(['status', 'type', 'amount', 'created_at']);
        });

        return $this->api(TransactionResource::collection($allExchanges),__METHOD__);
    }


    public function getUserBalance()
    {
        $userId = auth()->id();
        $cacheKey = 'user_balance_' . $userId;

      
        $balance = cache()->get($cacheKey);

        if (!$balance) {
         
            $user = auth()->user();

            $balance = [
                'token_amount' => $user->token_amount,
                'gem_amount' => $user->gem_amount,
            ];

            cache()->put($cacheKey, $balance, now()->addMinutes(5));
        }

        return $balance;
    }



    /**
     * Store a newly created resource in storage.
     */
  public function exchangeTokenToGem(ExchangeRequest $request, Transaction $transaction)
{
    try {
        $user = auth()->user(); // Find user

       
        $updatedUser = $this->exchangeService->convert($user, $request->token_amount);

      
        return $this->api([
            'gem_amount' => $updatedUser->gem_amount,
            'token_amount' => $updatedUser->token_amount
        ], __METHOD__, 'Tokens successfully converted to gems.');

    } catch (\InvalidArgumentException $e) {
        return $this->api(null, __METHOD__, $e->getMessage(), 422);

    } catch (\Exception $e) {
        return $this->api(null, __METHOD__, 'Unexpected error occurred. ' . $e->getMessage(), 500);
    }
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
    public function addNewGemAmount($user,$gem)
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
    public function deductUserTokenAmount($user,$request): bool
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

}

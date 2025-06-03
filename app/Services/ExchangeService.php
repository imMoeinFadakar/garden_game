<?php 

namespace App\Services;

use App\Models\GamePrice;
use App\Models\Transaction;
use App\Models\User;
use App\Trait\RandomNumberTrait;
use Cache;


class ExchangeService {

    use RandomNumberTrait;
    public function convert(User $user, int $tokenAmount)
    {
         if ($tokenAmount <= 0) {
            throw new \InvalidArgumentException("Invalid token amount.");
        }

        if ($user->token_amount < $tokenAmount) {
            throw new \InvalidArgumentException("You do not have enough tokens.");
        }

        $gemPrice = $this->getGemPrice();

        if (!$gemPrice || $gemPrice->unite_price <= 0) {
            throw new \Exception("Invalid gem price configuration.");
        }

        $gemAmount = floor($tokenAmount / $gemPrice->unite_price);

       
        \DB::transaction(function () use ($user, $tokenAmount, $gemAmount) {
           
            $user->decrement('token_amount', $tokenAmount);
            
            $user->increment('gem_amount', $gemAmount);


            $transactionNumber = (int) $this->createTransactionNumber();


            Transaction::create([
                'user_id' => $user->id,
                'type'    => 'exchange',
                'status'  => 'done',
                'amount'  => $tokenAmount,
                'transaction_number' => $transactionNumber
            ]);


             $cacheKey = "user_exchange_history" . $user->id;

             Cache::forget($cacheKey);

        });

       
        $user->refresh();

        return $user;
    }

    public function getGemPrice(): ?GamePrice
    {
        return GamePrice::query()
        ->where('unite','like','%token%')
        ->latest()
        ->first();
    }




}

<?php

namespace App\Http\Controllers\V1\User;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\V1\User\WithdrawalResource;
use App\Http\Requests\V1\User\Wallet\StoreWalletRequest;
use App\Http\Requests\V1\User\Wallet\DeleteWalletRequest;

class WalletController extends BaseUserController
{
public function getUserwallet(Request $request)
{
    $cacheKey = "user_wallets_" . auth()->id();

    $wallets = Cache::remember($cacheKey,
     now()->addMinutes(10),
      function () {
        return Wallet::query()
            ->where("user_id", auth()->id())
            ->get(['id', 'address', 'created_at']);
    });

    return $this->api(WithdrawalResource::collection($wallets), __METHOD__);
}

    public function newUserwallet(StoreWalletRequest $request , Wallet $wallet)
    {
        $userWallets = $this->userWalletCount();

        $hasUserWallet = $this->maxUserWallet($userWallets);
        if(! $hasUserWallet)
            return $this->api(null,
            __METHOD__,
            'you reached your maximum wallet number');


        $newAddressUser = 
        [
            "user_id" => auth()->id(),
            "address" => $request->address
        ];


       $newWallet =  $wallet->addNewWallet($newAddressUser);

        Cache::forget("user_wallets_" . auth()->id());

        $newWallet->user_id = null;
        return $this->api(new Wallet($newWallet->toArray()),__METHOD__);
    }
    public function userWalletCount(): int
    {
        return Wallet::query()
        ->where('user_id',auth()->id())
        ->count();
    }

    public function maxUserWallet(int $userWalletCount): bool
    {
        
        if($userWalletCount >= 5)
            return false;


        return true;


    }


    public function deleteUserWallet(DeleteWalletRequest $request,Wallet $wallet)
    {
        
        $wallet->query()->find($request->wallet_id)->delete();

        Cache::forget("user_wallets_" . auth()->id());

        return $this->api(null,__METHOD__,'deleted successfully');

    }


}
